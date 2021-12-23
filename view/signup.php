<?php

session_set_cookie_params(0, '/', $_SERVER['SERVER_NAME'], true, true);
session_start();

require_once('../security/csrf_token_functions.php');

$showForm = true;

die_on_csrf_token_failure();

    // New user
    if (isset($_POST['FirstName'])) {
        $showForm = false;

        require_once('../src/customer.php');

        $firstName = $_POST['FirstName'];
        $lastName = $_POST['LastName'];
        $email = $_POST['Email'];
        $password = $_POST['Password'];
        $company = $_POST['Company'] ?? '';
        $address = $_POST['Address'] ?? '';
        $city = $_POST['City'] ?? '';
        $state = $_POST['State'] ?? '';
        $country = $_POST['Country'] ?? '';
        $postalCode = $_POST['PostalCode'] ?? '';
        $phone = $_POST['Phone'] ?? '';
        $fax = $_POST['Fax'] ?? '';

        $customer = new Customer();
        $customerCreated = $customer->create($firstName, $lastName, $password, $company, $address, $city, $state, $country, $postalCode, $phone, $fax, $email);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" defer></script>
    <script src="../js/signup.js" defer></script>
    <link rel="stylesheet preload" as="style" href="../css/Variables.css" crossorigin="anonymous" type="text/css">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/signup.css">
    <title>Sign-up</title>
</head>
<body>

    <header>
        <h1>
            Music shop signup
        </h1>
    </header>
    <main>
        <?php
            if ($showForm) {
        ?>
        <form id="frmSignup" action="signup.php" method="POST">
            <fieldset>
            <legend>Sign up</legend>
                <label for="txtFirstName">First Name</label>
                <input type="text" id="txtFirstName" name="FirstName" required>
                <br>
                <label for="txtLastName">Last Name</label>
                <input type="text" id="txtLastName" name="LastName" required>
                <br>
                <label for="txtEmail">Email</label>
                <input type="text" id="txtEmail" name="Email" required>
                <br>
                <label for="txtPassword">Password</label>
                <input type="password" id="txtPassword" name="Password" required>
                <br>
                <label for="txtCompany">Company</label>
                <input type="text" id="txtCompany" name="Company">
                <br>
                <label for="txtAddress">Address</label>
                <input type="text" id="txtAddress" name="Address">
                <br>
                <label for="txtCity">City</label>
                <input type="text" id="txtCity" name="City">
                <br>
                <label for="txtState">State</label>
                <input type="text" id="txtState" name="State">
                <br>
                <label for="txtCountry">Country</label>
                <input type="text" id="txtCountry" name="Country">
                <br>
                <label for="txtPostalCode">PostalCode</label>
                <input type="text" id="txtPostalCode" name="PostalCode">
                <br>
                <label for="txtPhone">Phone</label>
                <input type="text" id="txtPhone" name="Phone">
                <br>
                <label for="txtFax">Fax</label>
                <input type="text" id="txtFax" name="Fax">
                <br>
                <?php echo csrf_token_tag() ?>
                <input type="submit" id="btnSignUp" value="Sign Up">
                <input type="button" id="btnSignUpCancel" value="Cancel">
            </fieldset>
        </form>
        <?php 
            } else {
                if ($customerCreated) {
        ?>
        <div>
            The user was successfully created.
            <br>
            <a href="login.php" alt="Login">Back</a>
        </div>
        <?php
                } else {
        ?>
        <div>
            A user registered with this email address already exists.
            <a href="signup.php" alt="Sign Up">Back</a>
        </div>
        <?php 
                }
            }
        ?>
    </main>
    
</body>
</html>