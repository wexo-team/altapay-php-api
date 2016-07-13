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

namespace Altapay\ApiTest;

use Altapay\Exceptions\ClassDoesNotExistsException;
use Altapay\Factory;

class FactoryTest extends AbstractTest
{

    public function dataProvider()
    {
        $refClass = new \ReflectionClass(Factory::class);
        $constants = $refClass->getConstants();
        $output = [];
        foreach ($constants as $class) {
            $output[] = [$class];
        }
        return $output;
    }

    /**
     * @dataProvider dataProvider
     * @param string $class
     */
    public function test_can_create($class)
    {
        $this->assertInstanceOf($class, Factory::create($class, $this->getAuth()));
    }

    public function test_does_not_exists()
    {
        $this->setExpectedException(ClassDoesNotExistsException::class);
        Factory::create('Foo\Bar', $this->getAuth());
    }

    public function test_does_not_exists_exception_catch()
    {
        try {
            Factory::create('Foo\Bar', $this->getAuth());
        } catch (ClassDoesNotExistsException $e) {
            $this->assertEquals('Foo\Bar', $e->getClass());
        }
    }

}
