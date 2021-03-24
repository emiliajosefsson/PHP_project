<?php

class User {

private $connect;     
private $id;
private $username;
private $password;
private $token;
private $salt = "hie23yr8i2hHU€&ml?I";


function __construct($db) {
    $this->connect = $db;
}


function CreateUser($username_IN,$password_IN){
   /*  if(empty($username_IN) && empty($password_IN)) {
            
        echo "Vänligen fyll i alla rutor";
        die();

    } else { */
        $sql = "SELECT id FROM users WHERE username=:username_IN";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(":username_IN", $username_IN);
        
        
        if( !$stmt->execute() ) {
            echo " could not execute";

            die();
        }

        $num_rows = $stmt->rowCount();
        if($num_rows > 0) {
            echo "Användaren finns redan";
            die();
        }

    $password_IN = md5($password_IN.$this->salt);    

    $sql = "INSERT INTO users (username, password) VALUES (:username_IN, :password_IN)";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":username_IN", $username_IN);
    $stmt->bindParam(":password_IN", $password_IN);


    if( !$stmt->execute() ) {
        echo "Could not create user!";
        die();
    } else {

    $this->username = $username_IN;
    $this->password = $password_IN;

    /* $message = new stdClass();
    $message->text = "Användare ". $this->username . " är nu skapad";
    return $message;
 */
    return "Användare ". $this->username . " är nu skapad";

    /* echo "Användaren är nu skapad";
    die(); 
    }
    */
    

    }
}


function DeleteUser($id_IN) {
    $sql = "DELETE FROM users WHERE id=:id_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":id_IN", $id_IN);
    $stmt->execute();


    if($stmt->rowCount() <= 0) {
        echo "Det finns ingen användare med id  $id_IN";
        die();
    }

    $this->id = $id_IN;

    return "Användare $this->id är nu borttagen";

}


}
