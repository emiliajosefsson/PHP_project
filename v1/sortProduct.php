<?php

include '../includes/db.php';
include '../objects/Product.php';
include '../objects/User.php';

$token = "";
$error = new stdClass();

if(!isset($_GET['token'])){
    $error->message = "No active session";
    $error->code = "0012";
    print_r(json_encode($error));
    die();
}
$token = $_GET['token'];

$user = new User($pdo);
$product = new Product($pdo);

if(!$user->ValidToken($token)) {
    $error->message = "Sessions is over, please log in again";
    $error->code = "0011";
    print_r(json_encode($error));
    die(); 
}


if(empty($_GET['product'])){
    $error->message = "Insert a value!";
    $error->code = "0004";
    print_r(json_encode($error));
    die();
}

if($_GET['product'] != "expensive" && $_GET['product'] != "cheapest" && $_GET['product'] != "first" && $_GET['product'] != "last"){
    $error->message = "Wrong value!";
    $error->code = "0005";
    print_r(json_encode($error));
    die();
} 

$product = new Product($pdo);
print_r(json_encode($product->SortProduct($_GET['product'])));