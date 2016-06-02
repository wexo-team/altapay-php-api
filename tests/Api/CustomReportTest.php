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

use Altapay\Api\Others\CustomReport;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class CustomReportTest extends AbstractApiTest
{

    /**
     * @return CustomReport
     */
    protected function getCustomReport()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(200, ['text-content' => 'application/xml'], file_get_contents(__DIR__ . '/Results/customreport.txt'))
        ]));

        return (new CustomReport($this->getAuth()))
            ->setClient($client)
        ;
    }

    public function test_custom_download_with_id()
    {
        $api = $this->getCustomReport();
        $api->setCustomReportId('0c55e643-49c2-492c-ab61-1014426dce5d');
        $api->call();

        $this->assertEquals($this->getExceptedUri('getCustomReport'), $api->getRawRequest()->getUri()->getPath());
        parse_str($api->getRawRequest()->getUri()->getQuery(), $parts);
        $this->assertEquals('0c55e643-49c2-492c-ab61-1014426dce5d', $parts['id']);
    }

    public function test_funding_download()
    {
        $api = $this->getCustomReport();
        $api->setCustomReportId('0c55e643-49c2-492c-ab61-1014426dce5d');
        $response = $api->call();
        $this->assertStringStartsWith('"Order ID";', $response);

        $csv = $api->__toArray(true);
        $this->assertCount(2, $csv);

        $csv = $api->__toArray(false);
        $this->assertCount(1, $csv);
    }

}
