<?php

class User {

private $connect;     
private $id;
private $username;
//private $password;
private $token;
private $salt = "hie23yr8i2hHU€&ml?I";


function __construct($db) {
    $this->connect = $db;
}


function CreateUser($username_IN,$password_IN){
   
    $sql = "SELECT id FROM users WHERE username=:username_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":username_IN", $username_IN);
        
    $error = new stdClass();
        
    if( !$stmt->execute() ) {
        $error->message = "Could not execute query!";
        $error->code = "0001";
        return $error;
    }

    $num_rows = $stmt->rowCount();
    if($num_rows > 0) {
        $error->message = "The user already exists";
        $error->code = "0002";
        return $error;
    }

    $password_IN = md5($password_IN.$this->salt);    

    $sql = "INSERT INTO users (username, password) VALUES (:username_IN, :password_IN)";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":username_IN", $username_IN);
    $stmt->bindParam(":password_IN", $password_IN);


    if( !$stmt->execute() ) {
        $error->message = "Could not execute query!";
        $error->code = "0001";
        return $error;
    } 

    $this->username = $username_IN;
    

    $return = new stdClass();
    $return->message = "User ". $this->username . " is now created";
    return $return;
    
}

function LoginUser($username_IN, $password_IN){
    $sql = "SELECT id, username, password FROM users WHERE username=:username_IN AND password=:password_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":username_IN", $username_IN);
    $stmt->bindParam(":password_IN", $password_IN);

    $password_IN = md5($password_IN.$this->salt); 

    $stmt->execute();
    $num_rows = $stmt->rowCount();

    if($num_rows < 1) {
        $error = new stdClass();
        $error->message = "Invalid username or password";
        $error->code = "0010";
        return $error;
    }
    
    $row = $stmt->fetch();


    $return = new stdClass();
    $return->token = $this->createToken($row['id'], $row['username']);
    return $return;
}

 private function createToken($id_IN, $username_IN){

    $checked_token = $this->checkToken($id_IN);

    if($checked_token != false){
        return $checked_token;

    }

    $token = md5(time() . $id_IN . $username_IN);

    $sql = "INSERT INTO sessions (userId, token, last_used) VALUES (:userId_IN, :token_IN, :last_used_IN)";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":userId_IN", $id_IN);
    $stmt->bindParam(":token_IN", $token);
    $time = time();
    $stmt->bindParam(":last_used_IN", $time);

    $stmt->execute();
    $this->token = $token;
    return $this->token;
}


private function checkToken($id_IN){
    $sql = "SELECT token, last_used FROM sessions WHERE userId = :userId_IN AND last_used > :token_time_IN LIMIT 1";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":userId_IN", $id_IN);
    $token_time = time() - (60*60);
    $stmt->bindParam(":token_time_IN", $token_time);

    $stmt->execute();

    $return = $stmt->fetch();

    if(isset($return['token'])){
        
        return $return['token'];
        
    }
    
    return false;  

}

function ValidToken($token){
    $sql = "SELECT token, last_used FROM sessions WHERE token = :token_IN AND last_used > :token_time_IN LIMIT 1";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":token_IN", $token);
    $token_time = time() - (60*60);
    $stmt->bindParam(":token_time_IN", $token_time);
    $stmt->execute();

    $return = $stmt->fetch();

    if(isset($return['token'])){
        $this->updateToken($return['token']);
        return true;
    }

    $sql = "DELETE basket FROM basket 
    JOIN users ON basket.userId = users.Id 
    JOIN sessions ON sessions.userId = users.Id 
    WHERE token = :token_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":token_IN", $token);
    $stmt->execute();
    
    return false; 

}

private function updateToken($token){
    $sql = "UPDATE sessions SET last_used=:last_used_IN WHERE token = :token_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":token_IN", $token);
    $time = time();
    $stmt->bindParam(":last_used_IN", $time);
    $stmt->execute();
}

function UpdateUser($token_IN, $username_IN = "", $password_IN = "" ) {
    $return = new stdClass();
    if(!empty($username_IN)){
       $return->error = $this->updateUsername($token_IN,$username_IN);
    }
    if(!empty($password_IN)){
        $return->error =  $this->updatePassword($token_IN,$password_IN);
    }
     return $return;
}

private function updateUsername($token_IN,$username_IN){
    $sql = "UPDATE users JOIN sessions ON users.Id = sessions.userId SET username = :username_IN 
    WHERE token = :token_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":username_IN", $username_IN);
    $stmt->bindParam(":token_IN", $token_IN);
    $stmt->execute();

    $this->username = $username_IN;

    $error = new stdClass();
    if($stmt->rowCount() < 1) { 
        $error->message=  "The user already have this value";
        $error->code = "0012";
        return $error;
       
    }  
    /* $this->productname = $productname_IN;
    $return = new stdClass();
    $return->message =  "Name is now updated to " . $this->productname;
    return $return;
  */
  return "User is now updated";
}

private function updatePassword($token_IN,$password_IN){
    $password_IN = md5($password_IN.$this->salt); 

    $sql = "UPDATE users JOIN sessions ON users.Id = sessions.userId SET password = :password_IN WHERE token = :token_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":password_IN", $password_IN);
    $stmt->bindParam(":token_IN", $token_IN);
    $stmt->execute();

    $error = new stdClass();
    if($stmt->rowCount() < 1) { 
        $error->message=  "The user already have this value";
        $error->code = "0012";
        return $error;
    } 
     
   
    return "User is now updated";
    /* $return = new stdClass();
    $return->message =  "price is now updated to " . $this->price . " SEK";
    return $return; */
    
    // return "price is now updated to " . $this->price . " SEK";
}



function DeleteUser($token_IN) {
    $sql = "DELETE users FROM users 
    JOIN sessions ON sessions.userId = users.Id 
    WHERE token = :token_IN";
    $stmt = $this->connect->prepare($sql);
    $stmt->bindParam(":token_IN", $token_IN);

    if( !$stmt->execute() ) {
        $error->message = "Could not execute query!";
        $error->code = "0001";
        return $error;
    } 

    $return = new stdClass();
    $return->message = "User  is now deleted";
    return $return;

}


}





