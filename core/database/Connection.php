<?php

class Connection {
    public static function make($database){
        try{
            return new PDO(
                'mysql:host='.$database['connection'].';dbname='.$database['name'].';port='.$database['port'],
                $database['username'],
                $database['password']
            );
        } catch(PDOException $e){
            die($e->getMessage());
        }
    }
}