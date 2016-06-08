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

namespace Altapay\ApiTest\Request;

use Altapay\Request\OrderLine;

class OrderLineTest extends \PHPUnit_Framework_TestCase
{

    public function test_orderline()
    {
        $line = new OrderLine('description', 12, 2, 12.50);
        $line->setGoodsType('item');
        $line->taxAmount = 4.75;
        $line->unitCode = 'code';
        $line->discount = 1;
        $line->imageUrl = 'https://image.com';

        $serialized = $line->serialize();

        $this->assertArrayHasKey('description', $serialized);
        $this->assertArrayHasKey('itemId', $serialized);
        $this->assertArrayHasKey('quantity', $serialized);
        $this->assertArrayHasKey('unitPrice', $serialized);
        $this->assertArrayHasKey('taxAmount', $serialized);
        $this->assertArrayHasKey('unitCode', $serialized);
        $this->assertArrayHasKey('discount', $serialized);
        $this->assertArrayHasKey('goodsType', $serialized);
        $this->assertArrayHasKey('imageUrl', $serialized);

        $this->assertEquals('description', $serialized['description']);
        $this->assertEquals(12, $serialized['itemId']);
        $this->assertEquals(2, $serialized['quantity']);
        $this->assertEquals(12.50, $serialized['unitPrice']);
        $this->assertEquals(4.75, $serialized['taxAmount']);
        $this->assertEquals('code', $serialized['unitCode']);
        $this->assertEquals(1, $serialized['discount']);
        $this->assertEquals('item', $serialized['goodsType']);
        $this->assertEquals('https://image.com', $serialized['imageUrl']);

    }

    public function dataProvider()
    {
        return [
            ['shipment'],
            ['handling'],
            ['item'],
            ['no_item', true]
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param string $type
     * @param bool $exception
     */
    public function test_can_not_set_goodstypes($type, $exception = false)
    {
        if ($exception) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('goodsType should be one of "shipment|handling|item" you have selected "' . $type . '"');
        }

        $line = new OrderLine('description', 12, 2, 12.50);
        $line->setGoodsType($type);
        $s = $line->serialize();

        $this->assertEquals($type, $s['goodsType']);
    }

    public function test_can_not_set_both_tax_types()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only one of "taxPercent" and "taxAmount" should be used');

        $line = new OrderLine('description', 12, 2, 12.50);
        $line->taxAmount = 4.75;
        $line->taxPercent = 25;
        $line->serialize();
    }

}
