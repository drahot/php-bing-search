<?php

namespace Bing\HttpClient;

use Guzzle\Http\Client as GuzzleClient;

/**
 * Http Client
 * @author drahot
 */
class Client implements ClientInterface
{

    protected $baseUri;
    protected $accountKey;  
    protected $client;
    protected $response;

    /**
     * Constructor
     * @param string $baseUri 
     * @param string $accountKey 
     * @return void
     */
    public function __construct($baseUri, $accountKey = '')
    {
        if (empty($baseUri)) {
            throw new \InvalidArgumentException("Invalid BaseUri");
        }
        $this->baseUri = $baseUri;
        $this->accountKey = $accountKey;
        $this->client = new GuzzleClient($this->baseUri);
    }

    /**
     * Execute Http Request
     * @param string $uri 
     * @param array $params 
     * @return void
     */
    public function request($uri, array $params = array())
    {
        if (empty($uri)) {
            throw new \InvalidArgumentException("Invalid uri");
        }
        $request = $this->client->get($uri);
        if (!empty($this->accountKey)) {
            $request->setAuth($this->accountKey, $this->accountKey);
        }
        $query = $request->getQuery();
        foreach ($params as $name => $value) {
            $query->set($name, $value);
        }
        $this->response = $request->send();
    }

    /**
     * Get BaseUri
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * Get AccountKey
     * @return string
     */
    public function getAccountKey()
    {
        return $this->accountKey;
    }

    /**
     * Description
     * @param string $accoutKey 
     * @return type
     */
    public function setAccountKey($accountKey)
    {
        $this->accountKey = $accountKey;
    }

    /**
     * Get HttpStatus Code
     * @return string
     */
    public function getStatusCode()
    {
        $this->validResponseCallCondition();
        return $this->response->getStatusCode();
    }

    /**
     * Get RawHeaders
     * @return string
     */
    public function getRawHeaders()
    {
        $this->validResponseCallCondition();
        return $this->response->getRawHeaders();
    }

    /**
     * Get Body
     * @return string
     */
    public function getBody()
    {
        $this->validResponseCallCondition();
        return $this->response->getBody();
    }

    /**
     * Get Response Object
     * @return Guzzle\Http\Message\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Check Valid Response Call Exception
     * @return void
     * @throws \BadMethodCallException
     */
    private function validResponseCallCondition()
    {
        if (is_null($this->response)) {
            throw new \BadMethodCallException("Invalid Method Call!");
        }
    }

}