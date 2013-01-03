<?php

namespace Bing\Search;

use Bing\HttpClient\ClientInterface;
use \IteratorAggregate;

/**
 * Bing Search API Class
 * @author drahot
 */
abstract class Search implements IteratorAggregate
{

    private $page;
    private $limit;
    private $query;
    private $market;
    private $adult      = null;
    private $latitude   = null;
    private $longitude  = null;
    private $options    = null;
    private $client     = null;

    private $statusCode;
    private $header;
    private $body; 
    private $result;

    private $props      = null;

    /**
     * Constructor 
     * @param Bing\HttpClient\ClientInterface $httpClient
     * @param string query
     * @param int $page 
     * @param int $limit 
     * @return void
     */
    public function __construct(ClientInterface $httpClient, $query, $page = 1, $limit = 50)
    {
        $this->setHttpClient($httpClient)
             ->setQuery($query)
             ->setPage($page)
             ->setLimit($limit);
    }

    /**
     * Get Search Type
     * @return string
     */
    public abstract function getSearchType();

    /**
     * Search Query Execute
     * @return Bing\Search\Search
     * @throws \RuntimeException
     */
    public function execute() 
    {
        $offset = ($this->page - 1) * $this->limit;
        $params = array(        
            '$format'   => "json",
            'Query'     => "'". $this->query. "'",
            '$top'      => $this->limit,
            '$skip'     => $offset,
        );
        if (!empty($this->market)) {
            $params['Market'] = "'" . $this->market . "'";
        }
        if (!empty($this->adult)) {
            $params['Adult'] = "'" . $this->adult . "'";
        }
        if (!is_null($this->latitude)) {
            $params['Latitude'] = $this->latitude;
        }
        if (!is_null($this->longitude)) {
            $params['Longitude'] = $this->longitude;
        }
        if (count($this->options) > 0) {
            $params['Options'] = "'" . implode('+', $this->options) . "'";
        }
        $params = $this->addAddtionalParameters($params);
        list($this->statusCode, $this->header, $this->body) = $this->request($params);
        $this->result = new SearchResult($this->body, $this->getSearchType());
        return $this;
    }

    /**
     * Request To Url
     * @param array $params 
     * @return string
     * @throws \RuntimeException
     */
    protected function request(array $params)
    {
        $this->httpClient->request($this->getSearchType(), $params);
        $statusCode = $this->httpClient->getStatusCode();
        $header = $this->httpClient->getRawHeaders();
        $body = (string) $this->httpClient->getBody();
        return array($statusCode, $header, $body);
    }

    /**
     * Add addtional Parameters
     * @param array $params 
     * @return array
     */
    protected function addAddtionalParameters(array $params)
    {
        return $params;
    }

    /**
     * Get Http Status Code
     * @return string
     */
    public function getStatus()
    {
        return $this->statusCode;
    }

    /**
     * Get HTTP Header
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Get HTTP Body
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get Result
     * @return Bing\Search\SearchResult
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Get page
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set page
     * @param int $page 
     * @return Bing\Search\Search
     */
    public function setPage($page)
    {
        $this->page = $page;        
        return $this;
    }

    /**
     * Get limit
     * @return type
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set limit
     * @param int $limit 
     * @return Bing\Search\Search
     */
    public function setLimit($limit)
    {
        if (!is_int($limit)) {
            throw \InvalidArgumentException('Limit is not integer');
        }
        $this->limit = $limit;
        return $this;
    }
    
    /**
     * Get Query
     * @return string
     */     
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set Query
     * @param string $query 
     * @return Bing\Search\Search
     */
    public function setQuery($query)
    {
        if (empty($query)) {
            throw new \InvalidArgumentException('Query Parameters is empty');
        }
        $this->query = $query;
        return $this;
    }

    /**
     * Get Market
     * @return string
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * Set Market
     * @param string $market 
     * @return Bing\Search\Search
     */
    public function setMarket($market)
    {
        $this->market = $market;
        return $this;
    }

    /**
     * Get Adult
     * @return string
     */
    public function getAdult()
    {
        return $this->adult;
    }

    /**
     * Set Adult
     * @param string $adult 
     * @return Bing\Search\Search
     */
    public function setAdult($adult)
    {
        $this->adult = $adult;
        return $this;
    }

    /**
     * Get Longitude
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set Longitude
     * @param float $longitude 
     * @return Bing\Search\Search
     */
    public function setLongitude($longitude)
    {
        if (!is_float($longitude)) {
            throw new \InvalidArgumentException("Longitude is not float!");
        }
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * Get Latitude
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set Latitude
     * @param float $latitude 
     * @return Bing\Search\Search
     */
    public function setLatitude($latitude)
    {
        if (!is_float($latitude)) {
            throw new \InvalidArgumentException("Latitude is not float!");
        }
        $this->latitude = $latitude;
        return $this;
    }
    
    /**
     * Get Options 
     * @return array
     */ 
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set Options 
     * @param  array $options 
     * @return Bing\Search\Search
     */
    public function setOptions(array $options)
    {
        $checkOptions = array('DisableLocationDetection', 'EnableHighlighting');
        foreach ($options as $option) {
            if (in_array($option, $checkOptions) === false) {
                throw new \InvalidArgumentException("Options is not Invalid!");
            }
        }
        $this->options = $options;
        return $this;
    }

    /**
     * Get Http
     * @return Bing\HttpClient\ClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Set HttpClient
     * @param Bing\HttpClient\ClientInterface $httpClient 
     * @return Bing\HttpClient\ClientInterface
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * Set All Parameters
     * @param array $params 
     * @return Bing\Search\Search
     */
    public function setAllParameters(array $params)
    {
        $props = $this->getProperties();
        foreach ($params as $name => $value) {
            if (isset($props[$name])) {
                $prop = $props[$name];
                $prop->setAccessible(true);
                $prop->setValue($this, $value);
            }
        }
        return $this;
    }

    /**
     * Get All Property Parameters
     * @return array
     */
    public function getAllParameters()
    {
        $props = array();
        foreach ($this->getProperties() as $name => $prop) {
            $prop->setAccessible(true);
            $props[$name] = $prop->getValue($this);
        }
        return $props;
    }    

    /**
     * Get All Properties
     * @return array
     */
    private function getProperties()
    {
        if (is_null($this->props)) {
            $r = new \ReflectionClass($this);
            $propertyList = array_merge(
                $r->getProperties(), 
                $r->getParentClass()->getProperties()
            );
            $this->props = array();
            foreach ($propertyList as $prop) {
                $this->props[$prop->getName()] = $prop;
            }
        }
        return $this->props;
    }

    /**
     * Get Iterator
     * @return Bing\Search\SearchIterator
     */
    public function getIterator()
    {
        return new SearchIterator($this);
    }

}
