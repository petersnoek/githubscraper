<?php

require 'vendor/autoload.php';
use Philo\Blade\Blade;
$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';
$blade = new Blade($views, $cache);

// load an html page
$url = "https://github.com/petersnoek";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
$html = curl_exec($curl);

echo $blade->view()->make('index')->with('html', $html)->render();