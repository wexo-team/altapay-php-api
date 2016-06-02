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

use Altapay\Api\Payments\ReleaseReservation;
use Altapay\Response\Embeds\Transaction;
use Altapay\Response\ReleaseReservationResponse as ReleaseReservationDocument;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ReleaseReservationTest extends AbstractApiTest
{

    /**
     * @return ReleaseReservation
     */
    protected function getReleaseReservation()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/release.xml'))
        ]));

        return (new ReleaseReservation($this->getAuth()))
            ->setClient($client)
        ;
    }

    public function test_release_reservation()
    {
        $api = $this->getReleaseReservation();
        $api->setTransactionId(123);
        $this->assertInstanceOf(ReleaseReservationDocument::class, $api->call());
    }

    /**
     * @depends test_release_reservation
     */
    public function test_release_data()
    {
        $api = $this->getReleaseReservation();
        $api->setTransactionId(123);
        /** @var ReleaseReservationDocument $response */
        $response = $api->call();
        $this->assertEquals('Success', $response->Result);
        $this->assertCount(1, $response->Transactions);
    }

    public function test_release_reservation_querypath()
    {
        $transaction = new Transaction();
        $transaction->TransactionId = 456;

        $api = $this->getReleaseReservation();
        $api->setTransaction($transaction);
        $api->call();
        $request = $api->getRawRequest();

        $this->assertEquals($this->getExceptedUri('releaseReservation/'), $request->getUri()->getPath());
        parse_str($request->getUri()->getQuery(), $parts);
        $this->assertEquals(456, $parts['transaction_id']);

        $api = $this->getReleaseReservation();
        $api->setTransactionId('helloworld');
        $api->call();
        $request = $api->getRawRequest();
        parse_str($request->getUri()->getQuery(), $parts);
        $this->assertEquals('helloworld', $parts['transaction_id']);

        $api = $this->getReleaseReservation();
        $api->setTransactionId('my trans id has spaces');
        $api->call();
        $request = $api->getRawRequest();
        parse_str($request->getUri()->getQuery(), $parts);
        $this->assertEquals('my trans id has spaces', $parts['transaction_id']);
    }

    public function test_capture_reservation_transaction_handleexception()
    {
        $this->setExpectedException(ClientException::class);

        $transaction = new Transaction();
        $transaction->TransactionId = 456;

        $client = $this->getClient($mock = new MockHandler([
            new Response(400, ['text-content' => 'application/xml'])
        ]));

        $api = (new ReleaseReservation($this->getAuth()))
            ->setClient($client)
            ->setTransactionId(123)
        ;
        $api->call();
    }

}
