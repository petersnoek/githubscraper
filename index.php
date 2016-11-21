<?php
//phpinfo();


// includes
require 'inc/configureblade.php';
require 'inc/githubscraper.class.php';
//require 'vendor/cache/memcached-adapter/MemcachedCachePool.php';

// settings
$urls = [
    ['naam'=>'Peter', 'github'=>'https://github.com/petersnoek'],
    ['naam'=>'Leon', 'github'=>'https://github.com/grondwortel'],
    ['naam'=>'Tim', 'github'=>'https://github.com/thaillie'],
];

// create scrapers from urls
$scrapers = [];
foreach ($urls as $url)
{
    $scraper = new githubscraper($url['github'], $url['naam']);
    $result = $scraper->Process();
    $scrapers[] = $scraper;
}

//date_default_timezone_set('Europe/Amsterdam');
//$client = new \Memcached();
//$client->addServer('localhost', 11211);
//$pool = new Cache\Adapter\Memcached\MemcachedCachePool($client);
//
//// Get an item (existing or new)
//$item = $pool->getItem('cache_key');
//
//// Set some values and store
//$item->set('value');
//$item->expiresAfter(60);
//$pool->save($item);
//
//// Verify existence
//$pool->hasItem('cache_key'); // True
//$item->isHit(); // True
//
//// Delete
////$pool->deleteItem('cache_key');
////$pool->hasItem('cache_key'); // False
//
//
//if ($pool->hasItem('cache_key'))
//{
//    echo 'Memcached installed';
//}
//else {
//    echo 'Memcached not installed';
//}
//
//die();

echo $blade->view()->make('index')->with('scrapers', $scrapers)->render();