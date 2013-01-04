<?php

namespace Bing\Test\HttpClient;

use Bing\HttpClient\Client;
use Bing\Tests\TestCase;

class ClientTest extends TestCase
{

    public function testConstructor()
    {
        try {
            $client = new Client('');
            $this->fail('Not raise InvalidArgumentException!');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }
        $client = new Client(self::BING_API_BASE_URL);
        $this->assertInstanceOf('Bing\\HttpClient\\ClientInterface', $client);
    }

    public function testRequest()
    {
        $client = new Client(self::BING_API_BASE_URL);
        try {
            $client->request('');
            $this->fail('Not raise InvalidArgumentException!');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }
        $params = array(
            '$format'   => 'json',
            'Query'     => "'oppai'",
            '$skip'     => 0,
        );
        $client->setAccountKey(self::ACCTKEY);
        $client->request('Image', $params);
        $this->assertEquals('200', $client->getStatusCode());
        $this->assertNotEmpty($client->getRawHeaders());
        $this->assertNotEmpty(json_decode($client->getBody()));
        $params['$format'] = 'atom';
        $client->request('Image', $params);
        $this->assertEquals('200', $client->getStatusCode());
        try {
            new \SimpleXMLElement($client->getBody());      
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

}
