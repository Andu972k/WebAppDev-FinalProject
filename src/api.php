<?php 

session_set_cookie_params(0, '/', $_SERVER['SERVER_NAME'], true, true);
session_start();

require_once('../security/csrf_token_functions.php');

define('POS_ENTITY', 1);
define('POS_ID', 2);

define('Max_PIECES', 5);

//Define API entities
define('ENTITY_ARTISTS', 'artists');
define('ENTITY_ALBUMS', 'albums');
define('ENTITY_TRACKS', 'tracks');
define('ENTITY_CUSTOMERS', 'customers');

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($pieces === Max_PIECES) {
        $body = (array) json_decode(file_get_contents('php://input'), true);
        if ($body['csrf_token'] !== $_SESSION['csrf_token']) {
            die("CSRF token validation failed.");
        }
    }
    else {
        die_on_csrf_token_failure();
    }
    
}

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
                    if (isset($_GET['search-text']) || isset($_GET['genre']) || isset($_GET['media-type'])) {
                        $searchText = (isset($_GET['search-text']))? $_GET['search-text'] : null;
                        $genre = (isset($_GET['genre']))? $_GET['genre'] : null;
                        $mediaType = (isset($_GET['media-type']))? $_GET['media-type'] : null;
                        echo $track->search($searchText, $genre, $mediaType);
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
                    echo $track->update($urlPieces[POS_ID], $trackData);
                    break;
                case 'DELETE':
                    echo $track->delete($urlPieces[POS_ID]);
                    break;
                default:
                    echo 'Unknown/Unsupported method';
                    break;
            }
            break;
        case ENTITY_CUSTOMERS:
            require_once('customer.php');

            $customer = new Customer();

            $verb = $_SERVER['REQUEST_METHOD'];

            switch ($verb) {
                case 'POST':
                    if (isset($urlPieces[POS_ID]) && count($urlPieces) == 5) {
                        $checkoutData = (array) json_decode(file_get_contents('php://input'), true);
                        echo $customer->checkout($urlPieces[POS_ID], $checkoutData);
                    }
                    else {
                        echo $customer->create($_POST['FirstName'],$_POST['LastName'], $_POST['Password'], $_POST['Company'], $_POST['Address'], $_POST['City'], $_POST['State'], $_POST['Country'], $_POST['PostalCode'], $_POST['Phone'], $_POST['Fax'], $_POST['Email']);
                    }
                    break;
                case 'PUT':
                    $customerData = (array) json_decode(file_get_contents('php://input'), true);
                    echo $customer->update($customerData['FirstName'], $customerData['LastName'], $customerData['Password'], $customerData['NewPassword'], $customerData['Company'], $customerData['Address'], $customerData['City'], $customerData['State'], $customerData['Country'], $customerData['PostalCode'], $customerData['Phone'], $customerData['Fax'], $customerData['Email']);
                    break;
                
                default:
                    echo 'Unsupported method';
                    break;
            }
            break;
        default:
            echo 'Unknown entity';
            break;
            
    }
}

?>