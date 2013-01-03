<?php

namespace Bing\Search;

use Bing\HttpClient\Client;

/**
 * Factory Class
 * @author drahot
 */
class Factory
{
	
    /**
     * Bing API URI Constant
     */ 
    const BING_API_BASE_URL  = 'https://api.datamarket.azure.com/Bing/Search';
	
	/**
	 * AccountKey
	 * @var string
	 */
	private $accountKey;

	/**
	 * Constructor
	 * @param string $accountKey 
	 * @return void
	 */
	public function __construct($accountKey)
	{
		$this->accountKey = $accountKey;		
	}

	/**
	 * Create Image Search Instance
	 * @param string $query 
	 * @param int $page 
	 * @param int $limit 
	 * @return Bing\Search\ImageSearch
	 */
	public function createImageSearch($query, $page = 1, $limit = 50)
	{
		$client = new Client(self::BING_API_BASE_URL, $this->accountKey);
		$search = new ImageSearch($client, $query, $page, $limit);
		return $search;
	}
	
	/**
	 * Create Web Search Instance
	 * @param string $query 
	 * @param int $page 
	 * @param int $limit 
	 * @return Bing\Search\WebSearch
	 */
	public function createWebSearch($query, $page = 1, $limit = 50)
	{
		$client = new Client(self::BING_API_BASE_URL, $this->accountKey);
		$search = new WebSearch($client, $query, $page, $limit);
		return $search;
	}

	/**
	 * Get AccountKey
	 * @return string
	 */
	public function getAccountKey()
	{
		return $this->accountKey;		
	}

}