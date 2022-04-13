<?php

use App\Core\App;
use App\Core\Session;

App::bind('config', require 'config.php');

App::bind('database', new QueryBuilder(
      Connection::make(App::get('config')['database']))
);

App::bind('session', new Session());

App::bind('userClass', App::get('config')['userClass']);

$primaryValue = App::get('session')->get('user');
if($primaryValue){
    $primaryKey = App::get('userClass')::primaryKey();
    App::$user = App::get('userClass')::findOne([$primaryKey => $primaryValue]);
} else {
    App::$user = null;
}

function view($name, $data=[]){
    extract($data);
    return require "views/{$name}.view.php";
}

function redirect($path){
    header("Location: {$path}");
}