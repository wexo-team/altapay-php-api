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

use Altapay\Request\Address;
use Altapay\Request\Customer;

class CustomerTest extends \PHPUnit_Framework_TestCase
{

    public function test_customer()
    {
        $billingAddress = new Address();
        $shippingAddress = new Address();

        $customer = new Customer($billingAddress);
        $customer->setShipping($shippingAddress);
        $customer->setOrganisationNumber(123);
        $customer->setPersonalIdentifyNumber('20304050');
        $customer->setGender(true);
        $serialized = $customer->serialize();

        $this->assertArrayHasKey('organisationNumber', $serialized);
        $this->assertArrayHasKey('personalIdentifyNumber', $serialized);
        $this->assertArrayHasKey('gender', $serialized);

        $this->assertEquals(123, $serialized['organisationNumber']);
        $this->assertEquals('20304050', $serialized['personalIdentifyNumber']);
        $this->assertEquals('F', $serialized['gender']);

        $customer->setGender(false);
        $serialized = $customer->serialize();
        $this->assertEquals('M', $serialized['gender']);

    }

}
