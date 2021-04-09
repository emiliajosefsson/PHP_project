<?php

class Basket {

private $connect;
private $id;
private $productId;

function __construct($db) {
    $this->connect = $db;
}

function AddBasket($token_IN, $productId_IN){
    $sql = "INSERT INTO basket (userId, productId) SELECT sessions.userId, :productId_IN FROM sessions 
    WHERE sessions.token = :token_IN";

    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":token_IN", $token_IN);
    $stmt->bindParam(":productId_IN", $productId_IN);
    $stmt->execute();
    $error = new stdClass();

    $this->productId = $productId_IN;

    $num_rows = $stmt->rowCount();
    if($num_rows <= 0) {
        $error->message = "There is no product with id " . $this->productId;
        $error->code = "0021";
        return $error;
    } 
    
    $return = new stdClass();
    $return->message = "Product with id " . $this->productId . " is now added to your cart";
    return $return;
}

function AllProductsBasket($token_IN){
    $sql = "SELECT productname, price FROM products 
    JOIN basket ON products.Id = basket.productId 
    JOIN sessions ON sessions.userId = basket.userId 
    WHERE token = :token_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":token_IN", $token_IN);

    $error = new stdClass();
    
    if( !$stmt->execute() ) {
        $error->message = "Could not execute query!";
        $error->code = "0001";
        return $error;
    }

    $num_rows = $stmt->rowCount();
    if($num_rows == 0) {
        $error->message = "You have no products in your shopping cart";
        $error->code = "0013";
        return $error;
    } 
    $return = new stdClass();
    $return->product = $stmt->fetchAll();
    return $return;
}

function DeleteProductsBasket($id_IN, $token_IN){
    $sql = "DELETE basket FROM basket JOIN sessions ON sessions.userId = basket.userId WHERE basket.id = :id_IN AND token = :token_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":id_IN", $id_IN);
    $stmt->bindParam(":token_IN", $token_IN);
    $stmt->execute();

    $this->id = $id_IN;

    $error = new stdClass();
    if($stmt->rowCount() == 0) { 
        $error->message =  "There is no product with id " . $this->id;
        $error->code = "0012";
        return $error;
    }

    $return = new stdClass();
    $return->message = "Product is now deleted";
    return $return;
}

function CheckoutBasket($token_IN){
    $sql= "DELETE basket FROM basket 
    JOIN users ON users.Id = basket.userId
    JOIN sessions ON sessions.userId = users.Id 
    WHERE token = :token_IN";

    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":token_IN", $token_IN);
    $error = new stdClass();

    if( !$stmt->execute() ) {
        $error->message = "Could not execute query!";
        $error->code = "0001";
        return $error;
    } 


     $num_rows = $stmt->rowCount();
    if($num_rows <= 0) {
        $error->message = "You have no products in your shopping cart";
        $error->code = "0013";
        return $error;
    } 
    $return = new stdClass();
    $return->message = "You have checkout";
    return $return;
}

}