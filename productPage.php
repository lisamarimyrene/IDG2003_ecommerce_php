<?php
session_start();
//print_r($_SESSION);

include "functions.php";
include "./classes/class_Product.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ecommerce - Product Page </title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <?php

    // * Displaying navigation bar.
    displayNavBar();

    // * Call the function from Product class.
    $productsArray = Product::readProductsTable();

    $idx = 0;
    foreach ($productsArray as $item) {

        if ($item['product_id'] == $_GET["id"]) {
            //Product details
            $displayArray[$idx]['ID'] = $item['product_id'];
            $displayArray[$idx]['Product Name'] = $item['product_name'];
            $displayArray[$idx]['Description'] = $item['description'];
            $displayArray[$idx]['Price'] = $item['price'];
            $displayArray[$idx]['Image'] = $item['image_name'];
        }
        $idx++;
    }

    echo "<h2 id=\"h2ProductInfo\">Product information</h2>";
    
    // * Creating table, calling it from the functions.php.
    createTable($displayArray);
?>

<h2 class="h2unique">Add product to shoppingcart</h2><br>
    <form  method="POST" class="forms" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Quantity:</td>
                <td>
                    <input type="number" name="quantity" max="50" min="1">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="addToCart" value="Add to cart">
                </td>
            </tr>
        </table>
    </form>

<?php
    //* Add functionality to create a new product.
    if (isset($_POST['addToCart'])) {

        makeCartArray($_GET['id'], $_POST['quantity']);

        $quantity = $_POST['quantity'];

        $cookieName = 'cart';
        setcookie($cookieName, 1, time() + (60*60*24*7));

        if(!isset($_COOKIE['cart'])) {
            //echo "Cookie named $cookieName is not set!";
        } 
        else {
            //echo "Cookie $cookieName is set!<br>";
            //echo "Value is: " . print_r($_COOKIE[$cookieName]);
        }

        // Redirect to shopping cart
        header('Location: shoppingCart.php');
    } 
?>
<!-- <footer>
    <p>IDG2003 Back-End 2022 – Lisa Mari Myrene</p> 
</footer> -->

</body>

</html>