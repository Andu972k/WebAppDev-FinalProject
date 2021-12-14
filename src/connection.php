<?php

    class DB {
        protected $pdo;

        /**
         * Opens a conncetion to the database
         */
        public function __construct()
        {
            require_once('conn_data.php');

            $dsn = 'mysql:host='. $server . ';dbname=' . $dbName . ';charset=utf8';
            $options = [
                PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC
            ];

            try {
                $this->pdo = @new PDO($dsn, $user, $pwd, $options);
            } catch (\PDOException $e) {
                echo 'Connection unsuccessful';
                die('Connection unsuccessful: ' . $e->getMessage());
                exit();
            }
        }

        /**
         * Closes a connection to the database
         */
        public function disconnect() {
            $this->pdo = null;
        }
    }

?>