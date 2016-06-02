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

use Altapay\Api\Others\FundingList;
use Altapay\Response\Embeds\Funding;
use Altapay\Response\Embeds\Shop;
use Altapay\Response\FundingsResponse;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class FundingListTest extends AbstractApiTest
{

    /**
     * @return FundingList
     */
    protected function getMultipleFundingsList()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/multiplefundinglist.xml'))
        ]));

        return (new FundingList($this->getAuth()))
            ->setClient($client)
        ;
    }

    /**
     * @return FundingList
     */
    protected function getSingleFundingsList()
    {
        $client = $this->getClient(new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/singlefundinglist.xml'))
        ]));

        return (new FundingList($this->getAuth()))
            ->setClient($client)
        ;
    }

    public function test_fundlinglist_routing()
    {
        $api = $this->getMultipleFundingsList();
        $api->call();

        $this->assertEquals($this->getExceptedUri('fundingList/'), $api->getRawRequest()->getUri()->getPath());
        parse_str($api->getRawRequest()->getUri()->getQuery(), $parts);

        $this->assertEquals(0, $parts['page']);

        $api = $this->getMultipleFundingsList();
        $api->setPage(9);
        $api->call();

        $this->assertEquals($this->getExceptedUri('fundingList/'), $api->getRawRequest()->getUri()->getPath());
        parse_str($api->getRawRequest()->getUri()->getQuery(), $parts);
        $this->assertEquals(9, $parts['page']);
    }

    public function test_fundlinglist_single()
    {
        $api = $this->getSingleFundingsList();
        /** @var FundingsResponse $response */
        $response = $api->call();
        $this->assertCount(1, $response->Fundings, 'num fundings');
    }

    public function test_fundlinglist_multiple()
    {
        $api = $this->getMultipleFundingsList();
        /** @var FundingsResponse $response */
        $response = $api->call();
        $this->assertCount(2, $response->Fundings, 'num fundings');
    }

    /**
     * @depends test_fundlinglist_multiple
     */
    public function test_funding_object()
    {
        $api = $this->getMultipleFundingsList();
        /** @var Funding $response */
        $response = $api->call()->Fundings[0];

        $this->assertEquals('CreatedByTest', $response->Filename);
        $this->assertEquals('1234567890123456', $response->ContractIdentifier);
        $this->assertCount(2, $response->Shops);
        $this->assertEquals('TestAcquirer', $response->Acquirer);
        $this->assertInstanceOf(\DateTime::class, $response->FundingDate);
        $this->assertEquals('26-09-2010', $response->FundingDate->format('d-m-Y'));
        $this->assertEquals('50.00 EUR', $response->Amount);
        $this->assertInstanceOf(\DateTime::class, $response->CreatedDate);
        $this->assertEquals('27-09-2010', $response->CreatedDate->format('d-m-Y'));
        $this->assertEquals('http://localhost/merchant.php/API/fundingDownload?id=1', $response->DownloadLink);

    }

    /**
     * @depends test_funding_object
     */
    public function test_funding_object_shops()
    {
        $api = $this->getMultipleFundingsList();
        /** @var Shop $response */
        $response = $api->call()->Fundings[0]->Shops[0];
        $this->assertEquals('AltaPay Functional Test Shop', $response->Shop);

        $api = $this->getMultipleFundingsList();
        /** @var Shop $response */
        $response = $api->call()->Fundings[0]->Shops[1];
        $this->assertEquals('AltaPay Functional Test Shop Two', $response->Shop);
    }

}
