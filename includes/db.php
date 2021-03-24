<?php

$dsn = "mysql:host=localhost;dbname=PHPproject";
$user = "root";
$password = "";
$pdo = new PDO($dsn, $user, $password);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


