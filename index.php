<?php

session_start();

if (!isset($_SESSION['userRole']) || !isset($_SESSION['customerID'])) {
    header('Location: view/login.php');
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


if (isset($_POST['trackId'])) {
    $_SESSION['cart'][$_POST['trackId']] = array('trackId' => $_POST['trackId'], 'trackName' => $_POST['trackName'], 'albumTitle' => $_POST['albumTitle'], 'mediaType' => $_POST['mediaType'], 'genre' => $_POST['genre'], 'price' => $_POST['price'], 'quantity' => $_POST['quantity']);
}

$itemsInCart = count($_SESSION['cart']);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" defer></script>
    <script src="js/customer.js" defer></script>
    <link rel="stylesheet preload" as="style" href="css/Variables.css" crossorigin="anonymous" type="text/css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/customer.css">
    <title>Music Shop</title>
</head>
<body>
    <header>
        <div>
            <h1>Music Shop</h1>
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
                <form action="view/profile.php" method="POST">
                    <input type="submit" value="My Profile">
                </form>
            </div>
            <div>
                <form action="view/cart.php">
                    <input type="submit" value="My cart">
                    <input type="number" name="itemsInCart" value="<?php echo $itemsInCart ?>" disabled>
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
        <section>
            <header>
                <h2 id="displayedEntity"></h2>
                <input type="hidden" value="">
                <div>
                    <form id="formSearch">
                        <input id="searchText" type="text" placeholder="Enter searchtext">
                        <select name="genre" id="selectGenre">
                            <option selected value="all">All Genres</option>
                            <option value="1">Rock</option>
                            <option value="2">Jazz</option>
                            <option value="3">Metal</option>
                            <option value="4">Alternative & Punk</option>
                            <option value="5">Rock And Roll</option>
                            <option value="6">Blues</option>
                            <option value="7">Latin</option>
                            <option value="8">Reggae</option>
                            <option value="9">Pop</option>
                            <option value="10">Soundtrack</option>
                            <option value="11">Bossa Nova</option>
                            <option value="12">Easy Listening</option>
                            <option value="13">Heavy Metal</option>
                            <option value="14">R&B/Soul</option>
                            <option value="15">Electronica/Dance</option>
                            <option value="16">World</option>
                            <option value="17">Hip Hop/Rap</option>
                            <option value="18">Science Fiction</option>
                            <option value="19">TV Shows</option>
                            <option value="20">Sci Fi & Fantasy</option>
                            <option value="21">Drama</option>
                            <option value="22">Comedy</option>
                            <option value="23">Alternative</option>
                            <option value="24">Classical</option>
                            <option value="25">Opera</option>
                        </select>
                        <select name="mediaType" id="selectMediaType">
                            <option selected value="all">All Mediatypes</option>
                            <option value="1">MPEG audio file</option>
                            <option value="2">Protected AAC audio file</option>
                            <option value="3">Protected MPEG-4 video file</option>
                            <option value="4">Purchased AAC audio file</option>
                            <option value="5">AAC audio file</option>
                        </select>
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
</body>
</html>