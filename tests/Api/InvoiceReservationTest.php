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

namespace Altapay\ApiTest\Api;

use Altapay\Api\Payments\InvoiceReservation;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class InvoiceReservationTest extends AbstractApiTest
{

    /**
     * @return InvoiceReservation
     */
    protected function getapi()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/invoicereservation.xml'))
        ]));

        return (new InvoiceReservation($this->getAuth()))
            ->setClient($client)
        ;
    }

    public function test_missing_all_options()
    {
        $this->setExpectedException(
            MissingOptionsException::class,
            'The required options "amount", "currency", "shop_orderid", "terminal" are missing.'
        );
        $this->getapi()->call();
    }

    public function test_url()
    {
        $api = $this->getapi();
        $api->setTerminal('my terminal');
        $api->setAmount(200.50);
        $api->setCurrency(957);
        $api->setShopOrderId('order id');
        $api->call();
        $request = $api->getRawRequest();

        $this->assertEquals($this->getExceptedUri('createInvoiceReservation/'), $request->getUri()->getPath());
        parse_str($request->getUri()->getQuery(), $parts);
        $this->assertEquals('my terminal', $parts['terminal']);
        $this->assertEquals('order id', $parts['shop_orderid']);
        $this->assertEquals(200.50, $parts['amount']);
        $this->assertEquals(957, $parts['currency']);
    }

    public function test_options()
    {
        $api = $this->getapi();
        $api->setTerminal('my terminal');
        $api->setAmount(200.50);
        $api->setCurrency(957);
        $api->setShopOrderId('order id');

        $api->setType('subscriptionAndCharge');
        $api->setAccountNumber('account');
        $api->setPaymentSource('mail_order');
        $api->setBankCode('code');
        $api->setFraudService('maxmind');

        $api->call();
        $request = $api->getRawRequest();

        parse_str($request->getUri()->getQuery(), $parts);
        $this->assertEquals('subscriptionAndCharge', $parts['type']);
        $this->assertEquals('account', $parts['accountNumber']);
        $this->assertEquals('mail_order', $parts['payment_source']);
        $this->assertEquals('code', $parts['bankCode']);
        $this->assertEquals('maxmind', $parts['fraud_service']);
    }

}
