<?php
namespace Altapay\ApiTest\Api;

use Altapay\Api\AbstractApi;
use Altapay\Api\Authentication;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

abstract class AbstractApiTest extends \PHPUnit_Framework_TestCase
{

    protected function getAuth()
    {
        return new Authentication('test_username', 'test_password');
    }

    protected function getClient(MockHandler $mock)
    {
        $handler = HandlerStack::create($mock);
        return new Client(['handler' => $handler]);
    }

    protected function getExceptedUri($uri)
    {
        return '/merchant/API/' . $uri;
    }

}
