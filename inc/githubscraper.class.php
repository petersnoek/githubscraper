<?php

class githubscraper
{
    // these are given upon creation of the class
    public $url;
    public $displayName;

    // these are filled after a call to 'Process()'
    public $html;
    public $DOM;
    public $avatarurl;
    public $activitysvg;
    public $errors = [];
    public $githubDisplayName;
    public $githubAccountName;

    // create this class by giving it a github page and a displayname
    // example: $scraper = new githubscraper('https://github.com/petersnoek', 'Peter Snoek');
    function __construct($url, $displayName)
    {
        $this->url = $url;
        $this->displayName = $displayName;
    }

    // returns:
    //  TRUE is no errors found
    //  FALSE is error found (errors are in $errors)
    //
    function Process()
    {
        // get new version of the page and store it in the cache
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $this->html = curl_exec($curl);

        // search for specific piece of HTML; load page into DOM object; if it fails then show the errors
        $this->DOM = new DOMDocument;
        libxml_use_internal_errors(true);       // libxml should be silent; we will show errors ourselves
        if (!$this->DOM->loadHTML($this->html)) {
            $this->errors[] = libxml_get_errors();
            libxml_clear_errors();
            return false;
        }

        // query DOM object
        $xpath = new DOMXPath($this->DOM);

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

        $resultnodes = $xpath->query('//span[@class="vcard-fullname d-block"]');
        foreach ($resultnodes as $node) {
            $this->githubDisplayName = $this->DOMinnerHTML($node);
        }

        $resultnodes = $xpath->query('//span[@class="vcard-username d-block"]');
        foreach ($resultnodes as $node) {
            $this->githubAccountName = $this->DOMinnerHTML($node);
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