<?php

session_start();

if (!isset($_SESSION['userRole']) || !isset($_SESSION['customerID'])) {
    header('Location: view/login.php');
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet preload" as="style" href="css/Variables.css" crossorigin="anonymous" type="text/css">
    <link rel="stylesheet" href="css/general.css">
    <title>Music Shop</title>
</head>
<body>
    <header>
        <div>
            <h1>Music Shop</h1>
        </div>
        <div>
            <div>

            </div>
            <div>

            </div>
            <div>
                
            </div>
            <div>
                <form action="view/profile.php" method="POST">
                    <input type="submit" value="My Profile">
                </form>
            </div>
            <div>
                <form action="view/login.php" method="POST">
                    <input type="hidden" name="logout" value="logout">
                    <input id="btnLogOut" type="submit" value="Log out">
                </form>
            </div>
        </div>
    </header>
    <main>

    </main>
</body>
</html>