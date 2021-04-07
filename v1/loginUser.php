<?php

include '../includes/db.php';
include '../objects/User.php';


if(empty($_GET['username']) || empty($_GET['password'])) {
    $error = new stdClass();
    $error->message = "Please insert both username and password";
    $error->code = "0014";
    print_r(json_encode($error));
    die();
} 

/* $user = new User($pdo);
$return = new stdClass();
$return->token = $user->LoginUser($_GET['username'], $_GET['password']);
print_r(json_encode($return)); */

$user = new User($pdo);
print_r(json_encode($user->LoginUser($_GET['username'], $_GET['password'])));