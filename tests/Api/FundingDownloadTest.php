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

use Altapay\Api\Others\FundingDownload;
use Altapay\Response\Embeds\Funding;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class FundingDownloadTest extends AbstractApiTest
{

    /**
     * @return FundingDownload
     */
    protected function getFundingDownload()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/fundingdownload.txt'))
        ]));

        return (new FundingDownload($this->getAuth()))
            ->setClient($client)
        ;
    }

    public function test_funding_download_with_link()
    {
        $api = $this->getFundingDownload();
        $api->setFundingDownloadLink('https://myshop.altapaysecure.com/merchant/API/fundingDownload?id=32');
        $api->call();

        $this->assertEquals('myshop.altapaysecure.com', $api->getRawRequest()->getUri()->getHost());
        $this->assertEquals('/merchant/API/fundingDownload', $api->getRawRequest()->getUri()->getPath());
        parse_str($api->getRawRequest()->getUri()->getQuery(), $parts);
        $this->assertEquals(32, $parts['id']);

    }

    public function test_funding_download_with_object()
    {
        $funding = new Funding();
        $funding->DownloadLink = 'https://thisismyshop.altapaysecure.com/merchant/API/fundingDownload?id=99';

        $api = $this->getFundingDownload();
        $api->setFunding($funding);
        $api->call();

        $this->assertEquals('thisismyshop.altapaysecure.com', $api->getRawRequest()->getUri()->getHost());
        $this->assertEquals('/merchant/API/fundingDownload', $api->getRawRequest()->getUri()->getPath());
        parse_str($api->getRawRequest()->getUri()->getQuery(), $parts);
        $this->assertEquals(99, $parts['id']);
    }

    public function test_funding_download()
    {
        $api = $this->getFundingDownload();
        $api->setFundingDownloadLink('https://myshop.altapaysecure.com/merchant/API/fundingDownload?id=32');
        $response = $api->call();
        $this->assertStringStartsWith('Date;', $response);

        $csv = $api->__toArray(true);
        $this->assertCount(2, $csv);

        $csv = $api->__toArray(false);
        $this->assertCount(1, $csv);
    }

}
