<?php

class Connection {
    public static function make($database){
        try{
            return new PDO(
                'mysql:host='.$database['connection'].'; port='.$database['port'] .';'.';dbname='.$database['name'],
                $database['username'],
                $database['password']
            );
        } catch(PDOException $e){
            die($e->getMessage());
        }
    }
}