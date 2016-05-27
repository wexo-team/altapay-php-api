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

namespace Altapay\Api;

use Altapay\Api\Request\Card;
use Altapay\Api\Exceptions\CreditCardTokenAndCardUsedException;
use Altapay\Api\Types\PaymentSources;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This will create a Credit payment. The payment can be made with a credit card, or a credit card token and the CVV.
 */
class Credit extends AbstractApi
{

    /**
     * Set the terminal
     *
     * @param string $terminal
     * @return $this
     */
    public function setTerminal($terminal)
    {
        $this->unresolvedOptions['terminal'] = $terminal;
        return $this;
    }

    /**
     * The id of the order in your web shop,
     * this is what we will post back to you so you know which order a given payment is associated with.
     *
     * @param string $shopOrderId
     * @return $this
     */
    public function setShopOrderId($shopOrderId)
    {
        $this->unresolvedOptions['shop_orderid'] = $shopOrderId;
        return $this;
    }

    /**
     * The amount of the payment in english notation (ex. 89.95).
     *
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->unresolvedOptions['amount'] = $amount;
        return $this;
    }

    /**
     * The currency of the order. It is a 3 digit currency code. Our gateway comply with the ISO-4217 standard.
     *
     * @param int $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->unresolvedOptions['currency'] = $currency;
        return $this;
    }

    /**
     * This is a one-dimensional associative array.
     * This is where you put any value that you would like to bind to the payment.
     *
     * @param array $transactioninfo
     * @return $this
     */
    public function setTransactionInfo(array $transactioninfo)
    {
        $this->unresolvedOptions['transaction_info'] = $transactioninfo;
        return $this;
    }

    /**
     * The source of the payment. Default is "moto"
     *
     * @param string $paymentsource
     * @return $this
     */
    public function setPaymentSource($paymentsource)
    {
        $this->unresolvedOptions['payment_source'] = $paymentsource;
        return $this;
    }

    /**
     * Set the card used
     *
     * @param Card $card
     * @return $this
     */
    public function setCard(Card $card)
    {
        $this->unresolvedOptions['cardnum'] = $card->getCardNumber();
        $this->unresolvedOptions['emonth'] = $card->getExpiryMonth();
        $this->unresolvedOptions['eyear'] = $card->getExpiryYear();
        $this->unresolvedOptions['cvc'] = $card->getCvc();
        return $this;
    }

    /**
     * A credit card token previously received from an eCommerce payment or an other MO/TO payment.
     *
     * @param string $token
     * @return $this
     */
    public function setCreditCardToken($token)
    {
        $this->unresolvedOptions['credit_card_token'] = $token;
        return $this;
    }

    /**
     * Configure options
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['terminal', 'shop_orderid', 'amount', 'currency']);
        $resolver->setAllowedTypes('terminal', 'string');
        $resolver->setAllowedTypes('amount', 'float');
        $resolver->setAllowedTypes('currency', 'int');

        $resolver->setDefined([
            'transaction_info', 'payment_source', 'credit_card_token',
            'cardnum', 'emonth', 'eyear', 'cvc'
        ]);

        $resolver->setAllowedTypes('transaction_info', 'array');
        $resolver->setAllowedValues('payment_source', PaymentSources::getAllowed());
        $resolver->setDefault('payment_source', 'moto');

        $resolver->setNormalizer('credit_card_token', function (Options $options, $value) {
            if ($value && isset($options['cardnum'])) {
                throw new CreditCardTokenAndCardUsedException(
                    'Both "credit_card_token" and "card" can not be set at the same time, please use only one of them'
                );
            }

            return $value;
        });
    }

    /**
     * Handle response
     *
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    protected function handleResponse(Request $request, Response $response)
    {
        // TODO: Implement handleResponse() method.
    }

    /**
     * Url to api call
     *
     * @param array $options Resolved options
     * @return string
     */
    public function getUrl(array $options)
    {
        $query = $this->buildUrl($options);
        return sprintf('credit?%s', $query);
    }
}
