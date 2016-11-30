<?php

class GithubCommit {

    public $sha;
    public $message;
    public $author_email;
    public $author_name;

    public function __construct($sha, $message, $author_email, $author_name) {
        $this->sha = $sha;
        $this->message = $message;
        $this->author_email = $author_email;
        $this->author_name = $author_name;
    }

}