# emilia_project

The task was to create a simple API with endpoints that covers the basic functions for an e-commerse site.

## Setup

To use these endpoints create a database named "PHPproject" and then run the SQL query to create the tables. 

### Technologies
Project is created with
* PHP version 7

### Coding Standard
* Public method  names must be definied with UpperCamelCase
* Private method names must be definied with camelCase
* Use print_r in endpoints, not in the classes
* All placeholders must be named like :placeholder_IN
* All classes must be definied with uppercase letter

#### Endpoints
* addUser.php
Creating a user 
* loginUser.php
Logs in a user
* updateUser.php
Updates a users username and password
* deleteUser.php
Deletes a user
* addProduct.php
Creates a product
* allProducts.php
Fetches and showes all the products
* deleteProduct.php
Deletes a product
* searchProduct
Searches for product
* sortProduct.php
Sort all products by the most expensive, the cheapest or by alphabetical order
* updateProduct
Updates a products name and price
* addBasket.php
A user can add a product to their cart
* allProductsBasket
Shows all the prodocuts that a user has in their cart
* checkoutBasket.php
A user check out, all the products in their cart will be deleted
* deleteProductsBasket
A user deletes a product from their cart



