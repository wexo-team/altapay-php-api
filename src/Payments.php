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

use Altapay\Api\Response\Transaction;
use Altapay\Api\Serializer\ResponseSerializer;
use Altapay\Api\Traits\TransactionsTrait;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This action is used to find and check the status of a specific payment.
 * This action takes some optional parameters which will limit the number of results.
 *
 * This is NOT intended for finding multiple payments or creating reports.
 * A report of multiple payments should be done through CustomReport.
 *
 * Please note that the maximum number of transactions returned is 10.
 */
class Payments extends AbstractApi
{

    use TransactionsTrait;

    /**
     * The id of a specific payment.
     *
     * @param string $paymentId
     * @return $this
     */
    public function setPaymentId($paymentId)
    {
        $this->unresolvedOptions['payment_id'] = $paymentId;
        return $this;
    }

    /**
     * The shop orderid passed in by the shop when the payment was created.
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
     * The shop that you want to find a payment in
     * default is to show payments from all the shops enabled for the API-user.
     *
     * The shop filter is only supposed to be used in conjunction with one of the other filters (except terminal).
     * Use this if you want to ensure that the payment returned exists in this context.
     *
     * @param string $shop
     * @return $this
     */
    public function setShop($shop)
    {
        $this->unresolvedOptions['shop'] = $shop;
        return $this;
    }

    /**
     * The terminal you want to find a payment in.
     * The terminal filter is only supposed to be used in conjunction with one of the other filters (except shop).
     * Use this if you want to ensure that the payment returned exists in this context.
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
     * Configure options
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['payment_id', 'shop_orderid', 'shop', 'terminal']);
        $resolver->setAllowedTypes('payment_id', ['string', 'int']);
        $resolver->setAllowedTypes('shop_orderid', ['string', 'int']);
        $resolver->setAllowedTypes('shop', ['string', 'int']);
        $resolver->setAllowedTypes('terminal', 'string');

        $resolver->remove('transaction_id');
        $resolver->setDefined('transaction_id');
        $resolver->addAllowedTypes('transaction_id', ['string', 'int', Transaction::class]);
        $resolver->setNormalizer('transaction_id', function (Options $options, $value) {
            if ($value instanceof Transaction) {
                return $value->TransactionId;
            }
            return $value;
        });
    }

    /**
     * Handle response
     *
     * @param Request $request
     * @param Response $response
     * @return Transaction[]
     */
    protected function handleResponse(Request $request, Response $response)
    {
        $body = (string) $response->getBody();
        $xml = simplexml_load_string($body);
        return ResponseSerializer::serialize(Transaction::class, $xml->Body->Transactions, 'Transaction', $xml->Header);
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
        return sprintf('payments/%s', $query ? '?' . $query : '');
    }
}
