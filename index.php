<?php
    require 'core/Router.php';
    //require 'routes.php';

    $uri = trim($_SERVER['REQUEST_URI'], '/');
    require Router::load('routes.php')->direct($uri);
?>