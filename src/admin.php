<?php

    require_once('connection.php');

    class admin extends DB {

        public string $userName = 'admin';


        /**
         * Validates an admin login
         */
        function validate($password){

            $query = <<<'SQL'
                SELECT * FROM admin;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$password]);
            if ($stmt->rowCount() === 0) {
                return false;
            }

            $row = $stmt->fetch();

            #Check password
            return (password_verify($password, $row['Password']));
        }
    }

?>