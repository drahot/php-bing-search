<?php
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Util', __DIR__);

$accountKey = "YOUR ACCOUNT KEY";
$downloader = new Util\ImageDownloader($accountKey);
$downloader->download('おっぱい', './data');
