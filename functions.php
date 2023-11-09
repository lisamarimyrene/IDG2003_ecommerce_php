<?php

// * Function making the navigation bar. 
function displayNavBar () {

    //Retrieving the json file.
    $json = 'shoppingCart.json';
    $assArray = json_decode(file_get_contents($json), true);
    $countArrays = count($assArray);

    // * If the user is logged in, then display this navigation bar. 
    if($_SESSION['isloggedin']) {
        echo "
            <nav>
            <a href='mainPage.php'>Home </a>
            <a href='shoppingCart.php'>Shopping cart ($countArrays) </a>";

        // Function checking if the user is admin or not.
        displayAdminPage();

        echo "
            <a href='loginPage.php'>Logout </a>
            </nav>
            <br><br>";
    } 
    
    //If not, display this navigation bar. 
    else {
        echo <<<END
            <nav>
                <a href='mainPage.php'>Home </a>
                <a href='shoppingCart.php'>Shopping cart($countArrays) </a>
                <a href='loginPage.php'>Login </a>
            </nav>
        END;
        echo "<br><br>";
    }
}

// * Function that checks if user is logged in, and role is 0 (admin).
function displayAdminPage () {
    if($_SESSION['isloggedin'] && $_SESSION['role'] == 0) {
        echo "<a href='adminPage.php'>Admin page </a>";
    }
}

// * Function to read file and return headers and entries.
function readThisFile($filename){
    //echo "In readThisFile <br>";

    //Open file
    $file = fopen($filename, "r") or die("Unable to open file");

    //Output one line until end-of-file.
    $idx = 0;
    while(!feof($file)){

        if ($idx==0){
            $headersArray = fgetcsv($file);

        }else{
            $line = fgetcsv($file);

            if(!(is_null($line[1]))){
                $valuesArray[$idx-1] = $line;
            }
        }
        $idx++;
    }

    //Close file
    fclose($file);

    return array('headersArray' => $headersArray, 'valuesArray' => $valuesArray);
}

// * Function that creates a 2 dimensional associative array, given a headers array and a values array. 
function createAssocArray($headersArray, $valuesArray){
   
    //Create an associative array given headers and values.
    foreach ($valuesArray as $item => $value){
        $idx = 0;
        foreach ($headersArray as $key){
            $resArray[$item][$key] = $value[$idx];         
            $idx++;
        }
    }
    return $resArray;
}

// * Take an associative array and the current page-url as input, and creates a table from it. 
function createTable($resArray) {

    //Saying that the page is equal to the current path. 
    $currentUrl = $_SERVER['REQUEST_URI']; 

    // * If the word 'adminPage', 'productPage', or 'shoppingCart' is in the url, then make the following table. 
    if (strpos($currentUrl, 'adminPage') == true || strpos($currentUrl, 'productPage') == true || strpos($currentUrl, 'shoppingCart') == true) {
       
        echo "<table class=\"table\">";

        //Checks if the first row is false.
        $isFirstRow = FALSE;
        
        //For each product array as items.
        foreach ($resArray as $item){
            
            //If the first row is false, then make the table header.
            if ($isFirstRow == FALSE){
    
                // Firstly print headers
                echo "<tr>";
                foreach ($item as $key => $value){
                    echo "<th class=\"tableheader\"> $key </th>";
                }
                echo "</tr>";
                
                //T hen print the first row of values.
                echo "<tr>";

                // For each item inside the products array as keys (values).
                foreach ($item as $key => $value){
                    
                    /* If the key is the image name, then find the correct 
                    path from the file-name, to find the actual picture. */
                    if ($key === "Image") {
                        $imgPath = "data/$value";
                        echo "<td><img class=\"productimg\" src=\"$imgPath\"></td>";
                    
                    } 
                    else { //If the key is not image, att table data for the other keys.
                        echo "<td> $value </td>";
                    }
                }
                echo "</tr>";
            
                // Setting the first row to TRUE.
                $isFirstRow = TRUE;
                
            /* If the first row is TRUE(meaning it already exists), 
            then don't make the table headings, just make the next rable rows. */
            } else { 
                // Then print every subsequent row of values.
                echo "<tr>";
                foreach ($item as $key => $value){
                   
                    if ($key === "Image") {
                        $imgPath = "data/$value";
                        echo "<td><img class=\"productimg\" src=\"$imgPath\"></td>";
                    
                    } else {
                        echo "<td> $value </td>";
                    }
                }
                echo "</tr>";
            }    
        } 
        echo "</table>";
    }

    // * If the word 'mainPage' is in the link, then make the following table. 
    if (strpos($currentUrl, 'mainPage') == true) {

        echo "<table class=\"table tableMainPage\">";

        // Checks if the first row is false.
        $isFirstRow = FALSE;
        
        // For each product array as items.
        foreach ($resArray as $item){
            
            // If the first row is false, then make the table header.
            if ($isFirstRow == FALSE){
    
                // Firstly print headers.
                echo "<tr>";
                foreach ($item as $key => $value){
                    
                    //Check if the key is product_id, and skips it, so it won't display. 
                    if($key == 'ID') continue;
                    echo "<th class=\"tableheader\"> $key </th>";
                }
                echo "</tr>";
                
                // Then print the first row of values.
                echo "<tr>";
                // For each item inside the products array as keys (values).
                foreach ($item as $key => $value){                    
                    
                    /* If the key is the image name, then find the correct 
                    path from the file-name, to find the actual picture. */
                    if ($key === "Image") {
                        $imgPath = "data/$value";
                        echo "<td><img class=\"productimg\" src=\"$imgPath\"></td>";
                    
                    /* If the key is the product name, then add a link to the name, 
                    and add the belonging id to the url when you click the link. */
                    } if ($key === "Name") {
                        echo "<td><a href=productPage.php?id=" . $item['ID'] . ">$value</a></td>";
                    }    
                }
                echo "</tr>";
            
                // Setting the first row to TRUE.
                $isFirstRow = TRUE;
                
            /* If the first row is TRUE(meaning it already excists), 
            then don't make the table headings, just make the next rable rows. */
            } else { // Then print every subsequent row of values.
                echo "<tr>";
                foreach ($item as $key => $value){
                    if ($key === "Image") {
                        $imgPath = "data/$value";
                        echo "<td><img class=\"productimg\" src=\"$imgPath\"></td>";
                    
                    } if ($key === "Name") {
                        echo "<td><a href=productPage.php?id=" . $item['ID'] . ">$value</a></td>";
                    }   
                }
                echo "</tr>";
            }    
        } 
        echo "</table>";
    }  
}

