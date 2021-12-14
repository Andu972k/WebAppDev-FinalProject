<?php

session_start();

if (!isset($_SESSION['customerID']) || !isset($_SESSION['adminLogin'])) {
    header('Location: view/login.php');
}
else {
    echo '<script>alert(<?php ' .$_SESSION['login'] . '?></script>)';
    echo '<h1>Logged in</h1>';
    echo $_SESSION['login'];
}


?>