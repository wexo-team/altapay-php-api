<?php

namespace Altapay\ApiTest\Functional;

use Altapay\Api\Test\TestConnection;

class TestConnectionTest extends AbstractFunctionalTest
{

    public function test_connection()
    {
        $response = (new TestConnection())
            ->call()
        ;

        $this->assertTrue($response);
    }

}
