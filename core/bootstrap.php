<?php

use App\Core\App;
use App\Core\Session;

App::bind('config', require 'config.php');

App::bind('database', new QueryBuilder(
      Connection::make(App::get('config')['database']))
);

App::bind('session', new Session());

function view($name, $data=[]){
    extract($data);
    return require "views/{$name}.view.php";
}

function redirect($path){
    header("Location: /{$path}");
}