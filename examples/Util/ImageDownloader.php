<?php
namespace Util;

use Bing\Search\Factory as SearchFactory;
use Bing\Search\ImageSearch;
use Bing\Search\SearchResult;
use React\EventLoop\Factory as ReactEventLoopFactory;
use React\Stream\Stream as ReactStream;

/**
 * Image Downloader
 * 
 * @author drahot
 */
class ImageDownloader
{

	private $accountKey;

	public function __construct($accountKey)
	{
		$this->accountKey = $accountKey;
	}

	public function download($query, $outDir)
	{
		if (empty($query)) {
			throw new \InvalidArgumentException('Query is invalid.');
		}		
		if (empty($outDir)) {
			throw new \InvalidArgumentException('OutDir is invalid.');
		}		
		if ('/' !== substr($outDir, -1, 1) ) {
			$outDir .= '/';
		}
		if (!file_exists($outDir)) {
			mkdir($outDir, 0777, true);
		}
		$searchFactory = new SearchFactory($this->accountKey);
		$searcher = $searchFactory->createImageSearch($query);
		foreach ($searcher as $searchResult) {
			$images = $this->selectImageFiles($outDir, $searchResult);
			$this->processDownload($images);
		}
	}

	private function selectImageFiles($outDir, SearchResult $searchResult)
	{
		$images = array();
		foreach ($searchResult as $imageResult) {
			$mediaUrl = $imageResult->getMediaUrl();
			if (0 === preg_match('/\.(jpg|jpeg).*/i', $mediaUrl)) {
				continue;
			}
			$pathInfo = pathinfo($mediaUrl);
			$filename = $outDir . $pathInfo['basename'];
			$images[$filename] = $mediaUrl;
		}
		return $images;
	}

	private function processDownload(array &$images)
	{
		$loop = ReactEventLoopFactory::create();
		foreach ($images as $file => $url) {
			$read = $this->getStream($loop, $url, 'r');
			if (!$read) {
				unset($images[$file]);
				echo "$file read failed\n";
				continue;
			}
			$write = $this->getStream($loop, $file, 'w');
		
			$read->on('end', function () use ($file, &$images) {
				unset($images[$file]);
				echo "Finished downloading $file\n";
			});
			$read->pipe($write);
		}
		$this->watchImages($loop, $images);
		echo "This script will show the download status every 1 seconds.\n";
		$loop->run();
	}

	private function getStream($loop, $path, $mode)
	{
		$fp = @fopen($path, $mode);
		if (!$fp) {
			return false;
		}
		stream_set_blocking($fp, 0);
		$s = new ReactStream($fp, $loop);
		return $s;
	}

	private function watchImages($loop, array &$images)
	{
		$loop->addPeriodicTimer(1, function ($timer, $loop) use (&$images) {
			if (0 === count($images)) {
				$loop->cancelTimer($timer);
				$loop->stop();
			}
			foreach ($images as $file => $url) {
				if (filesize($file) === 0) {
					unset($images[$file]);
					continue;
				}
				$mbytes = filesize($file) / (1024 * 1024);
				$formatted = number_format($mbytes, 3);
				echo "$file: $formatted MiB\n";
			}
		});
	}

}