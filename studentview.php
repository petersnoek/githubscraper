<?php

session_start();

// includes
require 'inc/configureblade.php';
require 'inc/configurePDO.php';
require 'model/Student.php';

$s = new Student($pdo);
$students = $s->All();


//foreach($students as $h) {
//    $h['github_link'] = 'https://api.github.com/users/' . $h . '/events';
//}
//var_export($students);

echo $blade->view()->make('studentview')->with('students', $students)->render();
