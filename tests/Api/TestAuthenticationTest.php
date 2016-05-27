<?php
namespace Altapay\ApiTest\Api;

use Altapay\Api\TestAuthentication;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class TestAuthenticationTest extends AbstractApiTest
{

    public function test_auth_ok()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(200)
        ]));

        $api = (new TestAuthentication())
            ->setAuthentication($this->getAuth())
            ->setClient($client)
        ;

        $this->assertTrue($api->call());
        $this->assertEquals($this->getExceptedUri('login'), $api->getRawRequest()->getUri()->getPath());
    }

    public function test_auth_fail()
    {
        $client = $this->getClient($mock = new MockHandler([
            new Response(400)
        ]));

        $api = (new TestAuthentication())
            ->setAuthentication($this->getAuth())
            ->setClient($client)
        ;

        $this->assertFalse($api->call());
        $this->assertEquals($this->getExceptedUri('login'), $api->getRawRequest()->getUri()->getPath());
    }

}
