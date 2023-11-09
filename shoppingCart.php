<?php
session_start();
//print_r($_SESSION);

include "functions.php";
include "./classes/class_Product.php";
include "./classes/class_Customer.php";
include "./classes/class_Order.php";
include "./classes/class_User.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecommerce - Shopping Cart </title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
<?php 
    // * Display navigation bar.
    displayNavBar();

    // * Display the items in the shoppingcart
    echo "<h2>Shopping cart:</h2><br>";

    //set det inn i en if, for å sjekke om cookie er set
    $json = 'shoppingCart.json';
    $data = file_get_contents($json);
    $shoppingcartArray = json_decode($data, true);

    // * Create a table with the items in the json file
    $idx = 0;
    $totalPrice = 0;
    foreach ($shoppingcartArray as $item) {

        $displayArray[$idx]['ID'] = $item['id'];
        $displayArray[$idx]['Quantity'] = $item['quantity'];
        
        $productsArray = Product::readProductsToCart($item['id']);

        $displayArray[$idx]['Product Name'] = $productsArray['product_name'];
        $displayArray[$idx]['Description'] = $productsArray['description'];
        $displayArray[$idx]['Price'] = $productsArray['price'];
        $displayArray[$idx]['Image'] = $productsArray['image_name'];

        $totalPrice += $productsArray['price'] * $item['quantity'];

        $idx++; 
    }
    // * Creating table, calling it from the functions.php.
    createTable($displayArray); 

    //* Display total price. 
    echo "<h3 class=\"h3unique\">Total price: $$totalPrice</h3><br>";

    ?>
    
    <!-- A form to delete an item in the shopping cart. -->
    <form class="forms" method="POST">
        <h2 class="h2DeleteItem">Delete item from shopping cart:</h2><br>
        
        <select name="deleteId" id="">
            <?php
                //Loop through each item.
                foreach ($shoppingcartArray as $key => $item) {
                    $id = $item['id'];
                    echo "<option name=" . $id . " value=\"$key\">";
                    echo $id . "</option>";
                }
            ?>
        </select>
        <input type="submit" name="deleteItem" value="Delete item"><br><br><br>

    </form>

    <?php

    // * Add functionality to delete a product from shopping cart.
    if (isset($_POST['deleteItem'])) {
        print_r($shoppingcartArray);

        unset($shoppingcartArray[$_POST['deleteId']]);
        file_put_contents($json, json_encode($shoppingcartArray));

        header('Location: shoppingCart.php');
    }

    // * Check if logged in. If not logged in, display: checkoutUserBtn -> Go to login page.
    if($_SESSION['isloggedin'] != 1) {
        
        // Show the 'checkoutUserBtn'.
        echo 
        <<<END
            <form method="POST">
            <button class="checkoutBtns" type="submit" name="checkoutUserBtn">Checkout as user</button>
            </form><br>
        END;

        // Show the 'checkoutGuestBtn'.
        echo
        <<<END
            <form method="POST">
                <button class="checkoutBtns guest" type="submit" name="checkoutGuestBtn">Checkout as guest</button>
            </form>
        END;

        // * Continue as user and click the 'checkoutUserBtn':
        if(isset($_POST['checkoutUserBtn'])) {
            //Redirect to the login page.
            header('Location: loginPage.php');
        }

        //* Continue as guest and click the 'checkoutGuestBtn':
        if(isset($_POST['checkoutGuestBtn'])) {
            //Display the 'submitOrderForm'.
            submitOrderForm();
        }

        // * User clicks the 'confirmCheckout' button:
        if(isset($_POST['confirmCheckout'])) {
            //Display the 'guestCheckout' form.
            guestCheckout();
            
            //Delete everything inside the shoppingcart.
            delShoppingCart();

            header('Location: shoppingCart.php');
        }
    }

    // * If the user is logged in, and doesn't have customer_id:
    if($_SESSION['isloggedin'] == 1 && $_SESSION['customer_id'] == null) {

        //Display the whole checkout form
        submitOrderForm();

        //Push otder to DB
        if(isset($_POST['confirmCheckout'])) {
            noCustomerIdCheckout();

            //Delete everything inside the shoppingcart.
            delShoppingCart();

            header('Location: shoppingCart.php');
        }  
    } 

    // * If the user is logged in, and has customer_id:
    if($_SESSION['isloggedin'] == 1 && $_SESSION['customer_id']) {

        //Display only the (second)checkout button.
        confirmCheckoutBtn2();

        //Push otder to DB
        if(isset($_POST['confirmCheckout2'])) {
            hasCustomerIdCheckout();

            //Delete everything inside the shoppingcart.
            delShoppingCart();

            header('Location: shoppingCart.php');
        }
    }
?>
<!-- <footer>
    <p>IDG2003 Back-End 2022 – Lisa Mari Myrene</p>
</footer> -->

</body>
</html>
