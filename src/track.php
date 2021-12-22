<?php

require_once('connection.php');
require_once('functions.php');

class Track extends DB {

    /**
     * Retrives all tracks
     * @return  json encoded array of all tracks
     */
    function getAll(){

        $query = <<<'SQL'
            SELECT * FROM track
            ORDER BY AlbumId, Name
        SQL;
        

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll();

        $this->disconnect();

        return json_encode(['Response' => $results]);
    }

    /**
     * Retrieves one track
     * @param   id of a track
     * @return  json encoded track
     */
    function getOne($id){

        $query = <<<'SQL'
            SELECT * FROM track
            WHERE TrackId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        $result = $stmt->fetch();

        $this->disconnect();

        return json_encode(['Response' => $result]);
    }

    /**
     * Retrieves tracks based on title
     * 
     * @param   text upon which the search is executed
     * @return  a json array with matching tracks
     */
    function search($searchText){
        $query = <<<'SQL'
            SELECT * FROM track
            WHERE Name like ?
            ORDER BY AlbumId, Name
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['%' . $searchText . '%']);

        $results = $stmt->fetchAll();

        $this->disconnect();

        return json_encode(['Response' => $results]);
    }

    /**
     * Creates a new track
     * @param   object containing information about a new track
     * @return  id of the new track if successful, else returns -1 if track with the name already exists or album, mediatype, genre doesn't exist
     */
    function create($newTrack){

        //Check if track exists
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM track
            WHERE Name = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$newTrack['Name']]);

        if ($stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => -1]);
        }

        //Ensure album exists
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM album
            WHERE AlbumId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$newTrack['AlbumId']]);

        if (!$stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => -1]);
        }

        //Ensure mediatype exists
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM mediatype
            WHERE MediaTypeId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$newTrack['MediaTypeId']]);

        if (!$stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => -1]);
        }

        //Ensure genre exists
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM genre
            WHERE GenreId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$newTrack['GenreId']]);

        if (!$stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => -1]);
        }

        //Insert track
        $query = <<<'SQL'
            INSERT INTO track (Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice) VALUES (?, ?, ?, ?, ?, ?, ?, ?);
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$newTrack['Name'], $newTrack['AlbumId'], $newTrack['MediaTypeId'], $newTrack['GenreId'], $newTrack['Composer'], $newTrack['Milliseconds'], $newTrack['Bytes'], $newTrack['UnitPrice']]);

        $newId = $this->pdo->lastInsertId();
        $this->disconnect();

        return json_encode(['Response' => $newId]);
    }

    /**
     * Update a track
     * @param   object containing updated information about a track
     * @return  true if the update was successful, false if new artist doesn't exist or another album already has the same name
     */
    function update($id, $updatedTrack){

        //Check if another track has the updated track's name exists
        $query = <<<'SQL'
        SELECT COUNT(*) as Total FROM track
        WHERE Name = ? AND NOT TrackId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$updatedTrack['Name'], $id]);

        if ($stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => false]);
        }
        

        //Ensure album exists
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM album
            WHERE AlbumId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$updatedTrack['AlbumId']]);

        if (!$stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => false]);
        }

        //Ensure mediatype exists
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM mediatype
            WHERE MediaTypeId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$updatedTrack['MediaTypeId']]);

        if (!$stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => false]);
        }

        //Ensure genre exists
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM genre
            WHERE GenreId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$updatedTrack['GenreId']]);

        if (!$stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => false]);
        }

        //Update album
        $query = <<<'SQL'
            UPDATE track
            SET Name = ?,
                AlbumId = ?,
                MediaTypeId = ?,
                GenreId = ?,
                Composer = ?,
                Milliseconds = ?,
                Bytes = ?,
                UnitPrice = ?
            WHERE TrackId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$updatedTrack['Name'], $updatedTrack['AlbumId'], $updatedTrack['MediaTypeId'], $updatedTrack['GenreId'], $updatedTrack['Composer'], $updatedTrack['Milliseconds'], $updatedTrack['Bytes'], $updatedTrack['UnitPrice'], $id]);
        
        if ($stmt->rowCount() == 0) {
            return json_encode(['Response' => false]);
        }

        $this->disconnect();

        return json_encode(['Response' => true]);
    }

    /**
     * Delete an album
     * @param   id of the track that should be deleted
     * @return  true if successful, false if unsuccessful that is the track is in a invoiceline
     */
    function delete($id){

        //Check if track is in any invoicelines
        $query = <<<'SQL'
            SELECT COUNT(*) as Total FROM invoiceline
            WHERE TrackId = ?
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        if ($stmt->fetch()['Total'] > 0) {
            return json_encode(['Response' => false]);
        }

        //Delete the album
        $query = <<<'SQL'
            DELETE FROM track WHERE TrackId = ?;
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        $this->disconnect();

        return json_encode(['Response' => true]);
    }
}


?>