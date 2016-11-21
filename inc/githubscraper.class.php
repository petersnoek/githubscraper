<?php

class githubscraper
{
    public $url;
    public $naam;
    public $avatarurl;
    public $activitysvg;
    public $errors = [];
    public $displayName;

    function __construct($url, $naam)
    {
        $this->url = $url;
        $this->naam = $naam;
    }

    // returns:
    //  TRUE is no errors found
    //  FALSE is error found (errors are in $errors)
    //
    function Process()
    {
        // load given html page
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $html = curl_exec($curl);

        // search for specific piece of HTML; load page into DOM object; if it fails then show the errors
        $DOM = new DOMDocument;
        libxml_use_internal_errors(true);       // libxml should be silent; we will show errors ourselves
        if (!$DOM->loadHTML($html)) {
            $this->errors[] = libxml_get_errors();
            libxml_clear_errors();
            return false;
        }

        // query DOM object
        $xpath = new DOMXPath($DOM);

        $resultnodes = $xpath->query('//div[@class="js-contribution-graph"]/
div[@class="mb-5 border border-gray-dark rounded-1 py-2"]/
div[@class="js-calendar-graph is-graph-loading graph-canvas calendar-graph height-full"]');
        foreach ($resultnodes as $node) {
            $this->activitysvg = $this->DOMinnerHTML($node);
        }

        $resultnodes = $xpath->query('//img[@class="avatar width-full rounded-2"]');
        foreach ($resultnodes as $node) {
            $this->avatarurl = $node->getAttribute("src");
        }

        $resultnodes = $xpath->query('//div[@class="user-profile-mini-vcard d-table"]');
        foreach ($resultnodes as $node) {
            $this->displayName = $this->DOMinnerHTML($node);
        }


        return true;
    }

    function DOMinnerHTML(DOMNode $element)
    {
        $innerHTML = "";
        $children  = $element->childNodes;

        foreach ($children as $child)
        {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }



}