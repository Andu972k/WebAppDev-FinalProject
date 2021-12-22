<?php
session_start();

if (!isset($_SESSION['customerID'])) {
    header('Location: login.php');
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" defer></script>
    <script src="../js/profile.js" defer></script>
    <link rel="stylesheet preload" as="style" href="../css/Variables.css" crossorigin="anonymous" type="text/css">
    <link rel="stylesheet" href="../css/general.css">
    <title>Customer Profile</title>
</head>
<body>
    <header>
        <h1>
            Edit information
        </h1>
    </header>
    <main>
        <section>
            <form id="formEditProfile">
                <input id="customerId" type="hidden" value="<?php echo $_SESSION['customerID']; ?>">
                <label for="txtFirstName">First Name</label>
                <input type="text" id="txtFirstName" name="FirstName" value="<?php echo $_SESSION['firstName']; ?>" required>
                <br>
                <label for="txtLastName">Last Name</label>
                <input type="text" id="txtLastName" name="LastName" value="<?php echo $_SESSION['lastName']; ?>" required>
                <br>
                <label for="txtEmail">Email</label>
                <input type="text" id="txtEmail" name="Email" value="<?php echo $_SESSION['email']; ?>" required>
                <br>
                <label for="txtOldPassword">Old Password</label>
                <input id="txtOldPassword" type="password" value="" required>
                <br>
                <label for="txtPassword">Password</label>
                <input type="password" id="txtPassword" name="Password" value="">
                <br>
                <label for="txtCompany">Company</label>
                <input type="text" id="txtCompany" name="Company" value="<?php echo (isset($_SESSION['company']))?$_SESSION['company']:''; ?>">
                <br>
                <label for="txtAddress">Address</label>
                <input type="text" id="txtAddress" name="Address" value="<?php echo (isset($_SESSION['address']))?$_SESSION['address']:''; ?>">
                <br>
                <label for="txtCity">City</label>
                <input type="text" id="txtCity" name="City" value="<?php echo (isset($_SESSION['city']))?$_SESSION['city']:''; ?>">
                <br>
                <label for="txtState">State</label>
                <input type="text" id="txtState" name="State" value="<?php echo (isset($_SESSION['state']))?$_SESSION['state']:''; ?>">
                <br>
                <label for="txtCountry">Country</label>
                <input type="text" id="txtCountry" name="Country" value="<?php echo (isset($_SESSION['country']))?$_SESSION['country']:''; ?>">
                <br>
                <label for="txtPostalCode">PostalCode</label>
                <input type="text" id="txtPostalCode" name="PostalCode" value="<?php echo (isset($_SESSION['postalCode']))?$_SESSION['postalCode']:''; ?>">
                <br>
                <label for="txtPhone">Phone</label>
                <input type="text" id="txtPhone" name="Phone" value="<?php echo (isset($_SESSION['phone']))?$_SESSION['phone']:''; ?>">
                <br>
                <label for="txtFax">Fax</label>
                <input type="text" id="txtFax" name="Fax" value="<?php echo (isset($_SESSION['fax']))?$_SESSION['fax']:''; ?>">
                <br>
                <input type="submit" id="btnEditProfile" value="Confirm">
                <input type="button" id="btnEditProfileCancel" value="Cancel">
            </form>
            <form class="hidden" action="login.php" method="POST">
                <input type="hidden" name="logout" value="logout">
                <input id="btnLogOut" type="submit" value="Log out">
            </form>
        </section>
    </main>
</body>
</html>