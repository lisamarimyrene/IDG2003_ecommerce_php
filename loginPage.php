<?php
// * Start session
session_start();
//print_r($_SESSION);

include "functions.php";
include "./classes/class_User.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecommerce - Login </title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php 
  
    // * Display navigation bar.
    displayNavBar();

    // * Call the function from User class.
    User::addAdminUser();

    // * Display the Login Form. 
    displayLoginForm();

    // * When you click the 'login' button, check this:
    $isDataOk = FALSE;
    if(isset($_POST['login'])){

        // Call the validateInput function
        $isDataOk = validateInput($_POST);

        // If $isDataOk true
        if($isDataOk){
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            // Push username and password to loginUser function inside the User class
            $loginArray = User::loginUser($username, $password);

            // Check if the user is not logged in, and the inputs are wrong. 
            if($loginArray['isLoggedIn'] != 1) {
                echo 'Wrong username or password!';
            }
            
            // If the user is logged in 
            if($loginArray['isLoggedIn'] == 1){
                echo 'You are logged in!';
                
                $userdata = $loginArray['userdata'];

                print_r($userdata);
                echo "<br><br>";
                print_r($loginArray);
                
                // Set session parameters
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $userdata['email'];
                $_SESSION['role'] = $userdata['role'];
                $_SESSION['customer_id'] = $userdata['customer_id'];
                $_SESSION['isloggedin'] = TRUE;
                //Sikkerhet:
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
                
                // Redirect user to the mainPage. 
                header('Location: mainPage.php');
            }

        } else {
            echo "Data not valid!<br>";
        }
    }

    // * Make a new user when clicking the 'register user' button.
    if(isset($_POST['registerUser'])){

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $newUser = new User($username, $email, $password);
    }

    // * Destroy session when loging out.
    if (isset($_POST['logout'])) {
        
        // Unset all of the session variables.
        $_SESSION = array();

        // Delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        // Finally, destroy the session.
        session_destroy();

        // Redirect user to mainPage after logout
        header('Location: mainPage.php');
    }

?>
<!-- <footer>
    <p>IDG2003 Back-End 2022 – Lisa Mari Myrene</p>
</footer> -->
    
</body>
</html>