<?php

use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Providers\Router;

Router::post('/login', [UserController::class, 'login']);


Router::post('/createPost', [PostController::class, 'create'], true);
