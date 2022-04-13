<?php

namespace App\Core;

use App\Models\DBModel;
use App\Models\User;

class App
{
    protected static $registry = [];
    public static ?DBModel $user;
    public static function bind($key, $value){
        static::$registry[$key]=$value;
    }

    public static function get($key){
        if(array_key_exists($key,static::$registry)){
            return static::$registry[$key];
        }
        else {
            throw new \Exception("No {$key} is bound in the container.");
        }
    }

    public static function login(DBModel $user1){
        $user = $user1;
        $primaryKey = User::primaryKey();
        $primaryValue = $user->{$primaryKey};
        App::get('session')->set('user', $primaryValue);
        return true;
    }

    public static function logout(){
        $user = null;
        App::get('session')->remove('user');
    }

    public static function isGuest()
    {
        return !self::$user;
    }

    public static function redirect($path){
        header("Location: /{$path}");
    }
}