<?php

session_start();
$_SESSION['debug'][] = 'Start: ' . time();

require_once 'vendor/autoload.php';
require_once 'inc/configurePDO.php';
require_once 'inc/multirequest.php';
require_once 'model/Student.php';
require_once 'inc/configureblade.php';
require_once 'inc/DownloadRequest.php';

$key = '1e75705e74303222a2c02dbe004bd003';
$token = 'ab565931a21226bd2d47fde5eb10717461a3d56804081bb1aff22fe3093bbd75';

$forceReload = false;

// download github events for each user
$github_events_rq = [];
$trello_activeboard_rq = [];
$trello_activeboard_lists_rq = [];
$trello_memberinfo_rq = [];
$options = [CURLOPT_HTTPHEADER => ['User-Agent: GithubScraper', 'Authorization: token 61a95674d05218c075999688a14b3a0fb38edfcb']];

foreach($pdo->query('SELECT id, github_handle, trello_handle, trello_board_1 from students') as $row) {
    if (!empty($row['github_handle'])) {
        $github_events_rq[$row['id']] = new DownloadRequest($row['id'],
            sprintf('https://api.github.com/users/%s/events', $row['github_handle']),
            $options,
            60, $forceReload);

        if ( ! empty($row['trello_board_1']) ) {
            $trello_activeboard_rq[$row['id']] = new DownloadRequest($row['id'],
                sprintf('https://api.trello.com/1/boards/%s?key=%s&token=%s', $row['trello_board_1'], $key, $token),
                null,
                3600, $forceReload);

            $trello_activeboard_lists_rq[$row['id']] = new DownloadRequest($row['id'],
                sprintf('https://api.trello.com/1/boards/%s/lists?key=%s&token=%s', $row['trello_board_1'], $key, $token),
                null,
                3600, $forceReload);
        }

        if ( ! empty($row['trello_handle']))
            $trello_memberinfo_rq[$row['id']] = new DownloadRequest($row['id'],
                sprintf('https://api.trello.com/1/members/%s?key=%s&token=%s', $row['trello_handle'], $key, $token),
                null,
                3600*24, $forceReload);

    }
}
$github_events = multiRequest($github_events_rq, $options);
//echo '<pre>';
//print_r($github_events);

$trello_activeboards = multiRequest($trello_activeboard_rq);

$trello_activeboard_lists = multiRequest($trello_activeboard_lists_rq);
//echo '<pre>';
//print_r($trello_activeboard_lists);

$trello_memberinfo = multiRequest($trello_memberinfo_rq);
//echo '<pre>';
//print_r($trello_memberinfo);

$trello_avatar_rq = [];
foreach ($trello_memberinfo as $id => $tr) {
    $obj = json_decode($tr);
    //var_dump($obj);
    if ( ! empty($obj->avatarHash)) {
        $trello_avatar_rq[$id] = new DownloadRequest($id,
            sprintf('https://trello-avatars.s3.amazonaws.com/%s/50.png' ,$obj->avatarHash),
            null,
            3600*24, $forceReload);
    }
}
$trello_avatars = multiRequest($trello_avatar_rq);
foreach ($trello_avatars as $id => $imgdata) {
    $trello_avatars[$id] = base64_encode($imgdata);
}
//print_r($trello_avatars);

$studentObjects = [];
foreach($pdo->query('SELECT * from students') as $row) {
    $s = new Student($pdo);
    $s->id = $row['id'];
    $s->displayname = $row['displayname'];
    $s->fullname = $row['fullname'];
    $s->github_handle = $row['github_handle'];
    $s->trello_handle = $row['trello_handle'];
    $s->trello_board_1 = $row['trello_board_1'];
    if ( array_key_exists($s->id, $trello_memberinfo) ) $s->ParseTrelloMember($trello_memberinfo[$s->id]);
    if ( array_key_exists($s->id, $trello_avatars) ) $s->trello_avatar_base64 = ($trello_avatars[$s->id]);
    if ( array_key_exists($s->id, $trello_activeboards) ) $s->ParseTrelloActiveBoard($trello_activeboards[$s->id]);
    if ( array_key_exists($s->id, $github_events) ) $s->ParseGithubEvents($github_events[$s->id]);

    $studentObjects[] = $s;
}
//var_export($studentObjects);

echo $blade->view()->make('studentview')->with('debug', $github_events)->with('students', $studentObjects)->render();