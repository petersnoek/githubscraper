<?php

class GithubEvent {

    public $id;             // string
    public $type;           // string
    public $created_at;     // DateTime
    public $commits;        // array of GithubCommit objects


}