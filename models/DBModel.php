<?php
namespace App\Models;
use App\Core\App;
use App\Models\Model;

abstract class DBModel extends Model
{
    abstract public function tableName(): string;

    abstract public function attributes(): array;

    public function save(){
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => "'{$this->{$attr}}'", $attributes);
        $sql = "INSERT INTO $tableName(".implode(',' , $attributes).") VALUES(".implode(', ',$params).");";
        //var_dump($sql);
        App::get('database')->executeStatementSQL($sql);
        return true;
    }

    public static function findOne($where){
        $tableName = 'users';
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $pdo = App::get('database')->getPdo();
        $statement = $pdo->prepare("SELECT * FROM $tableName WHERE $sql");
        foreach($where as $key => $item){
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public static function prepare($sql)
    {
        return App::get('database')->getPdo()->prepare($sql);
    }
}