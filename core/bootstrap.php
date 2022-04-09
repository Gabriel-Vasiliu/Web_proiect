<?php

use App\Core;

function view($name)
{
    return require "views/{$name}.view.php";
}

function redirect($path){
    header("Location: /{$path}");
}