<?php

class Product {

private $connect;     
private $productname;
private $price;
private $id;

function __construct($db) {
    $this->connect = $db;
}


function CreateProduct($productname_IN,$price_IN){
    
    $sql = "SELECT id FROM products WHERE productname=:productname_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":productname_IN", $productname_IN);
        
    $error = new stdClass();

    if( !$stmt->execute() ) {
        $error->message = "Could not execute query!";
        $error->code = "0001";
        return $error;
    }

    $num_rows = $stmt->rowCount();
    if($num_rows > 0) {
        $error->message = "The product already exists";
        $error->code = "0002";
        return $error;
    } 
   
    $sql = "INSERT INTO products (productname, price) VALUES (:productname_IN, :price_IN)";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":productname_IN", $productname_IN);
    $stmt->bindParam(":price_IN", $price_IN);

    if( !$stmt->execute() ) {
        $error->message = "Could not execute query!";
        $error->code = "0001";
        return $error;
    } 

    $this->productname = $productname_IN;
    $this->price = $price_IN;


    $return = new stdClass();
    $return->message = "Product ". $this->productname . " is now created with the price " . $this->price . " SEK";
    return $return;

    
}

function DeleteProduct($id_IN) {
    $sql = "DELETE FROM products WHERE id=:id_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":id_IN", $id_IN);
    $stmt->execute();

    $this->id = $id_IN;

    $error = new stdClass();
    if($stmt->rowCount() == 0) { 
        $error->message =  "There is no product with id " . $this->id;
        $error->code = "0012";
        return $error;
    }

   
    $return = new stdClass();
    $return->message =  "Product " . $this->id .  " is now deleted";
    return $return;

}


function UpdateProduct($id_IN, $productname_IN = "", $price_IN = "" ) {
    $res = new stdClass();
    if(!empty($productname_IN)){
       $res = $this->updateProductname($id_IN,$productname_IN);
    }
    if(!empty($price_IN)){
        $res=  $this->updatePrice($id_IN,$price_IN);
    }
     return $res;
}

private function updateProductname($id_IN,$productname_IN){
    $sql = "UPDATE products SET productname = :productname_IN WHERE id = :id_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":productname_IN", $productname_IN);
    $stmt->bindParam(":id_IN", $id_IN);
    $stmt->execute();

    $this->id = $id_IN;

    $error = new stdClass();
    if($stmt->rowCount() < 1) { 
        $error->message=  "There is no product with id " . $this->id . " or the product already has that value";
        $error->code = "0012";
        return $error;
       
    } 
    
    $return = new stdClass();
    $return->message =  "Product is now updated";
    return $return;

}

private function updatePrice($id_IN,$price_IN){
    $sql = "UPDATE products SET price = :price_IN WHERE id = :id_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":price_IN", $price_IN);
    $stmt->bindParam(":id_IN", $id_IN);
    $stmt->execute();

    $this->id = $id_IN;

    $error = new stdClass();
    if($stmt->rowCount() < 1) { 
        $error->message=   "There is no product with id " . $this->id . " or the product already has that value";
        $error->code = "0012";
        return $error;
    } 
    
   
    $return = new stdClass();
    $return->message =  "Product is now updated";
    return $return;
}

function SearchProduct($productname_IN){
    $sql = "SELECT productname, price FROM products WHERE productname LIKE :productname_IN";
    $stmt = $this->connect->prepare($sql);
    $productname_IN = '%'. $productname_IN . '%';
    $stmt->bindParam(":productname_IN", $productname_IN);
    $stmt->execute();


     if($stmt->rowCount() == 0) { 
        $error = new stdClass();
        $error->message=  "There is no product with that name";
        $error->code = "0016";
        return $error;
    } 
    $this->productname = $productname_IN;
    $return = new stdClass();
    $return->products = $stmt->fetchAll();
    return $return;
    

}

function AllProducts(){
    $sql = "SELECT productname, price FROM products";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();

    $return = new stdClass();
    $return->products = $stmt->fetchAll();
    return $return;
}


function SortProduct($sortBy){
    if ($sortBy == "expensive"){
        return $this->sortPriceExpensive();
    }else if($sortBy == "cheapest"){
        return $this->sortPriceCheapest();
    }else if($sortBy == "first"){
       return  $this->sortNameFirst();
    }else if($sortBy == "last"){
        return $this->sortNameLast();
    }
}

 private function sortPriceExpensive(){
    $sql = "SELECT productname, price FROM products ORDER BY price DESC";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute(); 

    $return = new stdClass();
    $return->products = $stmt->fetchAll();
    return $return;
}

private function sortPriceCheapest(){
    $sql = "SELECT productname, price FROM products ORDER BY price";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute(); 

    $return = new stdClass();
    $return->products = $stmt->fetchAll();
    return $return;
}

private function sortNameFirst(){
    $sql = "SELECT productname, price FROM products ORDER BY productname";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute(); 

    $return = new stdClass();
    $return->products = $stmt->fetchAll();
    return $return;
}
private function sortNameLast(){
    $sql = "SELECT productname, price FROM products ORDER BY productname DESC";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute(); 

    $return = new stdClass();
    $return->products = $stmt->fetchAll();
    return $return;
}



}

