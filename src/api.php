<?php 

define('POS_ENTITY', 1);
define('POS_ID', 2);

define('Max_PIECES', 3);

//Define API entities
define('ENTITY_ARTISTS', 'artists');
define('ENTITY_ALBUMS', 'albums');
define('ENTITY_TRACKS', 'tracks');

$url = strtok($_SERVER['REQUEST_URI'], '?');

if (substr($url, strlen($url) - 1) == '/') {
    $url = substr($url, 0, strlen($url) - 1);
}

$url = substr($url, strpos($url, basename(__DIR__)));

$urlPieces = explode('/', urldecode($url));

header('Content-Type: application/json');
header('Accept-version: v1');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: *');

$pieces = count($urlPieces);

if ($pieces > Max_PIECES) {
    echo 'Error';
}
else {
    $entity = $urlPieces[POS_ENTITY];

    switch ($entity) {
        case ENTITY_ARTISTS:
            require_once('artist.php');
            $artist = new Artist();

            $verb = $_SERVER['REQUEST_METHOD'];

            switch ($verb) {
                case 'GET':
                    if (isset($_GET['search-text'])) {
                        echo $artist->search($_GET['search-text']);
                    }
                    else if (isset($urlPieces[POS_ID])) {
                        echo $artist->getOne($urlPieces[POS_ID]);
                    }
                    else {
                        echo $artist->getAll();
                    }
                    break;
                case 'POST':
                    echo $artist->create($_POST['Name']);
                    break;
                case 'PUT':
                    $artistData = (array) json_decode(file_get_contents('php://input'), true);
                    echo $artist->update($urlPieces[POS_ID], $artistData['NewName']);
                    break;
                case 'DELETE':
                    echo $artist->delete($urlPieces[POS_ID]);
                    break;
                default:
                    echo 'Unknown/Unsupported method';
                    break;
            }
            break;
        case ENTITY_ALBUMS:
            require_once('album.php');
            $album = new Album();

            $verb = $_SERVER['REQUEST_METHOD'];

            switch ($verb) {
                case 'GET':
                    if (isset($_GET['search-text'])) {
                        echo $album->search($_GET['search-text']);
                    }
                    else if (isset($urlPieces[POS_ID])) {
                        echo $album->getOne($urlPieces[POS_ID]);
                    }
                    else {
                        echo $album->getAll();
                    }
                    break;
                case 'POST':
                    echo $album->create($_POST['Title'], $_POST['ArtistId']);
                    break;
                case 'PUT':
                    $albumData = (array) json_decode(file_get_contents('php://input'), true);
                    echo $album->update($urlPieces[POS_ID], $albumData['NewTitle'], $albumData['NewArtistId']);
                    break;
                case 'DELETE':
                    echo $album->delete($urlPieces[POS_ID]);
                    break;
                default:
                    echo 'Unknown/Unsupported method';
                    break;
            }
            break;
        case ENTITY_TRACKS:
            require_once('track.php');

            $track = new Track();

            $verb = $_SERVER['REQUEST_METHOD'];

            switch ($verb) {
                case 'GET':
                    if (isset($_GET['search-text'])) {
                        echo $track->search($_GET['search-text']);
                    }
                    else if (isset($urlPieces[POS_ID])) {
                        echo $track->getOne($urlPieces[POS_ID]);
                    }
                    else {
                        echo $track->getAll();
                    }
                    break;
                case 'POST':
                    echo $track->create($_POST);
                    break;
                case 'PUT':
                    $trackData = (array) json_decode(file_get_contents('php://input'), true);
                    echo $track->update($trackData);
                    break;
                case 'DELETE':
                    echo $track->delete($urlPieces[POS_ID]);
                    break;
                default:
                    echo 'Unknown/Unsupported method';
                    break;
            }
            break;
        default:
            echo 'Unknown entity';
            break;
            
    }
}

?>