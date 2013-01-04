<?php

namespace Bing\Search;

/**
 * Bing Image Search Class
 * @author drahot
 */
class ImageSearch extends Search
{
    
    /**
     * Image Filters
     * @var string
     */ 
    private $imageFilters;

    /**
     * Get SearchType
     * @return string
     */
    public function getSearchType()
    {
        return 'Image';
    }

    /**
     * Get Image Filters
     * @return array
     */
    public function getImageFilters()
    {
        return $this->imageFilters;
    }

    /**
     * Set Image Filters
     * @param array $imageFilters 
     * @return ImageSearch
     */
    public function setImageFilters(array $imageFilters)
    {
        $checkOptions = array('Size', 'Aspect');
        foreach (array_keys($imageFilters) as $option) {
            if (in_array($option, $checkOptions) === false) {
                throw new \InvalidArgumentException("Options is not Invalid!");
            }
        }
        $this->imageFilters = $imageFilters;
        return $this;
    }

    /**
     * Add Additional Parameters
     * @param array $params 
     * @return array
     */
    protected function addAddtionalParameters(array $params)
    {
        if (count($this->imageFilters) > 0) {
            $imageFilters = '';
            array_walk($this->imageFilters, function ($value, $key) use (&$imageFilters) {
                if (strlen($imageFilters)) {
                    $imageFilters .= '+';
                }
                $imageFilters .= $key.':'.$value;
            });
            $params['ImageFilters'] = "'".$imageFilters."'";
        }
        return $params;
    }

}
