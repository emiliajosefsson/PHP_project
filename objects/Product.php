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
    /* if(empty($productname_IN) && empty($price_IN)) {
            
        echo "Vänligen fyll i alla rutor";
        die();

    } else { */
        $sql = "SELECT id FROM products WHERE productname=:productname_IN";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(":productname_IN", $productname_IN);
        
        $error = new stdClass();

        if( !$stmt->execute() ) {
            $error->message = "Could not execute query!";
            $error->code = "0001";
            die();
        }

        $num_rows = $stmt->rowCount();
        if($num_rows > 0) {
            $error->message = "The prodict already exists";
            $error->code = "0002";
            die();
        } 

    $sql = "INSERT INTO products (productname, price) VALUES (:productname_IN, :price_IN)";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":productname_IN", $productname_IN);
    $stmt->bindParam(":price_IN", $price_IN);


    if( !$stmt->execute() ) {
        echo "Kunde inte skapa produkten!";
        die();
    } else {

    $this->productname = $productname_IN;
    $this->price = $price_IN;

    return "Produkt ". $this->productname . " är nu skapad med priset " . $this->price . " SEK";
    
    

    }
}

function DeleteProduct($id_IN) {
    $sql = "DELETE FROM products WHERE id=:id_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":id_IN", $id_IN);
    $stmt->execute();


    if($stmt->rowCount() <= 0) {
        echo "Det finns ingen produkt med id  $id_IN";
        die();
    }

    $this->id = $id_IN;

    return "Produkten $this->id är nu borttagen";

}


function UpdateProduct($id_IN, $productname_IN = "", $price_IN = "" ) {
echo "hej";
}

function SearchProduct($productname_IN){
    $sql = "SELECT productname, price FROM products WHERE productname LIKE :productname_IN";
    $stmt = $this->connect->prepare($sql);
    $productname_IN = '%'. $productname_IN . '%';
    $stmt->bindParam(":productname_IN", $productname_IN);
    $stmt->execute();

    //$this->productname = $productname_IN;

     if($stmt->rowCount() <= 0) {
        echo "Det finns ingen product med namn $productname_IN";
        die();
    } 

    return json_encode($stmt->fetchAll());
    // return "Produkterna med namn $productname_IN är följande";

}

function allProducts(){
    $sql = "SELECT productname, price FROM products";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();

    return print_r(json_encode($stmt->fetchAll()));
}


function SortProduct($sortBy){
    if ($sortBy == "expensive"){
        $this->sortPriceExpensive();
    }else if($sortBy == "cheapest"){
        $this->sortPriceCheapest();
    }else if($sortBy == "first"){
        $this->sortNameFirst();
    }else if($sortBy == "last"){
        $this->sortNameLast();
    }
}

 private function sortPriceExpensive(){
    $sql = "SELECT productname, price FROM products ORDER BY price DESC";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute(); 

    return  print_r(json_encode($stmt->fetchAll()));
}

private function sortPriceCheapest(){
    $sql = "SELECT productname, price FROM products ORDER BY price";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute(); 

    return  print_r(json_encode($stmt->fetchAll()));
}

private function sortNameFirst(){
    $sql = "SELECT productname, price FROM products ORDER BY productname";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute(); 

    return  print_r(json_encode($stmt->fetchAll()));
}
private function sortNameLast(){
    $sql = "SELECT productname, price FROM products ORDER BY productname DESC";
    $stmt = $this->connect->prepare($sql);
    $stmt->execute(); 

    return  print_r(json_encode($stmt->fetchAll()));
}



}

