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
     
if(empty($_GET['id'])) {
    $error->message = "Please insert an id";
    $error->code = "0007";
    print_r(json_encode($error));
    die();
} 

$product = new Product($pdo);            
print_r(json_encode($product->DeleteProduct($_GET['id'])));

        

       