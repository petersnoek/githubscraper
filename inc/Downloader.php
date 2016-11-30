<?php

require_once 'FileCache.php';

class Downloader {

    private $cache;

    public function __construct(\FileCache $cache) {
        $this->cache = $cache;
    }

    public function Get($url) {

        if ( $this->cache->hasItem($url) ) {
            // found in cache. return it.
            return $this->cache->getItem($url);

        } else {
            // not found in cache. download it and return it.

            $content = file_get_contents($url);
            $this->cache->save($url, $content);
            return $content;
        }

    }
}