// * Function to make sure no fields are empty (validating the input).
function validateInput($arr) {

    foreach($arr as $item) {
        if(empty($item)){
            echo "Fields are empty <br>";
            return false;
        }
    }
    return true;
}

// * Function adding new items/objects into the json file. 
function makeCartArray ($product_id, $quantity) {
    
    //echo "shopping cart items:" .  $quantity;

    // Put the file in a variable. 
    $json = 'shoppingCart.json';

    // The new item will contain an array with two keys; product_id and quantity. 
    $newItem = array("id" => $product_id, "quantity" => $quantity);

    // If the json file excists and it have content (not empty).
    if (file_exists("$json") && file_get_contents($json)) {

        // Create an assosicate array, that decodes the json content back to php.
        $assArray = json_decode(file_get_contents($json), true);

        foreach ($assArray as $key => $item) {
            if($item['id'] == $product_id) {
                $assArray[$key]['quantity'] += $quantity;
                $found = true;
            }
        }
        if (!$found){
            /* Push and adds the new item to the assosciate array. 
            I'm not giving the assArray[] an index, so therefore it 
            pushes after the last array index every time. */
            $assArray[] = $newItem;

        } //append a new item

        // Put the new array inside the json.php file, encoding it into json. 
        file_put_contents($json, json_encode($assArray)); 


    } else { 
    // If the json file is empty.
        
        // Encode the new array into json.
        $newItem = json_encode(array($newItem));

        // Put in inside the json.php file. 
        file_put_contents($json, $newItem);
    }

}

// * Function to display the login-form, with a button rederecting to the register-form.
function displayLoginForm() {

    // Check if the user is not logged in.
    if($_SESSION['isloggedin'] == FALSE) {

        // If the user click on the register form btn, display the register form instead. 
        if(isset($_POST['registerForm'])) {
            displayRegisterUserForm();
            return;
        }
    
        // Creating the login form.
        echo <<<END
        <h2 class="h2login">Login Form</h2>
            <form class="forms" method="POST">
                <table>
                    <tr>
                        <td>Username:</td>
                        <td>
                        <input type="text" name="username">
                        </td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td>
                        <input type="password" name="password">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        <input class="loginBtn" type="submit" name="login" value="Login">
                        </td>
                    </tr>
                </table>
            </form>

            <form class="forms" method="POST">
                <p>Not an user yet? Register here:</p>
                <button class="loginFormsBtns" type="submit" name="registerForm">Register form</button>
            </form>
        END;
    }

    // If the user is logged in, then only display the logout button.
    else {
        echo <<<END
            <form method="POST">
                <h2>Please click here to logout:</h2>
                <button class="logoutBtn" type="submit" name="logout">Logout</button>
            </form>
        END;
    }
    
    
}

