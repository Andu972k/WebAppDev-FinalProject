<?php

require_once('connection.php');
require_once('functions.php');

class Artist extends DB {

    /**
     * Retrives the total number of artists
     */
    /*
    public function countArtists(){

        $query = <<<'SQL'
            SELECT COUNT(*) as NumberOfArtists FROM artist
        SQL;

        $stmt = $this->pdo->query($query);

        $result = $stmt->fetch();

        return $result;
    }
    */

    /**
     * Retrieves range of artists
     */
    /*
    function getRange($page){
        
        $isFirstPage = ($page === 1);

        $this->countArtists();

        $query = <<<'SQL'
            SELECT * FROM artist
            ORDER BY Name
        SQL;


        if ($isFirstPage) {
           $query .= 'LIMIT 20'; 
        }
        else {
            $query .= 'LIMIT ?, 20';
        }
        

        $stmt = $this->pdo->prepare($query);

        if ($isFirstPage) {
            $stmt->execute();
        }
        else {
            $lastRowReceived = ($page-1) * 20;
            $stmt->execute([$lastRowReceived]);
        }

        $results = $stmt->fetchAll();

        $this->disconnect();

        return json_encode($results);
    }
    */

    /**
     * Retrieves all artists
     * @return  json encoded array of all albums
     */
    
    function getAll(){

        $query = <<<'SQL'
            SELECT * FROM artist
            ORDER BY Name ASC
        SQL;
        

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll();

        $this->disconnect();

        return json_encode(['Response' => $results]);
    }

    /**
     * Retrieves one artist
     * @param   id of an artist
     * @return  json encoded artist
     */
    function getOne($id){

        $query = <<<'SQL'
            SELECT * FROM artist
            WHERE ArtistId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        $result = $stmt->fetch();

        $this->disconnect();

        return json_encode(['Response' => $result]);
    }

    /**
     * Retrieves artists based on name
     * 
     * @param   text upon which the search is executed
     * @return  a json array with matching artists
     */
    function search($searchText){
        $query = <<<'SQL'
            SELECT * FROM artist
            WHERE Name like ?
            ORDER BY Name ASC
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['%' . $searchText . '%']);

        $results = $stmt->fetchAll();

        $this->disconnect();

        return json_encode(['Response' => $results]);
    }

    /**
     * Creates a new artist
     * @param   name of new artist
     * @return  id of the new artist if successful, else returns -1 if artist already exists
     */
    function create($name){
        
        //Check if artist exists 
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM artist
            WHERE Name = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$name]);

        if ($stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => -1]);
        }

        //Insert artist
        $query = <<<'SQL'
            INSERT INTO artist (Name) VALUES (?);
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$name]);

        $newId = $this->pdo->lastInsertId();
        $this->disconnect();

        return json_encode(['Response' => $newId]);
    }

    /**
     * Update an artist
     * @param id of the artist that will get updated
     * @param the new name of the artist
     * @return true if the update was successful, false if update was unsuccessful
     */
    function update($id, $newName){
        $query = <<<'SQL'
            UPDATE artist
            SET Name = ?
            WHERE ArtistId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$newName, $id]);

        if ($stmt->rowCount() == 0) {
            return json_encode(['Response' => false]);
        }

        $this->disconnect();

        return json_encode(['Response' => true]);
    }

    /**
     * Delete an artist
     * @param   id of the artist that should be deleted
     * @return  true if successful, false if unsuccessful
     */
    function delete($id){

        //Check if artist has any albums
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM album
            WHERE ArtistId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        if ($stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => false]);
        }

        //Delete the artist
        $query = <<<'SQL'
            DELETE FROM artist WHERE ArtistId = ?;
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        $this->disconnect();

        return json_encode(['Response' => true]);
    }

}

?>