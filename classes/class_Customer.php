<?php
require_once "class_Database.php";

//echo "In class: Customer<br>";

// * Customer class, that extends the Database class. Create new customer functionality.
class Customer extends Database {

    // Set properties
    protected $customer_id;
    protected $customer_firstname;
    protected $customer_lastname;
    protected $customer_address;
    protected $customer_country;

    // * A construct-function/method to make it run right away when you create an object.
    public function __construct($customer_firstname, $customer_lastname, $customer_address, $customer_country) {
        $this->setValues($customer_firstname, $customer_lastname, $customer_address, $customer_country);
        $this->addCustomerToDB($this->firstname, $this->lastname, $this->address, $this->country);
    }
    
    // * Method to set the values for the properties. 
    public function setValues($customer_firstname, $customer_lastname, $customer_address, $customer_country) {
        
        $this->firstname = $customer_firstname;
        $this->lastname = $customer_lastname;
        $this->address = $customer_address;
        $this->country = $customer_country;
    }

    // * Method adding the properties to the database.
    protected function addCustomerToDB($customer_firstname, $customer_lastname, $customer_address, $customer_country) {
        
        // Connecting to database. 
        $connection = parent::connect();

        // Sanitizing the properties, calling the cleanVar function. 
        $customer_firstname = parent::cleanVar($customer_firstname, $connection);
        $customer_lastname = parent::cleanVar($customer_lastname, $connection);
        $customer_address = parent::cleanVar($customer_address, $connection);
        $customer_country = parent::cleanVar($customer_country, $connection);

        // Query the database.
        $query = "INSERT INTO customers(firstname, lastname, address, country) ";
        $query .= "VALUES ('$customer_firstname','$customer_lastname','$customer_address','$customer_country');";

        $result = mysqli_query($connection, $query);
        $this->customer_id = mysqli_insert_id($connection);

        // Printing error message in case of query failure.
        if (!$result){
            die ("Adding customer failed!" . mysqli_error($connection));
        } else {
            echo "New customer added!<br>"; 
        }
        
        // Disconnect from database. 
        parent::disconnect($connection);
    }

    // * Method to retrieve out the customer_id. 
    function fetchCustomerId () {
        return $this->customer_id;
    }
}

?>