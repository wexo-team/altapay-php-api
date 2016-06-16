Altapay - PHP API &ndash; [![Build Status](https://travis-ci.org/lsv/altapay-php-api.svg?branch=master)](https://travis-ci.org/lsv/altapay-php-api) [![codecov](https://codecov.io/gh/lsv/altapay-php-api/branch/master/graph/badge.svg)](https://codecov.io/gh/lsv/altapay-php-api) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/ec01dcf9-d9dd-4227-b116-8c72617b79bc/mini.png)](https://insight.sensiolabs.com/projects/ec01dcf9-d9dd-4227-b116-8c72617b79bc)
=================

For accessing Altapay payment gateway through the API

### Install

`composer require lsv/altapay-php-api`

or add it to your `composer.json` file

```json
"require": {
    "lsv/altapay-php-api": "^1.0"
}
```

### Usage

For doing a [`capture`](docs/capture.md) the following can be used

```php
$auth = new \Altapay\Api\Authentication('username', 'password' , 'gateway.com');
$api = new \Altapay\Api\CaptureReservation($auth);
$api->setTransactionId('transaction id');
// Or you can use a transaction object you got from a previous API call
// $api->setTransaction($transactionObject);
try {
    $response = $api->call();
    // If everything went perfect, you will get a \Altapay\Api\Document\Capture in the response
} catch (\Altapay\Api\Exceptions\ClientException $e) {
    // If anything went wrong, you will get a exception where you can see the raw request and the raw response
}
```

More details in the [documentation](docs/index.md)

### License

The MIT License (MIT)

Copyright (c) 2016 Martin Aarhof martin.aarhof@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
