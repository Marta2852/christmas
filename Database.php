<?php

class Database{
    public $pdo;

    //konstruktors un destruktors
    public function __construct($config){
        // data source name
        $dsn = "mysql:" . http_build_query($config, "", ";");

        //PHP data object
        $this->pdo = new PDO($dsn); 
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function query($sql){
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        return $statement;
    }
}

?>