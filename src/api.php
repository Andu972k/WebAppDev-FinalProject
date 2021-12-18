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
                    $urlPieces[2] = $_GET['searchText'];
                    echo json_encode($urlPieces);
                    break;
                case 'POST':

                    break;
                case 'DELETE':

                    break;
                
                default:
                    # code...
                    break;
            }
            break;
        
        default:
            echo 'Unknown entity';
            
    }
}

?>