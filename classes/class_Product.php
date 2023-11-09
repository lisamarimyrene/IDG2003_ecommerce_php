<?php
require_once "class_Database.php";

//echo "In class: Product<br>";

// * Product class, that extends the Database class. Add new product functionality.
class Product extends Database {

    // Set properties
    protected $product_id;
    protected $product_name;
    protected $product_img;
    protected $description;
    protected $price;
    
    // * A construct-function/method to make it run right away when you create an object.
    public function __construct($product_name, $product_img, $description, $price) {
        $this->setValues($product_name, $product_img, $description, $price);
        $this->addProductToDB($this->product_name, $this->image_name, $this->description, $this->price);
        //echo $this->description;
    }
    
    // * Method to set the values for the properties. 
    public function setValues($product_name, $product_img, $description, $price) {
        
        $this->product_name = $product_name;
        $this->image_name = $product_img;
        $this->description = $description;
        $this->price = $price;
    }

    // * Method adding the properties to the database.
    protected function addProductToDB($product_name, $product_img, $description, $price) {
        
        // Connecting to database. 
        $connection = parent::connect();

        // Sanitizing the properties, calling the cleanVar function. 
        $product_name = parent::cleanVar($product_name, $connection);
        $product_img = parent::cleanVar($product_img, $connection);
        $description = parent::cleanVar($description, $connection);
        $price = parent::cleanVar($price, $connection);

        // Query the database.
        $query = "INSERT INTO products(product_name, image_name, description, price) ";
        $query .= "VALUES ('$product_name','$product_img','$description','$price');";

        $result = mysqli_query($connection, $query);

        // Printing error message in case of query failure.
        if (!$result){
            die ("Adding product failed!" . mysqli_error($connection));
        } else {
            echo "New product added!<br>"; 
        }
        
        // Disconnect from database. 
       parent::disconnect($connection);
    }

    // * Method that reads from the products table.  
    static function readProductsTable(){
        $productsArray = Database::readFromTable('products');

        // Returns the array
        return $productsArray;
    }

    // * Method that deletes selected product ID from the product table.
    static function deleteOrder($productID){
        // Connecting to database. 
        $connection = parent::connect();
        
        // Making the query to be sent to the database.
        $query = "DELETE FROM products ";
        $query .= " WHERE product_id = $productID "; 
    
        // Printing error message in case of query failure.
        $result = mysqli_query($connection, $query);
        if (!$result){
            die("Deleting Query Failed!" . mysqli_error($connection));
        } else {
            //echo "Product Entry Deleted!<br>";
        }

        // Disconnect from database. 
        parent::disconnect($connection);

    }

    // * Method that reads the a product depending on a selected product_id. 
    static function readProductsToCart($productID) {
        //echo "in readProductsToCart<br>";
        // Connecting to database. 
        $connection = parent::connect();

        $query = "SELECT product_name, image_name, description, price ";
        $query .= "FROM products ";
        $query .= "WHERE product_id = $productID ";

        // Printing error message in case of query failure.
        $result = mysqli_query($connection, $query);
        if(!$result){
            //die("Reading products failed!" . mysqli_error($connection));
        }else {
            //echo "Reading products successfully!<br>";
            $row = mysqli_fetch_assoc($result);
            return $row;
        }

        // Disconnect from database. 
        parent::disconnect($connection);
    }
    
    
}

?>