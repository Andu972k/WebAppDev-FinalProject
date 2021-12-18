<?php

require_once('connection.php');
require_once('functions.php');

class Artist extends DB {

    /**
     * Retrieves artists based on name
     * 
     * @param   text upon which the search is executed
     * @return  a json array with matching artists
     */
    function search($searchText){

        $query = <<<'SQL'
            SELECT * FROM artist
            WHERE name like ?
            ORDER BY name
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['%' . $searchText . '%']);

        $results = $stmt->fetchAll();

        $this->disconnect();

        return json_encode($results);
    }
}

?>