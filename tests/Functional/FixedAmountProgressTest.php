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

use Altapay\Exceptions\ResponseHeaderException;
use Altapay\Exceptions\ResponseMessageException;
use Altapay\Api\Payments\CaptureReservation;
use Altapay\Api\Payments\RefundCapturedReservation;
use Altapay\Api\Payments\ReservationOfFixedAmount;
use Altapay\Response\CaptureReservationResponse;
use Altapay\Response\RefundResponse;
use Altapay\Response\ReservationOfFixedAmountResponse;

class FixedAmountProgressTest extends AbstractFunctionalTest
{

    public function test_create_fixed_amount_fails()
    {
        $this->expectException(ResponseHeaderException::class);

        $api = new ReservationOfFixedAmount($this->getAuth());
        $api
            ->setTerminal($this->getTerminal())
            ->setShopOrderId(time())
            ->setAmount($this->getFaker()->randomFloat(2, 1, 50))
            ->setCurrency('DKK')
        ;
        $api->call();
    }

    public function test_can_reserve_capture_refund()
    {
        // Reserve
        $api = new ReservationOfFixedAmount($this->getAuth());
        $api
            ->setTerminal($this->getTerminal())
            ->setShopOrderId(time())
            ->setAmount($this->getFaker()->randomFloat(2, 1, 50))
            ->setCurrency('DKK')
            ->setCard($this->getValidCard())
        ;
        /** @var ReservationOfFixedAmountResponse $response */
        $response = $api->call();
        $this->assertInstanceOf(ReservationOfFixedAmountResponse::class, $response);

        // Capture
        $api = new CaptureReservation($this->getAuth());
        $api
            ->setTransaction($response->Transactions[0])
        ;
        /** @var CaptureReservationResponse $response */
        $response = $api->call();
        $this->assertInstanceOf(CaptureReservationResponse::class, $response);

        // Refund
        $api = new RefundCapturedReservation($this->getAuth());
        $api
            ->setTransaction($response->Transactions[0])
        ;
        /** @var RefundResponse $response */
        $response = $api->call();
        $this->assertInstanceOf(RefundResponse::class, $response);

    }


//    public function test_preauth_3dsecure_attempt_fails()
//    {
//        $api = new ReservationOfFixedAmount($this->getAuth());
//        $api
//            ->setTerminal($this->getTerminal())
//            ->setShopOrderId(time())
//            ->setAmount(4.66)
//            ->setCurrency('DKK')
//            ->setCard($this->generateCard(4140000000000466))
//        ;
//        $api->call();
//    }
//
//    public function test_preauth_3dsecure_attempt_error()
//    {
//        $api = new ReservationOfFixedAmount($this->getAuth());
//        $api
//            ->setTerminal($this->getTerminal())
//            ->setShopOrderId(time())
//            ->setAmount(4.67)
//            ->setCurrency('DKK')
//            ->setCard($this->generateCard(4180000000000467))
//        ;
//        $api->call();
//    }

    public function test_preauth_declined_by_bank()
    {
        $this->setExpectedException(ResponseMessageException::class, 'TestAcquirer[pan=0566 or amount=5660]');

        $api = new ReservationOfFixedAmount($this->getAuth());
        $api
            ->setTerminal($this->getTerminal())
            ->setShopOrderId(time())
            ->setAmount(5.66)
            ->setCurrency('DKK')
            ->setCard($this->generateCard(4180000000000566))
        ;
        $api->call();
    }

    public function test_preauth_bank_error()
    {
        $this->setExpectedException(ResponseMessageException::class, 'TestAcquirer[pan=0567 or amount=5670][54321]');

        $api = new ReservationOfFixedAmount($this->getAuth());
        $api
            ->setTerminal($this->getTerminal())
            ->setShopOrderId(time())
            ->setAmount(5.67)
            ->setCurrency('DKK')
            ->setCard($this->generateCard(4130000000000567))
        ;
        $api->call();
    }

    public function test_preauth_cvv_check_error()
    {
        $this->markTestSkipped('Not working with API');

        $this->setExpectedException(ResponseMessageException::class, 'TestAcquirer[pan=0572 or amount=5720]');

        $api = new ReservationOfFixedAmount($this->getAuth());
        $api
            ->setTerminal($this->getTerminal())
            ->setShopOrderId(time())
            ->setAmount(5.72)
            ->setCurrency('DKK')
            ->setCard($this->generateCard(4190000000000572))
        ;
        $api->call();
    }

    public function test_preauth_card_failed()
    {
        $this->markTestSkipped('Not working with API');

        $api = new ReservationOfFixedAmount($this->getAuth());
        $api
            ->setTerminal($this->getTerminal())
            ->setShopOrderId(time())
            ->setAmount(16.66)
            ->setCurrency('DKK')
            ->setCard($this->generateCard(4120000000001666))
        ;
        $api->call();
    }

    public function test_preauth_card_error()
    {
        $this->markTestSkipped('Not working with API');

        $api = new ReservationOfFixedAmount($this->getAuth());
        $api
            ->setTerminal($this->getTerminal())
            ->setShopOrderId(time())
            ->setAmount(16.67)
            ->setCurrency('DKK')
            ->setCard($this->generateCard(4160000000001667))
        ;
        $api->call();
    }

    public function test_fraud_check_challenge()
    {
        $api = new ReservationOfFixedAmount($this->getAuth());
        $api
            ->setTerminal($this->getTerminal())
            ->setShopOrderId(time())
            ->setAmount(102)
            ->setCurrency('DKK')
            ->setCard($this->generateCard(4170000000000121))
            ->setFraudService('maxmind')
        ;
        /** @var ReservationOfFixedAmountResponse $response */
        $response = $api->call();
        $this->assertInstanceOf(ReservationOfFixedAmountResponse::class, $response);
        $this->assertEquals('Deny', $response->Transactions[0]->FraudRecommendation);
    }

    public function test_fraud_check_deny()
    {
        $api = new ReservationOfFixedAmount($this->getAuth());
        $api
            ->setTerminal($this->getTerminal())
            ->setShopOrderId(time())
            ->setAmount(110)
            ->setCurrency('DKK')
            ->setCard($this->generateCard(4170000000000105))
            ->setFraudService('maxmind')
        ;
        /** @var ReservationOfFixedAmountResponse $response */
        $response = $api->call();
        $this->assertInstanceOf(ReservationOfFixedAmountResponse::class, $response);
        $this->assertEquals('Deny', $response->Transactions[0]->FraudRecommendation);
    }

}
