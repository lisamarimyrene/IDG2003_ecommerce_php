# E-commerce project by Lisa Mari Myrene – IDG2003

## About the project
This project is about a basic e-commerce platform which allows potential 
customers to view and buy products listed. There will also be a function 
to login as customer or admin. If the user is logged in as admin, there 
will be a possibility to add new-, and delete products to the database.  

## How it works

### Main page
The first page you arrive to, is the Homepage. Here you will see all the 
current products that are available to buy. If you click on one of the displayed
products, you will be redirected to a new page, 'productPage.php'. 

### Product page
There will be a functionality that retrieves the clicked product from the 
main page, and you will therefore be displayed the chosen product, with its 
belonging information. So inside each product clicked, you will see further 
details, such as:

* product_id
* product name
* description of product
* price
* image of the product

On this page, you will be able to add the product to your shopping cart. 
You can also select the quantity you want of the chosen product. When the user
adds a product to the shopping cart, it will push an array to a JSON file 
(where one product is an object), that will hold the information about 
all the products the user has added to the shopping cart. If the customer
adds the same product several times to the cart, then the new quantity will add 
to the current quantity. When the user adds an item to the shopping cart, 
there will be an added cookie, that will remember the items in the shopping cart
for about one week. 

### Shopping cart
In the shopping cart, you will see all your added products. You will also
see the total price for the products added. The customer will also be able
to delete a product if needed. When the customer are ready to check out, there
will be two options. Check out as guest, or check out as user. The different
options have some different functionalities:

#### Check out as guest:
I you check out as guest, there will pop-up a form, where you'll need to add
your firstname, last name, address, and country. Then you can confirm checkout. 

#### Check out as user:
If you click 'checkout as user', then you will be redirected to the login page.
Here you can login or register yourself as a new user. After you're logged in, 
you can then go back to the shopping cart, and the page will then do a check on 
the user.

* If the user doesn't have customer_id (meaning that the user haven't bought 
something in the past), then the user will need to fill out the same form, 
containing the user's first name, last name, address, and country. When the 
user confirms the order, the database will now add a customer_id to the user. 

* If the the user already has an customer_id (meaning that the user has bought 
something in the past), then the user only need to click the 'confirm checkout'- 
button, and the order will be sent to the database. There will be no need to fill 
out a form, since the database already have the saved information about the user
from the last time it went shopping.  

When the user confirm the order, both the cookie and JSON-file will be deleted. 
The cart is now empty again. 

❗Note: You will be able to 'check out' even tho there aren't products in the shopping cart.

### Login page
There will be two options in the login page; to login as an existing user, or 
register as a new user. To sign up, you will need to enter a username, email, 
and password. When you create a new user, the password will be hashed to the 
database, for safity reasons. When you log in, there will be a functionality to 
check if the username and password are correct, before you can login. If the 
username or password is incorrect, you will get a message about it. To make sure 
there always will be an admin user in the database, I made a function that pushes 
an admin user-query to the database if there isn't already an exsiting one. When 
you click login, you will beredirected to the homepage. 

If you log in as the admin user, the 'admin page' will then be visible in the 
navigaiton bar. If you login as a customer, then the 'admin page' will not 
be visible. I do this by chekcing the role for the logged in user. The admin 
has the role of '0', while all the other users has the role of '1'.  

### Admin page
In the admin page, you will see an overview of all the current products in the 
database. The admin user will have two options on this page, which is to:

* Add a new product to the database
* Delete an excisting product

There will also be an overview of the current orders.❗Note: I did not implement 
the function to sort the orders based on country, because I didn't have time.

### Deliverables
A zip-file containing:
* Project folder with all the files and assets
* The exported database 'ecommerce'
* README file
# IDG2003_ecommerce_php
