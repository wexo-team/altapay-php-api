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

namespace Altapay\Api\Response;

/**
 * Class Refund
 */
class Refund extends AbstractResponse
{

    /**
     * Childs
     *
     * @var array
     */
    protected $childs = [
        'Transactions' => [
            'class' => Transaction::class,
            'array' => 'Transaction'
        ],
    ];

    /**
     * The amount refunded
     *
     * @var float
     */
    public $RefundAmount;

    /**
     * The currency used
     *
     * @var string
     */
    public $RefundCurrency;

    /**
     * The result
     *
     * @var string
     */
    public $Result;

    /**
     * The result
     *
     * @var string
     * @deprecated
     */
    public $RefundResult;

    /**
     * Transactions
     *
     * @var Transaction[]
     */
    public $Transactions;

    /**
     * Sets the refunded amount
     *
     * @param float $RefundAmount
     * @return Refund
     */
    public function setRefundAmount($RefundAmount)
    {
        $this->RefundAmount = (float) $RefundAmount;
        return $this;
    }
}
