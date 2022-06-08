<?php

class QueryBuilder {
    protected $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function selectAllData($table) {
        $statement = $this->pdo->prepare("SELECT * FROM {$table}");

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function selectUserBottles($username, $bottlesTable, $userBottlesTable, $usersTable) {
        $sql = "SELECT * FROM {$userBottlesTable} WHERE user_id=(SELECT id FROM $usersTable WHERE username='{$username}');";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_OBJ);
        $bottleIdArr = [];
        foreach($rows as $row){
            $bottleIdArr[] = $row->bottle_id;
        }
        $any = '';
        foreach($bottleIdArr as $id){
            $any = $any . $id . ', ';
        }
        if(empty($bottleIdArr)) return [];
        $any = substr($any, 0, -2);
        $sql = "SELECT * FROM {$bottlesTable} WHERE id in ({$any});";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_OBJ);
        return $rows;
    }

    public function executeStatementSQL($sql){
        try{
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        } catch(PDOException $e){
            var_dump($sql);
            var_dump($e->getMessage());
        }
       // var_dump($statement);
    }

    public function getPdo(){
        return $this->pdo;
    }
}