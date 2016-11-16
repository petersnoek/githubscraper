<?php

require 'vendor/autoload.php';
use Philo\Blade\Blade;
$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';
$blade = new Blade($views, $cache);

// part 1: load an html page
$url = "https://github.com/petersnoek";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
$html = curl_exec($curl);

// part 2: search for specific piece of HTML
require 'inc/dominnerhtml.php';

// load page into DOM object; if it fails then show the errors
$DOM = new DOMDocument;
libxml_use_internal_errors(true);       // libxml should be silent; we will show errors ourselves
if (!$DOM->loadHTML($html)) {
    var_export(libxml_get_errors());
    libxml_clear_errors();
    die();
}

// query DOM object
$xpath = new DOMXPath($DOM);

$resultnodes = $xpath->query('//div[@class="js-contribution-graph"]/
div[@class="mb-5 border border-gray-dark rounded-1 py-2"]/
div[@class="js-calendar-graph is-graph-loading graph-canvas calendar-graph height-full"]');
$part = '';
foreach ($resultnodes as $node) {
    $innerhtml = DOMinnerHTML($node);
    $part = $innerhtml;
}


echo $blade->view()->make('index')->with('html', $part)->render();