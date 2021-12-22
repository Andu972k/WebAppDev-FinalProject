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
    <link rel="stylesheet preload" as="style" href="../css/Variables.css" crossorigin="anonymous" type="text/css">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/adminHub.css">
    <title>Music Admin</title>
</head>
<body>
    
<header>
    <div>
        <h1>Music Admin</h1>
    </div>
    <div>
        <div>
            <input id="btnArtists" type="button" value="Artists" disabled>
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
        <header>
            <h2 id="displayedEntity"></h2>
            <input type="hidden" value="">
            <div>
                <input id="btnOpenArtistCreation" type="button" value="Create artist">
                <input id="btnOpenAlbumCreation" class="hidden" type="button" value="Create album">
                <input id="btnOpenTrackCreation" class="hidden" type="button" value="Create track">
                <form id="formSearch">
                    <input id="searchText" type="text" placeholder="Enter searchtext">
                    <input type="submit" value="Search">
                </form>
            </div>
        </header>
        <main id="mainOutput">

        </main>
        <div id="modal" class="modal">

        </div>
    </section>
</main>

<?php
include_once('../footer.html');
?>

</body>
</html>