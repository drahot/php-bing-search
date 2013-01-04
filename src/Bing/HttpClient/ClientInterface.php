<?php

namespace Bing\HttpClient;

/**
 * HttpClient Interface
 * @author drahot
 */
interface ClientInterface
{
    
    public function request($uri, array $params = array());
    public function getBody();
    public function getStatusCode();
    public function getRawHeaders();

}