// * Function to display the register-form, with a button rederecting to the login-form.
function displayRegisterUserForm() {

    // Creating the register form. 
    echo <<<END
        <h2 class="h2login">Register Form</h2>
            <form class="forms" method="POST">
                <table>
                    <tr>
                        <td>Username:</td>
                        <td>
                        <input type="text" name="username">
                        </td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>
                        <input type="text" name="email">
                        </td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td>
                        <input type="password" name="password">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        <input class="registerBtn" type="submit" name="registerUser" value="Register">
                        </td>
                    </tr>
                </table>
            </form>

            <form class="forms" method="POST">
                <p>Already an user? Login here:</p>
                <button class="loginFormsBtns" type="submit" name="loginForm">Login form</button>
            </form>
        END;

}

// * Creating the submit-order form. 
function submitOrderForm () {

    // Creating the submit-order form. 
    echo <<<END
        <h2 id="h2SubmitOrder">Submit order</h2><br>
        <form method="POST" class="forms" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Firstname:</td>
                    <td>
                        <input type="text" name="customer_firstname">
                    </td>
                </tr>
                <tr>
                    <td>Lastname:</td>
                    <td>
                        <input type="text" name="customer_lastname">
                    </td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td>
                    <input type="text" name="customer_address">
                    </td>
                </tr>
                <tr>
                    <td>Country:</td>
                    <td>
                    <input type="text" name="customer_country">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="confirmCOBtn" type="submit" name="confirmCheckout" value="Confirm checkout">
                    </td>
                </tr>
            </table>
        </form>
    END;
}

// * Function for the second confirm-checkout btn. I needed two seperate for the if-statements to work.
function confirmCheckoutBtn2 () {
    echo
    <<<END
        <form method="POST">
            <button class="confirmCOBtn" type="submit" name="confirmCheckout2">Confirm checkout</button>
        </form>
    END;
}

// * Function that push the checkout to the datbase, when you are checking out as guest. 
function guestCheckout() {

    //Extract all the POST data from the form, and placing it into variables.
    $customer_firstname = $_POST['customer_firstname'];
    $customer_lastname = $_POST['customer_lastname'];
    $customer_address = $_POST['customer_address'];
    $customer_country = $_POST['customer_country'];

    //Calling the validateInput function, to check if fields are empty.
    if(validateInput(array($customer_firstname, $customer_lastname, $customer_address, $customer_country))){

        //Catch the customer_id from the customer database. 
        $newCustomer = new Customer($customer_firstname, $customer_lastname, $customer_address, $customer_country);

        //Set the customer_id, retrieved it from the customer. 
        $customer_id = $newCustomer->fetchCustomerId();

        $json = 'shoppingCart.json';
        $data = file_get_contents($json);
        $shoppingCartArray = json_decode($data, true);

        foreach ($shoppingCartArray as $item) {
            $product_id = $item['id'];
            $order_quantity = $item['quantity'];
            $time = time();

            //Push order to the DB, using the Order class. 
            $newOrder = new Order($customer_id, $product_id, $time, $order_quantity);
        }
    }
}

// * Function that push the checkout to the datbase, when you are checking out as an user without customer_id. 
function noCustomerIdCheckout () {

    //Extract all the POST data from the form, and placing it into variables.
    $customer_firstname = $_POST['customer_firstname'];
    $customer_lastname = $_POST['customer_lastname'];
    $customer_address = $_POST['customer_address'];
    $customer_country = $_POST['customer_country'];

    //Calling the validateInput function, to check if fields are empty.
    if(validateInput(array($customer_firstname, $customer_lastname, $customer_address, $customer_country))) {

        //Catch the customer_id from the customer database. 
        $newCustomer = new Customer($customer_firstname, $customer_lastname, $customer_address, $customer_country);

        $customer_id = $newCustomer->fetchCustomerId();
        User::updateUser($customer_id, $_SESSION['username']);

        $json = 'shoppingCart.json';
        $data = file_get_contents($json);
        $shoppingCartArray = json_decode($data, true);

        foreach ($shoppingCartArray as $item) {
            $product_id = $item['id'];
            $order_quantity = $item['quantity'];
            $time = time();

            //Push order to the DB, using the Order class. 
            $newOrder = new Order($customer_id, $product_id, $time, $order_quantity);
        }
    }
}

// * Function that push the checkout to the datbase, when you are checking out as an user with an excisting customer_id.  
function hasCustomerIdCheckout () {

    $customer_id = $_SESSION['customer_id'];

    $json = 'shoppingCart.json';
    $data = file_get_contents($json);
    $shoppingCartArray = json_decode($data, true);

    foreach ($shoppingCartArray as $item) {
        $product_id = $item['id'];
        $order_quantity = $item['quantity'];
        $time = time();

        //Push order to the DB, using the Order class.
        $newOrder = new Order($customer_id, $product_id, $time, $order_quantity);
    }
}

// * Function that deletes everything inside the json file (shopping cart).
function delShoppingCart () {

    // Deleting everything inside the json file.
    file_put_contents('shoppingCart.json', ' ');

    // Deleting cookie. 
    unset($_COOKIE['cart']);
    setcookie('cart', 1, time() - 3600, '/');
}