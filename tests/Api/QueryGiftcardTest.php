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

use Altapay\Api\Others\QueryGiftcard;
use Altapay\Request\Giftcard;
use Altapay\Response\GiftcardResponse as GiftcardResponse;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class QueryGiftcardTest extends AbstractApiTest
{

    /**
     * @return QueryGiftcard
     */
    protected function getapi()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/querygiftcard.xml'))
        ]));

        return (new QueryGiftcard($this->getAuth()))
            ->setClient($client)
        ;
    }

    public function test_route()
    {
        $card = new Giftcard('account', 'provider', '1234-1234');
        $api = $this->getapi();
        $api->setTerminal('my terminal');
        $api->setGiftcard($card);
        $api->call();

        $this->assertEquals($this->getExceptedUri('queryGiftCard/'), $api->getRawRequest()->getUri()->getPath());
        parse_str($api->getRawRequest()->getUri()->getQuery(), $parts);

        $this->assertEquals('my terminal', $parts['terminal']);
        $this->assertEquals('account', $parts['giftcard']['account_identifier']);
        $this->assertEquals('provider', $parts['giftcard']['provider']);
        $this->assertEquals('1234-1234', $parts['giftcard']['token']);

    }

    public function test_response()
    {
        $card = new Giftcard('account', 'provider', '1234-1234');
        $api = $this->getapi();
        $api->setTerminal('my terminal');
        $api->setGiftcard($card);
        /** @var GiftcardResponse $response */
        $response = $api->call();

        $this->assertEquals('Success', $response->Result);
        $this->assertCount(2, $response->Accounts);
        $account = $response->Accounts[0];

        $this->assertEquals('EUR', $account->Currency);
        $this->assertEquals('50.00', $account->Balance);

    }

}
