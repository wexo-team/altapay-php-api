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

use Altapay\Api\Credit;
use Altapay\Api\Request\Card;
use Altapay\Api\Exceptions\CreditCardTokenAndCardUsedException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class CreditTest extends AbstractApiTest
{

    /**
     * @return Credit
     */
    protected function getCredit()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/customreport.txt'))
        ]));

        return (new Credit())
            ->setClient($client)
            ->setAuthentication($this->getAuth())
        ;
    }

    public function test_options()
    {
        $this->setExpectedException(CreditCardTokenAndCardUsedException::class);

        $card = new Card(1234, 12, 12, 122);
        $api = $this->getCredit();
        $api->setTerminal('123');
        $api->setShopOrderId(123);
        $api->setAmount(20.44);
        $api->setCurrency(967);
        $api->setCard($card);
        $api->setCreditCardToken('12345');
        $api->call();
    }

    public function test_creditcard_options()
    {
        $card = new Card(1234567890, 5, 19, 122);
        $api = $this->getCredit();
        $api->setTerminal('terminal');
        $api->setShopOrderId(123);
        $api->setAmount(20.44);
        $api->setCurrency(967);
        $api->setCard($card);
        $api->call();
        $request = $api->getRawRequest();

        $this->assertEquals($this->getExceptedUri('credit'), $request->getUri()->getPath());
        parse_str($request->getUri()->getQuery(), $parts);
        $this->assertEquals('terminal', $parts['terminal']);
        $this->assertEquals(123, $parts['shop_orderid']);
        $this->assertEquals(20.44, $parts['amount']);
        $this->assertEquals(967, $parts['currency']);
        $this->assertEquals(1234567890, $parts['cardnum']);
        $this->assertEquals(5, $parts['emonth']);
        $this->assertEquals(19, $parts['eyear']);
        $this->assertEquals(122, $parts['cvc']);

    }

    public function test_creditcardtoken_options()
    {
        $api = $this->getCredit();
        $api->setTerminal('terminal');
        $api->setShopOrderId(123);
        $api->setAmount(20.44);
        $api->setCurrency(967);
        $api->setCreditCardToken('token');
        $api->call();
        $request = $api->getRawRequest();

        $this->assertEquals($this->getExceptedUri('credit'), $request->getUri()->getPath());
        parse_str($request->getUri()->getQuery(), $parts);
        $this->assertEquals('terminal', $parts['terminal']);
        $this->assertEquals(123, $parts['shop_orderid']);
        $this->assertEquals(20.44, $parts['amount']);
        $this->assertEquals(967, $parts['currency']);
        $this->assertEquals('token', $parts['credit_card_token']);
    }

    public function paymentSourceDataProvider()
    {
        return [Credit::ALLOWEDPAYMENTSOURCES];
    }

    /**
     * @dataProvider paymentSourceDataProvider
     * @param string $type
     */
    public function test_paymentsource_options($type)
    {
        $api = $this->getCredit();
        $api->setTerminal('terminal');
        $api->setShopOrderId(123);
        $api->setAmount(20.44);
        $api->setCurrency(967);
        $api->setCreditCardToken('token');
        $api->setPaymentSource($type);
        $api->call();
    }

    public function test_paymentsource_invalid_options()
    {
        $this->setExpectedException(InvalidOptionsException::class);

        $api = $this->getCredit();
        $api->setTerminal('terminal');
        $api->setShopOrderId(123);
        $api->setAmount(20.44);
        $api->setCurrency(967);
        $api->setCreditCardToken('token');
        $api->setPaymentSource('webshop');
        $api->call();
    }

}
