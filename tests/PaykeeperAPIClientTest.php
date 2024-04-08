<?php

namespace Tests;

use BadMethodCallException;
use GuzzleHttp\Client;
use JustCommunication\PaykeeperSDK\API\Info\SystemsListRequest;
use JustCommunication\PaykeeperSDK\PaykeeperAPIClient;
use PHPUnit\Framework\TestCase;

class PaykeeperAPIClientTest extends TestCase
{
    public function testCreateWithDefaultOptions()
    {
        $client = new PaykeeperAPIClient('', '', '', '');

        $this->assertEquals(10, $client->getHttpClient()->getConfig('timeout'));
        $this->assertEquals(4, $client->getHttpClient()->getConfig('connect_timeout'));
    }

    public function testCreateWithCustomHttpClientOptions()
    {
        $client = new PaykeeperAPIClient('', '', '', '', [
            'timeout' => 11,
            'connect_timeout' => 3
        ]);

        $this->assertEquals(11, $client->getHttpClient()->getConfig('timeout'));
        $this->assertEquals(3, $client->getHttpClient()->getConfig('connect_timeout'));
    }

    public function testCreateWithCustomHttpClient()
    {
        $httpClient = new Client([
            'timeout' => 12,
            'connect_timeout' => 2
        ]);

        $client = new PaykeeperAPIClient('', '', '', '', $httpClient);

        $this->assertEquals(12, $client->getHttpClient()->getConfig('timeout'));
        $this->assertEquals(2, $client->getHttpClient()->getConfig('connect_timeout'));
    }

    public function testCallUndefinedMethod()
    {
        $client = new PaykeeperAPIClient('', '', '', '');

        $this->expectException(BadMethodCallException::class);

        $client->callSomeUndefinedRequest(new SystemsListRequest());
    }
}
