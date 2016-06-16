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

use Altapay\Api\Ecommerce\Callback;
use Altapay\Response\CallbackResponse;

class CallbackTest extends \PHPUnit_Framework_TestCase
{

    private $data;

    public function setUp()
    {
        $this->data = [
            'shop_orderid' => '000000022',
            'currency' => '840',
            'type' => 'payment',
            'embedded_window' => '0',
            'amount' => '0',
            'transaction_id' => '10975531',
            'payment_id' => 'd28df6b4-122d-49e2-add0-19c8271260b0',
            'nature' => 'CreditCard',
            'require_capture' => 'false',
            'payment_status' => 'created',
            'masked_credit_card' => '457168*********0000',
            'blacklist_token' => '1ba7bdb2752394286d511de4e9cc18d9f75f2946',
            'credit_card_token' => '3fd3d781cc2faf1e8bb6d50e5ae2220554acbb14',
            'status' => 'incomplete',
            'xml' => <<<XML
<?xml version="1.0"?>
<APIResponse version="20151228">
    <Header>
        <Date>2016-06-16T05:44:53+02:00</Date>
        <Path>API/reservationOfFixedAmount</Path>
        <ErrorCode>0</ErrorCode>
        <ErrorMessage/>
    </Header>
    <Body>
        <Result>Failed</Result>
        <MerchantErrorMessage/>
        <CardHolderErrorMessage>Order verification failed</CardHolderErrorMessage>
        <CardHolderMessageMustBeShown>false</CardHolderMessageMustBeShown>
        <Transactions>
            <Transaction>
                <TransactionId>11327661</TransactionId>
                <PaymentId>cf0dbd02-ec44-4692-bc37-b7a061711751</PaymentId>
                <AuthType>payment</AuthType>
                <CardStatus>InvalidLuhn</CardStatus>
                <CreditCardExpiry>
                    <Year>2021</Year>
                    <Month>06</Month>
                </CreditCardExpiry>
                <CreditCardToken>12ad9f352531f19ef7a2596f5726206e80541dbf</CreditCardToken>
                <CreditCardMaskedPan>457198********2020</CreditCardMaskedPan>
                <ThreeDSecureResult>Not_Applicable</ThreeDSecureResult>
                <LiableForChargeback>Merchant</LiableForChargeback>
                <CVVCheckResult>Not_Applicable</CVVCheckResult>
                <BlacklistToken>e9219d358bf95f6f3d30bc04c23d678c316b88bd</BlacklistToken>
                <ShopOrderId>000000028</ShopOrderId>
                <Shop>Scandesigns</Shop>
                <Terminal>Scandesigns Test Terminal</Terminal>
                <TransactionStatus>order_verify_declined</TransactionStatus>
                <ReasonCode>NONE</ReasonCode>
                <MerchantCurrency>840</MerchantCurrency>
                <MerchantCurrencyAlpha>USD</MerchantCurrencyAlpha>
                <CardHolderCurrency>840</CardHolderCurrency>
                <CardHolderCurrencyAlpha>USD</CardHolderCurrencyAlpha>
                <ReservedAmount>0.00</ReservedAmount>
                <CapturedAmount>0.00</CapturedAmount>
                <RefundedAmount>0.00</RefundedAmount>
                <CreditedAmount>0.00</CreditedAmount>
                <RecurringDefaultAmount>0.00</RecurringDefaultAmount>
                <SurchargeAmount>0.00</SurchargeAmount>
                <CreatedDate>2016-06-16 05:44:51</CreatedDate>
                <UpdatedDate>2016-06-16 05:44:52</UpdatedDate>
                <PaymentNature>CreditCard</PaymentNature>
                <PaymentSchemeName>Visa</PaymentSchemeName>
                <PaymentNatureService name="">
                    <SupportsRefunds>false</SupportsRefunds>
                    <SupportsRelease>false</SupportsRelease>
                    <SupportsMultipleCaptures>false</SupportsMultipleCaptures>
                    <SupportsMultipleRefunds>false</SupportsMultipleRefunds>
                </PaymentNatureService>
                <ChargebackEvents/>
                <PaymentInfos/>
                <CustomerInfo>
                    <UserAgent>Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36</UserAgent>
                    <IpAddress>83.221.155.149</IpAddress>
                    <Email/>
                    <Username/>
                    <CustomerPhone/>
                    <OrganisationNumber/>
                    <CountryOfOrigin>
                        <Country>DK</Country>
                        <Source>BillingAddress</Source>
                    </CountryOfOrigin>
                    <BillingAddress>
                        <Firstname>
                            <![CDATA[Foo]]>
                        </Firstname>
                        <Lastname>
                            <![CDATA[Bar]]>
                        </Lastname>
                        <Address>
                            <![CDATA[somewhere]]>
                        </Address>
                        <City>
                            <![CDATA[city]]>
                        </City>
                        <Region>
                            <![CDATA[0]]>
                        </Region>
                        <Country>
                            <![CDATA[DK]]>
                        </Country>
                        <PostalCode>
                            <![CDATA[1000]]>
                        </PostalCode>
                    </BillingAddress>
                    <ShippingAddress>
                        <Firstname>
                            <![CDATA[Foo]]>
                        </Firstname>
                        <Lastname>
                            <![CDATA[Bar]]>
                        </Lastname>
                        <Address>
                            <![CDATA[somewhere]]>
                        </Address>
                        <City>
                            <![CDATA[city]]>
                        </City>
                        <Region>
                            <![CDATA[0]]>
                        </Region>
                        <Country>
                            <![CDATA[DK]]>
                        </Country>
                        <PostalCode>
                            <![CDATA[1000]]>
                        </PostalCode>
                    </ShippingAddress>
                </CustomerInfo>
                <ReconciliationIdentifiers/>
            </Transaction>
        </Transactions>
    </Body>
</APIResponse>
XML

        ];
    }

    public function test_can_handle_callback()
    {
        $call = new Callback($this->data);
        $response = $call->call();
        $this->assertInstanceOf(CallbackResponse::class, $response);
        $this->assertEquals('d28df6b4-122d-49e2-add0-19c8271260b0', $response->paymentId);
        $this->assertEquals('incomplete', $response->status);
        $this->assertCount(1, $response->Transactions);
        $this->assertEquals('11327661', $response->Transactions[0]->TransactionId);
        $this->assertEquals('Failed', $response->Result);
    }

}
