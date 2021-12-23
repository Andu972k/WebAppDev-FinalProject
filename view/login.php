<?php
    require_once('../src/functions.php');
    
    session_set_cookie_params(0, '/', $_SERVER['SERVER_NAME'], true, true);
    session_start();

    require_once('../security/csrf_token_functions.php');

    $customerValidation = false;
    //If set then the customer is logged out
    if (isset($_POST['logout'])) {
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 86400, 
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']);
            session_unset();
            session_destroy();
        }
    }
    else if (isset($_SESSION['customerID'])) {
        header('Location: ../index.php');
    }
    else if (isset($_SESSION['userRole'])) {
        header('Location: ../admin/adminHub.php');
    }
    else if (isset($_POST['email'])) {
        
        $customerValidation = true;

        $email = $_POST['email'];
        $password = $_POST['password'];

        if (strtolower($email) === 'admin') {
            require_once('../src/admin.php');
            $admin = new admin();
            $validAdmin = $admin->validate($password);
            if ($validAdmin) {
                session_set_cookie_params(0, '/', $_SERVER['SERVER_NAME'], true, true);
                session_start();
                session_regenerate_id();
                $_SESSION['userRole'] = $admin->userName;

                header('Location: ../admin/adminHub.php');
            }
        }
        else {
            require_once('../src/customer.php');
            $customer = new Customer();
            $validCustomer = $customer->validate($email, $password);
            if ($validCustomer) {
                session_set_cookie_params(0, '/', $_SERVER['SERVER_NAME'], true, true);
                session_start();
                session_regenerate_id();
                
                $_SESSION['customerID'] = $customer->customerID;
                $_SESSION['userRole'] = 'customer';
                $_SESSION['email'] = $customer->email;
                $_SESSION['firstName'] = $customer->firstName;
                $_SESSION['lastName'] = $customer->lastName;
                $_SESSION['company'] = $customer->company;
                $_SESSION['address'] = $customer->address;
                $_SESSION['city'] = $customer->city;
                $_SESSION['state'] = $customer->state;
                $_SESSION['country'] = $customer->country;
                $_SESSION['postalCode'] = $customer->postalCode;
                $_SESSION['phone'] = $customer->phone;
                $_SESSION['fax'] = $customer->fax;
                header('Location: ../index.php');
            }
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
    <link rel="stylesheet preload" as="style" href="../css/Variables.css" crossorigin="anonymous" type="text/css">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <header>
        <h1>Music shop</h1>
    </header>

    <?php
            if ($customerValidation && (!$validCustomer || !$validAdmin)) {
        ?>
        <div >
            Invalid email or password.
        </div>
        <?php            
            }
        ?>

    <main>
        <section>
            <div>
                <form id="signinForm" action="login.php" method="POST">
                    <fieldset>
                        <legend>Login</legend>
                        <?php echo csrf_token_tag() ?>
                        <div>
                            <label for="email">Email</label>
                            <input type="text" name="email" required>
                        </div>
                        <div>
                            <label for="password">Password</label>
                            <input type="password" name="password" required>
                        </div>
                        <input type="submit" id="btnLogin" value="Login">
                    </fieldset>
                </form>
                <div id="signup">
                    If you do not have a user, you can <a href="signup.php">sign up</a>
                </div>
            </div>
        </section>
    </main>

    <?php
    unset($admin);
    unset($customer);
    include_once('../footer.html');
    ?>
    
</body>
</html>