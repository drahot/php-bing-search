<?php

namespace Bing\Tests\Search;

use Bing\HttpClient\Client;
use Bing\Search\WebSearch;
use Bing\Search\WebResult;

use Bing\Tests\TestCase;
use Bing\Tests\Search\WebClientMock;

class WebSearchTest extends TestCase
{

    public function testExecute()
    {
        $client = new WebClientMock(self::BING_API_BASE_URL);
        $search = new WebSearch($client, 'おっぱい');
        $result = $search->execute()->getResult();
        $this->assertInstanceOf('Bing\\Search\\SearchResult', $result);
        $this->assertEquals(39, count($result));

        $webResult = $result[0];
        $this->assertInstanceOf('Bing\\Search\\WebResult', $webResult);
        $this->assertHead($webResult);
        $webResult = $result[38];
        $this->assertInstanceOf('Bing\\Search\\WebResult', $webResult);
        $this->assertTail($webResult);

        foreach ($search as $key => $result) {
            $this->assertEquals(0, $key);
            $this->assertInstanceOf('Bing\\Search\\SearchResult', $result);
            foreach ($result as $index => $webResult) {
                $this->assertEquals(0, $index);
                $this->assertInstanceOf('Bing\\Search\\WebResult', $webResult);
                $this->assertHead($webResult);
                break;
            }
            break;
        }
    }

    private function assertHead(WebResult $webResult)
    {
        $this->assertEquals("9294a30e-6337-4ad9-b889-f918c5d9c8de", $webResult->getId());
        $this->assertEquals("乳房 - Wikipedia", $webResult->getTitle());
        $this->assertEquals(
            "乳房 （にゅうぼう、ちぶさ）とは 哺乳類 の メス が持つ授乳器官。単に乳（ちち）あるいはお乳とも言い、俗に おっぱい とも呼ばれる。 乳房は多くの 哺乳類 の メス に存在する、皮膚の一部がなだらかに隆起しているようにみえる ...", 
            $webResult->getDescription()
        );
        $this->assertEquals("ja.wikipedia.org/wiki/おっぱい", $webResult->getDisplayUrl());
        $this->assertEquals("http://ja.wikipedia.org/wiki/%E3%81%8A%E3%81%A3%E3%81%B1%E3%81%84", $webResult->getUrl());
    }

    private function assertTail(WebResult $webResult)
    {
        $this->assertEquals("fdb956c4-867c-48bd-9904-6f1918398599", $webResult->getId());
        $this->assertEquals("おっぱいメロン", $webResult->getTitle());
        $this->assertEquals(
            "【サイズ】片乳の横幅約10cm 重量、両方合わせて約1kg強 【付属品】ミニローション 【匂い】弱い 【べとつき ... ボリュームも柔らかさも文句なし！パイズリ目当ての方はGETして下さい おっぱい担当の佐藤店員が ...", 
            $webResult->getDescription()
        );
        $this->assertEquals("www.hotpowers.jp/goods/1-270.html", $webResult->getDisplayUrl());
        $this->assertEquals("http://www.hotpowers.jp/goods/1-270.html", $webResult->getUrl());
    }

}

