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

use Altapay\Exceptions\ResponseMessageException;
use Altapay\Response\SetupSubscriptionResponse as SetupSubscriptionResponse;
use Altapay\Api\Subscription\SetupSubscription;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class SetupSubscriptionTest extends AbstractApiTest
{

    /**
     * @return SetupSubscription
     */
    protected function getapi()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/reservationoffixedamount.xml'))
        ]));

        return (new SetupSubscription($this->getAuth()))
            ->setClient($client)
        ;
    }

    public function test_charge_subscription_error()
    {
        $this->setExpectedException(
            ResponseMessageException::class,
            'TestAcquirer[pan=1466 or amount=14660]'
        );

        $client = $this->getClient($mock = new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/setupsubscription_fail.xml'))
        ]));

        $api = (new SetupSubscription($this->getAuth()))
            ->setClient($client)
        ;
        $api->setTerminal('my terminal');
        $api->setAmount(200.50);
        $api->setCurrency(957);
        $api->setShopOrderId('order id');
        $api->setSurcharge(155.23);
        $api->call();
    }

    public function test_set_type()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class,
            'Type can not be set on a subscription'
        );

        $api = $this->getapi();
        $api->setType('Hello World');
    }

    public function test_url()
    {
        $api = $this->getapi();
        $api->setTerminal('my terminal');
        $api->setAmount(200.50);
        $api->setCurrency(957);
        $api->setShopOrderId('order id');
        $api->setSurcharge(155.23);
        $api->call();
        $request = $api->getRawRequest();

        $this->assertEquals($this->getExceptedUri('setupSubscription/'), $request->getUri()->getPath());
        parse_str($request->getUri()->getQuery(), $parts);
        $this->assertEquals('my terminal', $parts['terminal']);
        $this->assertEquals('order id', $parts['shop_orderid']);
        $this->assertEquals(200.50, $parts['amount']);
        $this->assertEquals(957, $parts['currency']);
        $this->assertEquals(155.23, $parts['surcharge']);
    }

    public function test_response()
    {
        $api = $this->getapi();
        $api->setTerminal('my terminal');
        $api->setAmount(200.50);
        $api->setCurrency(957);
        $api->setShopOrderId('order id');

        /** @var SetupSubscriptionResponse $response */
        $response = $api->call();

        $this->assertInstanceOf(SetupSubscriptionResponse::class, $response);
        $this->assertEquals('Success', $response->Result);
        $this->assertCount(1, $response->Transactions);
    }

}
