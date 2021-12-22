<?php

require_once('connection.php');
require_once('functions.php');

class Album extends DB {

    /**
     * Retrives all albums
     * @return  json encoded array of all albums
     */
    function getAll(){

        $query = <<<'SQL'
            SELECT * FROM album
            ORDER BY ArtistId ASC, Title ASC
        SQL;
        

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll();

        $this->disconnect();

        return json_encode(['Response' => $results]);
    }

    /**
     * Retrieves one album
     * @param   id of an album
     * @return  json encoded album
     */
    function getOne($id){

        $query = <<<'SQL'
            SELECT * FROM album
            WHERE AlbumId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        $result = $stmt->fetch();

        $this->disconnect();

        return json_encode(['Response' => $result]);
    }

    /**
     * Retrieves albums based on title
     * 
     * @param   text upon which the search is executed
     * @return  a json array with matching albums
     */
    function search($searchText){
        $query = <<<'SQL'
            SELECT * FROM album
            WHERE Title like ?
            ORDER BY ArtistId ASC, Title ASC
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['%' . $searchText . '%']);

        $results = $stmt->fetchAll();

        $this->disconnect();

        return json_encode(['Response' => $results]);
    }

    /**
     * Creates a new album
     * @param   title of new album
     * @param   id of the album's artist
     * @return  id of the new album if successful, else returns -1 if album with the title already exists or artist doesn't exist
     */
    function create($title, $artistId){
        
        //Ensure the artist exists 
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM artist
            WHERE ArtistId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$artistId]);

        if (!$stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => -1]);
        }

        //Check if album exists
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM album
            WHERE Title = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$title]);

        if ($stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => -1]);
        }

        //Insert album
        $query = <<<'SQL'
            INSERT INTO album (Title, ArtistId) VALUES (?, ?);
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$title, $artistId]);

        $newId = $this->pdo->lastInsertId();
        $this->disconnect();

        return json_encode(['Response' => $newId]);
    }

    /**
     * Update an album
     * @param id of the album that will get updated
     * @param the name of the new artist
     * @return true if the update was successful, false if new artist doesn't exist or another album already has the same name
     */
    function update($id, $newTitle, $newArtistId){

        //Ensure the artist exists 
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM artist
            WHERE ArtistId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$newArtistId]);

        if (!$stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => false]);
        }

        //Check if album exists
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM album
            WHERE Title = ? AND NOT AlbumId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$newTitle, $id]);

        if ($stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => false]);
        }

        //Update album
        $query = <<<'SQL'
            UPDATE album
            SET Title = ?,
                ArtistId = ?
            WHERE AlbumId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$newTitle, $newArtistId, $id]);

        if ($stmt->rowCount() == 0) {
            return json_encode(['Response' => false]);
        }

        $this->disconnect();

        return json_encode(['Response' => true]);
    }

    /**
     * Delete an album
     * @param   id of the album that should be deleted
     * @return  true if successful, false if unsuccessful that is the album has tracks
     */
    function delete($id){

        //Check if album has any tracks
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM track
            WHERE AlbumId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        if ($stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => false]);
        }

        //Delete the album
        $query = <<<'SQL'
            DELETE FROM album WHERE AlbumId = ?;
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        $this->disconnect();

        return json_encode(['Response' => true]);
    }



}



?>