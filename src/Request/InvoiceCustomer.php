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

namespace Altapay\Request;

use Altapay\Api\Request\Address;

class InvoiceCustomer extends AbstractSerializer
{

    /**
     * Client gender
     *
     * @var string
     */
    private $gender;

    /**
     * Client IP address
     *
     * @var string
     */
    private $clientIp;

    /**
     * Client session ID
     *
     * @var string
     */
    private $clientSessionId;

    /**
     * The language setting of the customers browser.
     *
     * @var string
     */
    private $clientAcceptLanguage;

    /**
     * The customers browser identification
     *
     * @var string
     */
    private $clientUserAgent;

    /**
     * The customers IP address as forwarded by transparent proxy
     *
     * @var string
     */
    private $clientForwardedIp;

    /**
     * Billing address
     *
     * @var Address
     */
    private $billingAddress;

    /**
     * The customer's email address
     *
     * @var string
     */
    private $email;

    /**
     * InvoiceCustomer
     *
     * @param string $gender
     * @param string $clientIp
     * @param string $clientSessionId
     * @param string $clientAcceptLanguage
     * @param string $clientUserAgent
     * @param string $clientForwardedIp
     * @param Address $billingAddress
     * @param string $email
     */
    public function __construct(
        $gender,
        $clientIp,
        $clientSessionId,
        $clientAcceptLanguage,
        $clientUserAgent,
        $clientForwardedIp,
        Address $billingAddress,
        $email
    ) {
        $this->gender = $gender;
        $this->clientIp = $clientIp;
        $this->clientSessionId = $clientSessionId;
        $this->clientAcceptLanguage = $clientAcceptLanguage;
        $this->clientUserAgent = $clientUserAgent;
        $this->clientForwardedIp = $clientForwardedIp;
        $this->billingAddress = $billingAddress;
        $this->email = $email;
    }

    /**
     * Serialize a object
     *
     * @return array
     */
    public function serialize()
    {
        return [
            'gender' => $this->gender,
            'client_ip' => $this->clientIp,
            'client_session_id' => $this->clientSessionId,
            'client_accept_language' => $this->clientAcceptLanguage,
            'client_user_agent' => $this->clientUserAgent,
            'client_forwarded_ip' => $this->clientForwardedIp,
            'billing_postal' => $this->billingAddress->PostalCode,
            'billing_address' => $this->billingAddress->Address,
            'email' => $this->email
        ];
    }
}
