<?php

require_once('../src/functions.php');

session_start();

if (!isset($_SESSION['userRole'])) {
    header('Location: ../login.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" defer></script>
    <script src="../js/adminHub.js" defer></script>
    <title>Music Admin</title>
</head>
<body>
    
<header>
    <div>
        <h1>Music Admin</h1>
    </div>
    <div>
        <div>
            <input id="btnArtists" type="button" value="Artists">
        </div>
        <div>
            <input id="btnAlbums" type="button" value="Albums">
        </div>
        <div>
            <input id="btnTracks" type="button" value="Tracks">
        </div>
        <div>
            <form action="../view/login.php" method="POST">
                <input type="hidden" name="logout" value="logout">
                <input id="btnLogOut" type="submit" value="Log out">
            </form>
        </div>
    </div>
</header>

<main>
    <section>

    </section>
</main>

<?php
include_once('../footer.html');
?>

</body>
</html>