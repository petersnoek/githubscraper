<?php

class Student {

    private $pdo;
    public $id;
    public $displayname;
    public $fullname;
    public $github_handle;
    public $trello_handle;
    public $tags;
    public $trello_board_1;

    public $trello_fullname;
    public $trello_avatar_base64;
    public $trello_activeboard_name;

    public $github_events;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->github_events = [];
    }

    /**
     * Returns an array of Student objects
     *
     * @return Array()
     */
    function All() {
        //if (empty($pdo)) return null;
        $sth = $this->pdo->prepare("SELECT * from students");
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_CLASS, "Student");
    }

    function ParseTrelloMember($json) {
        $obj = json_decode($json);
        $this->trello_fullname = $obj->fullName;
    }

    function ParseTrelloActiveBoard($json) {
        $obj = json_decode($json);
        $this->trello_activeboard_name = $obj->name;
    }

    function ParseGithubEvents($json) {
        $obj = json_decode($json);

        foreach($obj as $o) {
            if (isset($o->type) && $o->type == "PushEvent") {
                $created = new DateTime($o->created_at);
                printf('%s Push_id:%s to Repo:%s with Message:%s<br>' , $created->format(''), $o->id, $o->repo->name, $o->payload->commits[0]->message);
            }

        }

    }


}