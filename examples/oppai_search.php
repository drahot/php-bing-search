<?php

require __DIR__.'/../vendor/autoload.php';

use Bing\Search\Factory;

$accountKey = "YOUR ACCOUNT KEY";
$factory = new Factory($accountKey);
$s = $factory->createImageSearch('oppai');

foreach ($s as $key => $resultObject) {
    foreach ($resultObject as $obj) {
        echo $obj, PHP_EOL;
    }
}

