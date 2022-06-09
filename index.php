<?php

require 'vendor/autoload.php';
require 'core/bootstrap.php';

use App\Core\{Request, Router};

echo Router::load('routes.php')
    ->direct(Request::uri(), Request::method());
