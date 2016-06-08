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

namespace Altapay\ApiTest\Functional;

use Altapay\Authentication;
use Altapay\Request\Card;
use Altapay\ApiTest\AbstractTest;

abstract class AbstractFunctionalTest extends AbstractTest
{

    const VALID_VISA_CARD_NUMBER = '4140000000001466';

    protected function setUp()
    {
        if (! file_exists(__DIR__ . '/../../.env.php')) {
            $this->markTestSkipped(
                'Can not test functional because .env.php file does not exists'
            );
        }
    }

    /**
     * @return Authentication
     */
    protected function getAuth()
    {
        return new Authentication($_ENV['USERNAME'], $_ENV['PASSWORD'], $this->getBaseUrl());
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        return $_ENV['BASEURL'];
    }

    /**
     * @return string
     */
    protected function getTerminal()
    {
        return $_ENV['TERMINAL'];
    }

    /**
     * @return Card
     */
    protected function getValidCard()
    {
        return $this->generateCard(self::VALID_VISA_CARD_NUMBER);
    }

    protected function generateCard($number)
    {
        return new Card(
            $number,
            (new \DateTime())->format('m'),
            (new \DateTime())->add(new \DateInterval('P1Y'))->format('Y'),
            123
        );
    }

}
