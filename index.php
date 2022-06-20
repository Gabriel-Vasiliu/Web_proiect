<?php

require 'vendor/autoload.php';
require 'core/bootstrap.php';

use App\Core\{Request, Router};
try{
echo Router::load('routes.php')
    ->direct(Request::uri(), Request::method());
}
catch(\Exception $e){
die(var_dump($e));
}