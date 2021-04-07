<?php

include '../includes/db.php';
include '../objects/User.php';

$token = "";
$error= new stdClass();

if(!isset($_GET['token'])){
    $error->message = "No session";
    $error->code = "0012";
    print_r(json_encode($error));
    die();
}
$token = $_GET['token'];

$user = new User($pdo);


if(!$user->ValidToken($token)) {
    $error->message = "Sessions is over, please log in again";
    $error->code = "0011";
    print_r(json_encode($error));
    die(); 
} 

// $id = "";
$token = $_GET['token'];
$username = "";
$password = ""; 

/* if(!isset($_GET['id'])){
    $error->message = "Please insert an id";
    $error->code = "0007";
    print_r(json_encode($error));
    die();
}

$id = $_GET['id']; */

if(!isset($_GET['username']) && !isset($_GET['password'])){
    $error->message = "Please insert username or password";
    $error->code = "0021";
    print_r(json_encode($error));
    die();
} 

if(isset($_GET['username'])){
    $username = $_GET['username'];
}

if(isset($_GET['password'])){
    $password = $_GET['password'];
}


$user = new User($pdo);
print_r(json_encode($user->UpdateUser($token, $username, $password)));

