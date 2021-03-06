#Implementation
#DB name = PHPproject

#DROP SCHEMA IF EXISTS
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS basket;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users; 

CREATE TABLE users(
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
username VARCHAR(50) NOT NULL,
password VARCHAR(50) NOT NULL
)
ENGINE=InnoDB; 

CREATE TABLE products(
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
productname VARCHAR(50),
price INT
)
ENGINE=InnoDB;

CREATE TABLE basket(
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
userId INT NOT NULL,
productId INT NOT NULL,
CONSTRAINT FK_User FOREIGN KEY(userId) REFERENCES users(id) ON DELETE CASCADE,
CONSTRAINT FK_Product_Basket FOREIGN KEY(productId) REFERENCES products(id) ON DELETE CASCADE
)
ENGINE=InnoDB;

CREATE TABLE sessions(
id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
userId INT NOT NULL,
token VARCHAR(50) NOT NULL,
last_used INT NOT NULL,
CONSTRAINT FK_User_Session FOREIGN KEY(userId) REFERENCES users(id) ON DELETE CASCADE
)
ENGINE=InnoDB;