<?php

namespace Bing\Tests\Search;

use Bing\HttpClient\Client;
use Bing\Search\ImageSearch;
use Bing\Search\ImageResult;

use Bing\Tests\TestCase;
use Bing\Tests\Search\ImageClientMock;

class ImageSearchTest extends TestCase
{

    public function testSetAllParameters()
    {
        $params = array(
            'query'         => 'おっぱい',
            'limit'         => 100,
            'page'          => 2,
            'market'        => 'ja-JP',
            'latitude'      => 100,
            'longitude'     => 102,
            'adult'         => 'Moderate',
            'imageFilters'  => array('Size' => 'Small', 'Aspect' => 'Square'),
            'options'       => array('DisableLocationDetection', 'EnableHighlighting'),
        );
        $client = new Client(self::BING_API_BASE_URL);
        $search = new ImageSearch($client, 'oppai');
        $search->setAllParameters($params);
        $this->assertEquals("おっぱい", $search->getQuery());
        $this->assertEquals(100, $search->getLimit());
        $this->assertEquals(2, $search->getPage());
        $this->assertEquals("ja-JP", $search->getMarket());
        $this->assertEquals(100, $search->getLatitude());
        $this->assertEquals(102, $search->getLongitude());
        $this->assertEquals('Moderate', $search->getAdult());
        $this->assertEquals(array('Size' => 'Small', 'Aspect' => 'Square'), $search->getImageFilters());
        $this->assertEquals(array('DisableLocationDetection', 'EnableHighlighting'), $search->getOptions());
    }

    public function testGetAllParameters()
    {
        $params = array(
            'query'         => 'おっぱい',
            'limit'         => 100,
            'page'          => 2,
            'market'        => 'ja-JP',
            'latitude'      => 100,
            'longitude'     => 102,
            'adult'         => 'Moderate',
            'imageFilters'  => array('Size' => 'Small', 'Aspect' => 'Square'),
            'options'       => array('DisableLocationDetection', 'EnableHighlighting'),
        );
        $client = new Client(self::BING_API_BASE_URL);
        $search = new ImageSearch($client, 'oppai');
        $search->setAllParameters($params);
        $props = $search->getAllParameters();
        $this->assertEquals("おっぱい", $props["query"]);
        $this->assertEquals(100, $props["limit"]);
        $this->assertEquals(2, $props["page"]);
        $this->assertEquals("ja-JP", $props["market"]);
        $this->assertEquals(100, $props["latitude"]);
        $this->assertEquals(102, $props["longitude"]);
        $this->assertEquals("Moderate", $props["adult"]);
        $this->assertEquals(array('Size' => 'Small', 'Aspect' => 'Square'), $props["imageFilters"]);
        $this->assertEquals(array('DisableLocationDetection', 'EnableHighlighting'), $props["options"]);
    }

    public function testExecute()
    {
        $client = new ImageClientMock(self::BING_API_BASE_URL);
        $search = new ImageSearch($client, 'おっぱい');
        $result = $search->execute()->getResult();
        $this->assertInstanceOf('Bing\\Search\\SearchResult', $result);
        $this->assertEquals(50, count($result));

        $imageResult = $result[0];
        $this->assertInstanceOf('Bing\\Search\\ImageResult', $imageResult);
        $this->assertHead($imageResult);
        $imageResult = $result[49];
        $this->assertInstanceOf('Bing\\Search\\ImageResult', $imageResult);
        $this->assertTail($imageResult);

        foreach ($search as $key => $result) {
            $this->assertEquals(0, $key);
            $this->assertInstanceOf('Bing\\Search\\SearchResult', $result);
            foreach ($result as $index => $imageResult) {
                $this->assertEquals(0, $index);
                $this->assertInstanceOf('Bing\\Search\\ImageResult', $imageResult);
                $this->assertHead($imageResult);
                break;
            }
            break;
        }
    }

    private function assertHead(ImageResult $imageResult)
    {
        $this->assertEquals("aacadc1a-81e0-45c9-a57f-ec18f16b4eaa", $imageResult->getId());
        $this->assertEquals("綾瀬はるか「おっぱい」連呼 ...", $imageResult->getTitle());
        $this->assertEquals(
            "http://storage.kanshin.com/free/img_41/417560/k197430421.jpg", 
            $imageResult->getMediaUrl()
        );
        $this->assertEquals(
            "http://www.kanshin.com/keyword/1512046", 
            $imageResult->getSourceUrl()
        );
        $this->assertEquals(
            "www.kanshin.com/keyword/1512046", 
            $imageResult->getDisplayUrl()
        );
        $this->assertEquals("700", $imageResult->getWidth());
        $this->assertEquals("933", $imageResult->getHeight());
        $this->assertEquals("82104", $imageResult->getFileSize());
        $this->assertEquals("image/jpeg", $imageResult->getContentType());
        $thumbnail = $imageResult->getThumbnail();
        $this->assertEquals(
            "http://ts2.mm.bing.net/th?id=H.4618211703063605&pid=15.1", 
            $thumbnail->getMediaUrl()
        );
        $this->assertEquals("image/jpg", $thumbnail->getContentType());
        $this->assertEquals("225", $thumbnail->getWidth());
        $this->assertEquals("300", $thumbnail->getHeight());
        $this->assertEquals("8562", $thumbnail->getFileSize());
    }

    private function assertTail(ImageResult $imageResult)
    {
        $this->assertEquals("5dd6982a-3fcf-4054-a716-f4181ba18e68", $imageResult->getId());
        $this->assertEquals("おっぱいおっぱい", $imageResult->getTitle());
        $this->assertEquals("http://fiancetank.net/img/823.jpg", $imageResult->getMediaUrl());
        $this->assertEquals(
            "http://fiancetank.net/2009/08/012022.html", 
            $imageResult->getSourceUrl()
        );
        $this->assertEquals(
            "fiancetank.net/2009/08/012022.html", 
            $imageResult->getDisplayUrl()
        );
        $this->assertEquals("559", $imageResult->getWidth());
        $this->assertEquals("793", $imageResult->getHeight());
        $this->assertEquals("145451", $imageResult->getFileSize());
        $this->assertEquals("image/jpeg", $imageResult->getContentType());
        $thumbnail = $imageResult->getThumbnail();
        $this->assertEquals(
            "http://ts3.mm.bing.net/th?id=H.4772396685395582&pid=15.1", 
            $thumbnail->getMediaUrl()
        );
        $this->assertEquals("image/jpg", $thumbnail->getContentType());
        $this->assertEquals("211", $thumbnail->getWidth());
        $this->assertEquals("300", $thumbnail->getHeight());
        $this->assertEquals("8092", $thumbnail->getFileSize());
    }

}

