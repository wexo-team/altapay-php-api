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

use Altapay\Api\Response\Embeds\CustomerInfo;
use Altapay\Api\Response\Embeds\PaymentInfo;
use Altapay\Api\Response\Embeds\PaymentNatureService;
use Altapay\Api\Response\Embeds\ReconciliationIdentifier;

class Transaction extends AbstractResponse
{

    protected $childs = [
        'PaymentNatureService' => [
            'class' => PaymentNatureService::class,
            'array' => false
        ],
        'PaymentInfos' => [
            'class' => PaymentInfo::class,
            'array' => 'PaymentInfo',
        ],
        'CustomerInfo' => [
            'class' => CustomerInfo::class,
            'array' => false
        ],
        'ReconciliationIdentifiers' => [
            'class' => ReconciliationIdentifier::class,
            'array' => 'ReconciliationIdentifier'
        ]
    ];

    public $TransactionId;
    public $PaymentId;
    public $CardStatus;
    public $CreditCardToken;
    public $CreditCardMaskedPan;
    public $ThreeDSecureResult;
    public $LiableForChargeback;
    public $BlacklistToken;
    public $ShopOrderId;
    public $Shop;
    public $Terminal;
    public $TransactionStatus;
    public $ReasonCode;
    public $MerchantCurrency;
    public $MerchantCurrencyAlpha;
    public $CardHolderCurrency;
    public $CardHolderCurrencyAlpha;

    /**
     * @var float
     */
    public $ReservedAmount;

    /**
     * @var float
     */
    public $CapturedAmount;

    /**
     * @var float
     */
    public $RefundedAmount;

    /**
     * @var float
     */
    public $RecurringDefaultAmount;

    /**
     * @var \DateTime
     */
    public $CreatedDate;

    /**
     * @var \DateTime
     */
    public $UpdatedDate;
    public $PaymentNature;
    public $PaymentNatureService;

    /**
     * @var float
     */
    public $FraudRiskScore;
    public $FraudExplanation;
    public $PaymentInfos;
    public $CustomerInfo;
    public $ReconciliationIdentifiers;

    /**
     * @param string $CreatedDate
     * @return Transaction
     */
    protected function setCreatedDate($CreatedDate)
    {
        $this->CreatedDate = new \DateTime($CreatedDate);
        return $this;
    }

    /**
     * @param string $UpdatedDate
     * @return Transaction
     */
    protected function setUpdatedDate($UpdatedDate)
    {
        $this->UpdatedDate = new \DateTime($UpdatedDate);
        return $this;
    }

    /**
     * @param float $ReservedAmount
     * @return Transaction
     */
    public function setReservedAmount($ReservedAmount)
    {
        $this->ReservedAmount = (float) $ReservedAmount;
        return $this;
    }

    /**
     * @param float $CapturedAmount
     * @return Transaction
     */
    public function setCapturedAmount($CapturedAmount)
    {
        $this->CapturedAmount = (float) $CapturedAmount;
        return $this;
    }

    /**
     * @param float $RefundedAmount
     * @return Transaction
     */
    public function setRefundedAmount($RefundedAmount)
    {
        $this->RefundedAmount = (float) $RefundedAmount;
        return $this;
    }

    /**
     * @param float $RecurringDefaultAmount
     * @return Transaction
     */
    public function setRecurringDefaultAmount($RecurringDefaultAmount)
    {
        $this->RecurringDefaultAmount = (float) $RecurringDefaultAmount;
        return $this;
    }

    /**
     * @param float $FraudRiskScore
     * @return Transaction
     */
    public function setFraudRiskScore($FraudRiskScore)
    {
        $this->FraudRiskScore = (float) $FraudRiskScore;
        return $this;
    }
}
