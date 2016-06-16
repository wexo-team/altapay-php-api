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

use Altapay\Api\Others\Terminals;
use Altapay\Response\Embeds\Terminal;
use Altapay\Response\TerminalsResponse as TerminalsDocument;
use Altapay\Response\TerminalsResponse;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class TerminalsTest extends AbstractApiTest
{

    /**
     * @return Terminals
     */
    protected function getTerminals()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/terminals.xml'))
        ]));

        return (new Terminals($this->getAuth()))
            ->setClient($client)
        ;
    }

    public function test_url()
    {
        $api = $this->getTerminals();
        $api->call();
        $this->assertEquals($this->getExceptedUri('getTerminals'), $api->getRawRequest()->getUri()->getPath());
    }

    public function test_header()
    {
        $api = $this->getTerminals();
        /** @var TerminalsDocument $response */
        $response = $api->call();
        $this->assertInstanceOf(\DateTime::class, $response->Header->Date);
        $this->assertEquals('07-01-2016', $response->Header->Date->format('d-m-Y'));
        $this->assertEquals('API/getTerminals', $response->Header->Path);
        $this->assertEquals('0', $response->Header->ErrorCode);
        $this->assertEquals('', $response->Header->ErrorMessage);
    }

    public function test_response()
    {
        $api = $this->getTerminals();
        /** @var TerminalsDocument $response */
        $response = $api->call();

        $this->assertCount(2, $response->Terminals);
        $this->assertEquals('Success', $response->Result);
    }

    /**
     * @depends test_response
     */
    public function test_response_terminal()
    {
        $api = $this->getTerminals();
        /** @var Terminal $terminal */
        /** @var TerminalsResponse $response */
        $response = $api->call();
        $this->assertCount(2, $response->Terminals);

        $terminal = $response->Terminals[0];
        $this->assertEquals('AltaPay Multi-Nature Terminal', $terminal->Title);
        $this->assertEquals('DK', $terminal->Country);
        $this->assertCount(4, $terminal->Natures);
    }

}
