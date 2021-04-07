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
    $return->message = "Product ". $this->productname . " is now created " . $this->price . " SEK";
    return $return;

    
}

function DeleteProduct($id_IN) {
    $sql = "DELETE FROM products WHERE id=:id_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":id_IN", $id_IN);
    $stmt->execute();

    $this->id = $id_IN;

    $error = new stdClass();
    if($stmt->rowCount() <= 0) { //kolla upp detta
        $error->message =  "There is no product with id " . $this->id;
        $error->code = "0012";
        return $error;
    }

   
    $return = new stdClass();
    $return->message =  "Product " . $this->id .  " is now deleted";
    return $return;

}


function UpdateProduct($id_IN, $productname_IN = "", $price_IN = "" ) {
    $return = new stdClass();
    if(!empty($productname_IN)){
       $return->message = $this->updateProductname($id_IN,$productname_IN);
    }
    if(!empty($price_IN)){
        $return->message =  $this->updatePrice($id_IN,$price_IN);
    }
     return $return;
}

private function updateProductname($id_IN,$productname_IN){
    $sql = "UPDATE products SET productname = :productname_IN WHERE id = :id_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":productname_IN", $productname_IN);
    $stmt->bindParam(":id_IN", $id_IN);
    $stmt->execute();

    $this->id = $id_IN;

    $error = new stdClass();
    if($stmt->rowCount() < 1) { //kolla upp detta
        $error->message=  "There is no product with id " . $this->id . " or the product already have this value";
        $error->code = "0012";
        return $error;
       
    } 
    /* $this->productname = $productname_IN;
    $return = new stdClass();
    $return->message =  "Name is now updated to " . $this->productname;
    return $return;
  */
  return "Product is now updated";
}

private function updatePrice($id_IN,$price_IN){
    $sql = "UPDATE products SET price = :price_IN WHERE id = :id_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":price_IN", $price_IN);
    $stmt->bindParam(":id_IN", $id_IN);
    $stmt->execute();

    $this->id = $id_IN;

    $error = new stdClass();
    if($stmt->rowCount() < 1) { //kolla upp detta
        $error->message=   "There is no product with id " . $this->id . " or the product already have this value";
        $error->code = "0012";
        return $error;
    } 
    
   
    return "Product is now updated";
    /* $return = new stdClass();
    $return->message =  "price is now updated to " . $this->price . " SEK";
    return $return; */
    
    // return "price is now updated to " . $this->price . " SEK";
}

function SearchProduct($productname_IN){
    $sql = "SELECT productname, price FROM products WHERE productname LIKE :productname_IN";
    $stmt = $this->connect->prepare($sql);
    $productname_IN = '%'. $productname_IN . '%';
    $stmt->bindParam(":productname_IN", $productname_IN);
    $stmt->execute();


     if($stmt->rowCount() <= 0) { //kolla detta
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

