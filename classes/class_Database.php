<?php 
// Superclass definition: Database
//echo "In class: Database<br>";

// * Class Database, connecting to the database. 
class Database{
    
    // * Method to connect to the database.
    protected function connect(){
        //echo "Database: connect<br>";
        
        $host = 'localhost';
        $username = 'root';
        $password = 'root';
        $database = 'ecommerce';

        $connection = mysqli_connect($host,$username,$password,$database);

        //Checks if we are connected or not
        if($connection){
            //echo "We are connected!<br>";
        }else {
            die ("Database connection failed");
        }
        return $connection;
    }
    
    // * Method to disconnect to the database.
    protected function disconnect($connection){
        mysqli_close($connection);
    }
    
    // * Method that read from a table.
    protected function readFromTable($tableName){
        //echo "Database:readFromTable<br>";

        // Connecting to the database.
        $connection = Database::connect();

        // Query the database
        $query = "SELECT * FROM $tableName";

        $result = mysqli_query($connection, $query);

        // Printing error message in case of query failure
        if(!$result){
            die('Query failed!' . mysqli_error($connection));
        }else {
            //echo "Entries Retrieved!<br>";
        }

        // Read 1 row at a time
        $idx = 0;
        while($row=mysqli_fetch_assoc($result)){
            $resArray[$idx] = $row;
            $idx++;
        }

        // Disconnect from the database
        Database::disconnect($connection);
        
        // Returning the associated array
        return $resArray;

    }
    
    // * Method to sanitize the inputs.
    protected function cleanVar($var, $connection){   
        $var = htmlentities($var);
        $var = mysqli_real_escape_string($connection, $var);
        return $var;
    } 
}

?>