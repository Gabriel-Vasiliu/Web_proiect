<?php

use App\Core\App;

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

    public static function prepare($sql)
    {
        return App::get('database')->getPdo()->prepare($sql);
    }

}