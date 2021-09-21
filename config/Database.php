<?php
 class Database{

        public $connection;

        public function getConnection(){
            $this->connection = null;

            try{
                $this->connection = new PDO("mysql:host=localhost;dbname=api_rest","root","");
                $this->connection->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Erreur de connection : " .$exception->getMessage();
            }

            return $this->connection;
        }
    }
    ?>