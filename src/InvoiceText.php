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

use Altapay\Api\Response\InvoiceText as InvoiceTextResponse;
use Altapay\Api\Serializer\ResponseSerializer;
use Altapay\Api\Traits\TransactionsTrait;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * GetInvoiceText is used for gathering information to print on the customer invoice for invoice/Arvato payments.
 * This is typically used when merchants print their own invoices from their backend system.
 * As the invoice sent to the customer needs to include some information from Arvato,
 * this call is mandatory if you use Arvato's PayByBill-product.
 */
class InvoiceText extends AbstractApi
{

    use TransactionsTrait;

    /**
     * If you do not want to invoice the full amount a smaller amount can be captured
     *
     * @param int|float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->unresolvedOptions['amount'] = $amount;
        return $this;
    }

    /**
     * Configure options
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['amount']);
        $resolver->setAllowedTypes('amount', ['int', 'float']);
    }

    /**
     * Handle response
     *
     * @param Request $request
     * @param Response $response
     * @return InvoiceTextResponse
     */
    protected function handleResponse(Request $request, Response $response)
    {
        $body = (string) $response->getBody();
        $xml = simplexml_load_string($body);
        return ResponseSerializer::serialize(InvoiceTextResponse::class, $xml->Body->InvoiceText, false, $xml->Header);
    }

    /**
     * Url to api call
     *
     * @param array $options Resolved options
     * @return string
     */
    public function getUrl(array $options)
    {
        $query = $this->buildUrl($options);
        return sprintf('getInvoiceText/?%s', $query);
    }
}
