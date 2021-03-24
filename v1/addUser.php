<?php

include '../includes/db.php';
include '../objects/User.php';




if(empty($_GET['username']) || empty($_GET['password'])) {
    echo "vänligen fyll i både lösenord samt användarnamn";
    die();

} 

$user = new User($pdo);

// print_r(json_encode($user->CreateUser($_GET['username'], $_GET['password'])));
$user->CreateUser($_GET['username'], $_GET['password']);

