<?php

session_start();

// includes
require 'inc/configureblade.php';
require 'inc/githubscraper.class.php';

// settings
$urls = [
    ['naam'=>'Peter', 'github'=>'https://github.com/petersnoek'],
    ['naam'=>'Leon', 'github'=>'https://github.com/LenGh'],
    ['naam'=>'Tim', 'github'=>'https://github.com/thaillie'],
];

// create scrapers from urls
$scrapers = [];
foreach ($urls as $url)
{
    $scraper = new githubscraper($url['github'], $url['naam']);
    $scraper->Process();        // retrieve HTML page and pick content to display
    $scrapers[] = $scraper;
}

echo $blade->view()->make('index')->with('scrapers', $scrapers)->render();