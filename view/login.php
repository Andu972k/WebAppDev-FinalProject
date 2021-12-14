<?php
    require_once('../src/functions.php');

    session_start();

    $customerValidation = false;
    //If set then the customer is logged out
    if (isset($_POST['logout'])) {
        session_destroy();
    }
    else if (isset($_SESSION['userID'])) {
        header('Location: ../index.php');
    }
    else if (isset($_POST['username'])) {
        $customerValidation = true;

        $username = $_POST['username'];
        $password = $_POST['password'];

        if (strtolower($username) === 'admin') {

        }
        else {
            
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.js" defer></script>
    <script src="js/script.js" defer></script>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <header>
        <h1>Music shop</h1>
    </header>

    <main>
        <section>
            <div>
                <form id="signinForm" method="POST">
                    <fieldset>
                        <legend>Login</legend>
                        <div>
                            <label for="userName">Username</label>
                            <input type="text" name="userName">
                        </div>
                        <div>
                            <label for="password">Password</label>
                            <input type="password" name="password">
                        </div>
                        <input type="submit" value="Login">
                    </fieldset>
                </form>
            </div>
        </section>
    </main>

    <footer>
        &copy; Anders Genderskov Binder
    </footer>
    
</body>
</html>