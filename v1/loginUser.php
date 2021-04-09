<?php

include '../includes/db.php';
include '../objects/User.php';


if(empty($_GET['username']) || empty($_GET['password'])) {
    $error = new stdClass();
    $error->message = "Please insert both a username and a password";
    $error->code = "0014";
    print_r(json_encode($error));
    die();
} 

$user = new User($pdo);
print_r(json_encode($user->LoginUser($_GET['username'], $_GET['password'])));