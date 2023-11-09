<?php
require_once "class_Database.php";

//echo "In class: Order<br>";

// * Order class, that extends the Database class. Create new order functionality.
class Order extends Database {

    // Set properties
    protected $order_id;
    protected $customer_id;
    protected $product_id;
    protected $time;
    protected $order_quantity;

    // * A construct-function/method to make it run right away when you create an object.
    public function __construct($customer_id, $product_id, $time, $order_quantity) {
        $this->setValues($customer_id, $product_id, $time, $order_quantity);
        $this->addOrderToDB($this->customer_id, $this->product_id, $this->time, $this->quantity);
    }
    
    // * Method to set the values for the properties. 
    public function setValues($customer_id, $product_id, $time, $order_quantity) {
        
        $this->customer_id = $customer_id;
        $this->product_id = $product_id;
        $this->time = $time;
        $this->quantity = $order_quantity;
    }

    // * Method that adds the properties to the database.
    protected function addOrderToDB($customer_id, $product_id, $time, $order_quantity) {
        
        // Connecting to database. 
        $connection = parent::connect();

        // Sanitizing the properties, calling the cleanVar function. 
        $customer_id = parent::cleanVar($customer_id, $connection);
        $product_id = parent::cleanVar($product_id, $connection);
        $time = parent::cleanVar($time, $connection);
        $order_quantity = parent::cleanVar($order_quantity, $connection);

        // Query the database.
        $query = "INSERT INTO orders(customer_id, product_id, time, quantity) ";
        $query .= "VALUES ('$customer_id','$product_id','$time','$order_quantity');";

        $result = mysqli_query($connection, $query);
        

        // Printing error message in case of query failure.
        if (!$result){
            die ("Adding order failed!" . mysqli_error($connection));
        } else {
            echo "New order added!<br>"; 
        }
        
        // Disconnect from database. 
        parent::disconnect($connection);
    }

    // * Method that reads from the products table.  
    static function readOrdersTable(){
        $orderArray = Database::readFromTable('orders');

        // Returning the array. 
        return $orderArray;
    }

}

?>