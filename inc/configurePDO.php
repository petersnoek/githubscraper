<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=progress', 'progress', 'Studentje1');
} catch (PDOException $e) {
    print "Database connection error!: " . $e->getMessage() . "<br/>";
    die();
}