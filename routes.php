<?php

use App\Controllers\PagesController;

$router->get('', [PagesController::class, 'home']);
$router->get('home', 'PagesController@home');
$router->get('edit', 'PagesController@edit');
$router->get('login', 'PagesController@login');
$router->get('register', 'PagesController@register');
$router->get('search', 'PagesController@search');
$router->get('top', 'PagesController@top');
$router->get('statistics', 'PagesController@statistics');