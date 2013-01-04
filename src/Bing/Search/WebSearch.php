<?php

namespace Bing\Search;

/**
 * Bing Web Search Class
 * @author drahot
 */
class WebSearch extends Search
{

    private $webSearchOptions;
    private $webFileType;

    /**
     * Get SearchType
     * @return string
     */
    public function getSearchType()
    {
        return 'Web';
    }

    /**
     * Get WebSearchOptions
     * @return array
     */
    public function getWebSearchOptions()
    {
        return $this->webSearchOptions;
    }

    /**
     * Set WebSearchOptions
     * @param array $webSearchOptions 
     * @return Bing\Search\WebSearch
     */
    public function setWebSearchOptions(array $webSearchOptions)
    {
        $checkOptions = array('DisableHostCollapsing', 'DisableQueryAlterations');
        foreach ($webSearchOptions as $option) {
            if (in_array($option, $checkOptions) === false) {
                throw new \InvalidArgumentException("Options is not Invalid!");
            }
        }
        $this->webSearchOptions = $webSearchOptions;
    }

    /**
     * Get WebFileType
     * @return string
     */
    public function getWebFileType()
    {
        return $this->getWebFileType;
    }

    /**
     * Set WebFileType
     * @param string $webFileType 
     * @return type
     */
    public function setWebFileType($webFileType)
    {
        $this->webFileType = $webFileType;
        return $this;
    }

    /**
     * Add Additional Parameters
     * @param array $params 
     * @return array
     */
    protected function addAddtionalParameters(array $params)
    {
        if (count($this->webSearchOptions) > 0) {
            $params['WebSearchOptions'] = "'".implode('+', $this->webSearchOptions)."'";
        }
        if (!empty($this->webFileType)) {
            $params['WebFileType'] = "'".$this->webFileType."'";
        }
        return $params;
    }

}
