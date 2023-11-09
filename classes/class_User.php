<?php
require_once "class_Database.php";

//echo "In class: User<br>";

// * PUser class, that extends the Database class. Add new user functionality.
class User extends Database {

    // Set properties
    protected $username;
    protected $email;
    protected $password;
    protected $role;

    // * A construct-function/method to make it run right away when you create an object.
    public function __construct($username, $email, $password) {
        $this->setValues($username, $email, $password);
        $this->addUserToDB($this->username, $this->email, $this->password);
    }
    
    // * Method to set the values for the properties. 
    public function setValues($username, $email, $password) {
        
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;

        // Check if the user has role 0 or 1, by reading from the users table. 
        $userArr = self::readUsersTable();
        if ($userArr) {
            $this->role = 1;

        } else {
            $this->role = 0;
        }
    }

    // * Method adding the properties to the database.
    protected function addUserToDB($username, $email, $password) {
        
        // Connecting to database. 
        $connection = parent::connect();

        // Sanitizing the properties, calling the cleanVar function. 
        $username = parent::cleanVar($username, $connection);
        $email = parent::cleanVar($email, $connection);

        // Hash password
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    
        // Query the database.
        $query = "INSERT INTO users(username, email, password, role) ";
        $query .= "VALUES ('$username','$email','$password_hashed','$this->role');";

        $result = mysqli_query($connection, $query);
        $this->customer_id = mysqli_insert_id($connection);

        // Printing error message in case of query failure.
        if (!$result){
            die ("Adding user failed!" . mysqli_error($connection));
        } else {
            //echo "New user added!<br>"; 
        }
        
        // Disconnect from database. 
        parent::disconnect($connection);
    }

    // * Method that reads from the products table.  
    static function readUsersTable(){
        $orderArray = Database::readFromTable('users');

        // Returning the array
        return $orderArray;
    }

    // * Method that adds an admin user automatically to the database if there are none. 
    static function addAdminUser (){

        // Connecting to database. 
        $connection = parent::connect();
    
        $query = "SELECT username FROM users WHERE username=admin";
        $result = mysqli_query($connection, $query);
           
        //There are no user, add admin user to the database,  
        if(!$result) {

            // Set the password to '0admin0'
            $password = '0admin0';
            
            // Hash password
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users(username, email, password, customer_id, role) ";
            $query .= "VALUES ('admin', 'admin@ecommerce.com', '$password_hashed', NULL, '0')";

            $result = mysqli_query($connection, $query);
        }
        
        // Disconnect from database. 
        parent::disconnect($connection);
    }

    // * Method to make sure the password and username matches when logging in.
    static function loginUser($username, $password){
        
        // Connecting to database.
        $connection = parent::connect();
        
        $doesPasswordsMatch = FALSE;
        $userdata = array();
        
        // Sanitize the input
        $username_cleaned = parent::cleanVar($username, $connection);
        
        // Making the query to be sent to database
        $query = "SELECT * FROM users";
        $query .= " WHERE username = '$username_cleaned' "; 
        
        $result = mysqli_query($connection, $query);
        
        // Printing error message in case of query failure
        if(!$result){
            die('Query failed!' . mysqli_error($connection));
        }else {  
            $row = mysqli_fetch_assoc($result);
            $user_password = $row['password'];
            
            // Check if the password is correct
            if(password_verify($password, $user_password)){
                $doesPasswordsMatch = TRUE;
                $userdata['username'] = $row['username'];
                $userdata['email'] = $row['email'];
                $userdata['role'] = $row['role'];   
                $userdata['customer_id'] = $row['customer_id'];
            }
        }
        // Disconnect to database
        parent::disconnect($connection);

        // Return array
        return array("isLoggedIn" => $doesPasswordsMatch, "userdata" => $userdata);
    }

    // * Method that updates the customer_id to the user. 
    static function updateUser($customer_id, $username) {
        
        // Connecting to database.
        $connection = parent::connect();
        
        // Making the query to be sent to database
        $query = "UPDATE users SET customer_id = '$customer_id' WHERE username = '$username'; ";

        $result = mysqli_query($connection, $query);

        // Disconnect to database
        parent::disconnect($connection);
    }

}

?>