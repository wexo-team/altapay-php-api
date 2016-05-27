<?php
/**
 * Copyright (c) 2016 Martin Aarhof
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Altapay\Api;

use Altapay\Api\Event\BeforeHandlingResponseEvent;
use Altapay\Api\Event\BeforeClientSendEvent;
use Altapay\Api\Event\AfterResolveOptionsEvent;
use Altapay\Api\Event\BeforeRequestEvent;
use Altapay\Api\Exceptions\AuthenticationRequiredException;
use Altapay\Api\Exceptions\ClientException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException as GuzzleHttpClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractApi
 */
abstract class AbstractApi
{

    /**
     * Test gateway url
     */
    const TESTBASEURL = 'https://testgateway.altapaysecure.com';

    /**
     * Api version
     */
    const VERSION = 'API';

    /**
     * Event dispatcher
     *
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * Not resolved options
     *
     * @var array
     */
    public $unresolvedOptions;

    /**
     * Filters to go into the url
     *
     * @var array
     */
    protected $definedFilters = [];

    /**
     * Resolved options
     *
     * @var array
     */
    protected $options;

    /**
     * Request of the call
     *
     * @var Request
     */
    private $request;

    /**
     * Response of the call
     *
     * @var Response
     */
    private $response;

    /**
     * Base url
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Authentication
     *
     * @var Authentication
     */
    private $authentication;

    /**
     * HTTP client to use
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * Configure options
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    abstract protected function configureOptions(OptionsResolver $resolver);

    /**
     * Handle response
     *
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    abstract protected function handleResponse(Request $request, Response $response);

    /**
     * Url to api call
     *
     * @param array $options Resolved options
     * @return string
     */
    abstract public function getUrl(array $options);

    /**
     * AbstractApi constructor.
     *
     * @param Authentication $authentication
     */
    public function __construct(Authentication $authentication = null)
    {
        $this->unresolvedOptions = [];
        $this->dispatcher = new EventDispatcher();
        $this->httpClient = new Client();
        $this->authentication = $this->setAuthentication($authentication);
    }

    /**
     * Generate the response
     *
     * @return mixed
     */
    public function call()
    {
        return $this->doResponse();
    }

    /**
     * Set authentication
     *
     * @param Authentication $authentication
     * @return $this
     */
    public function setAuthentication(Authentication $authentication = null)
    {
        if ($authentication) {
            $this->authentication = $authentication;
            $this->baseUrl = $authentication->getBaseurl();
        }

        return $this;
    }

    /**
     * Set HTTP client
     *
     * @param ClientInterface $client
     * @return $this
     */
    public function setClient(ClientInterface $client)
    {
        $this->httpClient = $client;
        return $this;
    }

    /**
     * Get the raw request
     * It is made after call() method has been called
     *
     * @return Request
     */
    public function getRawRequest()
    {
        return $this->request;
    }

    /**
     * Get the raw response
     * It is made after call() method has been called
     *
     * @return Response
     */
    public function getRawResponse()
    {
        return $this->response;
    }

    /**
     * HTTP method in use
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'GET';
    }

    /**
     * Handle exception response
     *
     * @param ClientException $exception
     * @throws ClientException
     * @return bool|void
     */
    protected function handleExceptionResponse(ClientException $exception)
    {
        throw $exception;
    }

    /**
     * Resolve options
     */
    protected function doConfigureOptions()
    {
        $resolver = new OptionsResolver();

        if ($this->authRequired() && ! $this->authentication instanceof Authentication) {
            throw new AuthenticationRequiredException();
        }

        $this->setTransactionResolver($resolver);
        $this->setOrderLinesResolver($resolver);

        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($this->unresolvedOptions);

        $this->dispatcher->dispatch(
            AfterResolveOptionsEvent::NAME,
            new AfterResolveOptionsEvent($this->options)
        );
    }

    /**
     * Generate the response
     */
    protected function doResponse()
    {
        $this->doConfigureOptions();
        $headers = $this->getBasicHeaders();
        $request = new Request(
            $this->getHttpMethod(),
            $this->parseUrl(),
            $headers
        );

        $this->dispatcher->dispatch(BeforeRequestEvent::NAME, new BeforeRequestEvent($request));
        $this->request = $request;

        $this->dispatcher->dispatch(BeforeClientSendEvent::NAME, new BeforeClientSendEvent($request));
        try {
            $response = $this->getClient()->send($request);
            $this->response = $response;

            $this->dispatcher->dispatch(
                BeforeHandlingResponseEvent::NAME,
                new BeforeHandlingResponseEvent($request, $response)
            );

            return $this->handleResponse($request, $response);
        } catch (GuzzleHttpClientException $e) {
            $exception = new ClientException($e->getMessage(), $e->getRequest(), $e->getResponse());
            return $this->handleExceptionResponse($exception);
        }
    }

    /**
     * Parse the URL
     *
     * @return string
     */
    protected function parseUrl()
    {
        return sprintf(
            '%s/merchant/%s/%s',
            $this->baseUrl ?: self::TESTBASEURL,
            self::VERSION,
            $this->getUrl($this->options)
        );
    }

    /**
     * Build url
     *
     * @param array $options
     * @return bool|string
     */
    protected function buildUrl(array $options)
    {
        if (! $options) {
            return false;
        }

        return http_build_query($options);
    }

    /**
     * Is authentication required for this
     *
     * @return bool
     */
    protected function authRequired()
    {
        return true;
    }

    /**
     * Set the headers to the API call
     *
     * @return array
     */
    protected function getBasicHeaders()
    {
        $headers = [];

        if ($this->authRequired()) {
            $headers['Authorization'] = sprintf(
                'Basic %s',
                base64_encode($this->authentication->getUsername() . ':' . $this->authentication->getPassword())
            );
        }

        return $headers;
    }

    /**
     * Get the HTTP client
     *
     * @return Client
     */
    private function getClient()
    {
        return $this->httpClient;
    }

    /**
     * Resolve transaction
     *
     * @param OptionsResolver $resolver
     */
    protected function setTransactionResolver(OptionsResolver $resolver)
    {
    }

    /**
     * Resolve orderlines
     *
     * @param OptionsResolver $resolver
     */
    protected function setOrderLinesResolver(OptionsResolver $resolver)
    {
    }
}
