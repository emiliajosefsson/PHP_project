<?php 

include '../includes/db.php';
include '../objects/Basket.php';
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

$basket = new Basket($pdo);

print_r(json_encode($basket->AllProductsBasket($_GET['token'])));