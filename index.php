<?php

session_start();

if (!isset($_SESSION['userRole']) || !isset($_SESSION['customerID'])) {
    header('Location: view/login.php');
}
else {
    echo '<script>alert(<?php ' .$_SESSION['customerID'] . '?></script>)';
    echo '<h1>Logged in</h1>';
    echo $_SESSION['customerID'];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Index</h1>
</body>
</html>