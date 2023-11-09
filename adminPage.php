<?php
session_start();

//print_r($_SESSION);

include "functions.php";
include "./classes/class_Product.php";
include "./classes/class_Order.php";

//* Add functionality to create a new product.
if (isset($_POST['addProduct'])) {

    // Extract all the POST data from the form, and placing it into variables.
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Upload- and place images to the 'data' folder.
    $product_img = $_FILES['product_img']['name'];
    // Specifying the folder path.
    $folderPath = "./data/" . $product_img;
    move_uploaded_file($_FILES['product_img']['tmp_name'], $folderPath);

    // Calling the 'validateInput' function, to check if fields are empty.
    if(validateInput(array($product_name, $description, $price, $product_img))){
        
        // Adding a new product to the database, using the Product class.
        $newProduct = new Product($product_name, $product_img, $description, $price);
    }
}

//*  Add functionality to delete an existing product.
if (isset($_POST['deleteEntry'])) {

    //Extract the POST data and placing it into a variable.
    $product_id = $_POST['product_id'];

    //Using the Product class to delete the selected product id. 
    Product::deleteOrder($product_id);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ecommerce - Admin Area</title>
    <link rel="stylesheet" href="./styles.css" type="text/css">
</head>

<body>

<?php

    // * Displaying navigation bar.
    displayNavBar();

    echo "<h2>Current products:</h2><br>";

    // * Reading the products table from the Product Class function. 
    $productsArray = Product::readProductsTable();
    //print_r($ordersArray);
   
    $idx = 0;
    // * Creating table from each product.
    foreach ($productsArray as $item) {

        //Product details
        $displayArray[$idx]['ID'] = $item['product_id'];
        $displayArray[$idx]['Product Name'] = $item['product_name'];
        $displayArray[$idx]['Description'] = $item['description'];
        $displayArray[$idx]['Price'] = $item['price'];
        $displayArray[$idx]['Image'] = $item['image_name'];

        $idx++;
    }

    // * Creating table, calling it from the functions.php.
    createTable($displayArray);

?>

    <!-- A form to create a new product. -->
    <h2>Add new product:</h2><br>
    <form class="forms" method="POST" action="" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Product name:</td>
                <td>
                    <input type="text" name="product_name">
                </td>
            </tr>
            <tr>
                <td>Description:</td>
                <td>
                    <input type="description" name="description">
                </td>
            </tr>
            <tr>
                <td>Price:</td>
                <td>
                    <input type="number" name="price">
                </td>
            </tr>
            <tr>
                <td>Add image:</td>
                <td>
                    <input type="file" name="product_img" accept="image/*">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="addProduct" value="Add product">
                </td>
            </tr>
        </table>
    </form>

    <!-- A form to delete a product. -->
    <form class="forms" method="POST">
        <h2 class="h2DeleteItem">Delete Product with product ID:</h2><br>
        
        <select name="product_id" id="">
            <?php
            //Loop through each item.
            foreach ($productsArray as $item) {
                $id = $item['product_id'];
                echo "<option name=" . $id . ">";
                echo $id . "</option>";
            }
            ?>
        </select>
        <input type="hidden" name="formID" value="2">
        <input type="submit" name="deleteEntry" value="Delete product"><br><br>

    </form>

<?php

    echo "<h2>Current orders:</h2><br>";

    // * Reading the products table from the Product Class function. 
    $orderArray = Order::readOrdersTable();
    //print_r($ordersArray);
   
    $idx = 0;
    // * Creating table from each product.
    foreach ($orderArray as $item) {

        // Product details
        $displayOrderArray[$idx]['Order ID'] = $item['order_id'];
        $displayOrderArray[$idx]['Customer ID'] = $item['customer_id'];
        $displayOrderArray[$idx]['Product ID'] = $item['product_id'];
        $displayOrderArray[$idx]['Time'] = $item['time'];
        $displayOrderArray[$idx]['Quantity'] = $item['quantity'];

        $idx++;
    }

    // * Creating table, calling it from the functions.php.
    createTable($displayOrderArray);

?>

<!-- <footer>
    <p>IDG2003 Back-End 2022 – Lisa Mari Myrene</p>
</footer> -->

</body>

</html>