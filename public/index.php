<?php

define('ROOT', dirname(__DIR__));

session_start();

require_once ROOT . '/app/core/Database.php';
require_once ROOT . '/app/core/Router.php';

// Routes
$router = new Router();

$router->get('/',            'HomeController',   'index');
$router->get('/login',       'AuthController',   'showLogin');
$router->post('/login',      'AuthController',   'login');
$router->get('/register',    'AuthController',   'showRegister');
$router->post('/register',   'AuthController',   'register');
$router->get('/logout',      'AuthController',   'logout');

$router->get('/recipes',     'RecipeController', 'index');
$router->get('/recipe',      'RecipeController', 'show');
$router->get('/add-recipe',  'RecipeController', 'add');
$router->post('/add-recipe', 'RecipeController', 'store');
$router->get('/grocery',     'RecipeController', 'grocery');

$router->dispatch();
