<?php
session_start();
//print_r($_SESSION);

include "functions.php";
include "./classes/class_Product.php";
include "./classes/class_User.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecommerce - Main Page </title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
<?php
    
    // * Displaying navigation bar.
    displayNavBar();

    // * Call the function from User class.
    User::addAdminUser();
    
    // * Reading the products table from the Product Class function. 
    $productsArray = Product::readProductsTable();

    $idx = 0;
    // * Creating table from each product.
    foreach ($productsArray as $item) {

        //Product details
        $displayArray[$idx]['ID'] = $item['product_id'];
        $displayArray[$idx]['Image'] = $item['image_name'];
        $displayArray[$idx]['Name'] = $item['product_name'];

        $idx++;
    }

    echo "<h2>Products available</h2>";
    
    // * Creating table, calling it from the functions.php.
    createTable($displayArray);
    
?>
<!-- <footer>
    <p>IDG2003 Back-End 2022 – Lisa Mari Myrene</p>
</footer> -->
    
</body>
</html>
    
    
    
    