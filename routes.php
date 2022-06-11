<?php

use App\Controllers\PagesController;

$router->get('', [PagesController::class, 'home']);
$router->get('home', 'PagesController@home');
$router->get('edit', 'PagesController@edit');
$router->get('login', 'PagesController@login');
$router->get('register', 'PagesController@register');
$router->get('bottles/search', 'PagesController@search');
$router->get('bottles/top', 'PagesController@top');
$router->get('bottles/manage', 'PagesController@manage');
$router->get('bottles/statistics', 'PagesController@statistics');
$router->get('logout', 'PagesController@logout');

$router->post('register', 'PagesController@register'); 
$router->post('login', 'PagesController@login');
$router->post('bottles/manage', 'PagesController@manage');