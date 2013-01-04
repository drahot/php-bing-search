<?php

namespace Bing\Search;

use \Iterator;
    
/**
 * SearchIterator
 * 
 * @author drahot
 */
class SearchIterator implements Iterator
{

    /**
     * Search Object
     * @var Search
     */
    private $searchObject;

    /**
     * SearchResult Object
     * @var SearchResult
     */
    private $searchResult;
    
    /**
     * Iterator
     * @var int
     */    
    private $index; 

    /**
     * Constructor
     * @param Bing\Search\Search $searchObject 
     * @return void
     */
    public function __construct(Search $searchObject)
    {
        $this->searchObject = $searchObject;
    }

    /**
     * rewind
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * valid
     * @return boolean
     */
    public function valid()
    {
        $this->searchResult = 
            $this->searchObject
                 ->setPage($this->index + 1)
                 ->execute()
                 ->getResult();
        return (count($this->searchResult) > 0);
    }

    /**
     * next
     * @return void
     */
    public function next() 
    {
        ++$this->index;
    }

    /**
     * Get Current SearchResult
     * @return Bing\Search\SearchResult
     */
    public function current() 
    {
        return $this->searchResult;
    }

    /**
     * Get Key
     * @return mixed
     */
    public function key() 
    {
        return $this->index;
    }
    
}