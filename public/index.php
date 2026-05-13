<?php

define('ROOT', dirname(__DIR__));

session_start();

require_once ROOT . '/app/core/Database.php';
require_once ROOT . '/app/core/Router.php';

$router = new Router();

// Home & Auth
$router->get('/',             'HomeController',    'index');
$router->get('/coming-soon',  'HomeController',    'comingSoon');
$router->get('/login',        'AuthController',    'showLogin');
$router->post('/login',       'AuthController',    'login');
$router->get('/register',     'AuthController',    'showRegister');
$router->post('/register',    'AuthController',    'register');
$router->get('/logout',       'AuthController',    'logout');

// Recipes
$router->get('/recipes',       'RecipeController',  'index');
$router->get('/recipe',        'RecipeController',  'show');
$router->get('/add-recipe',    'RecipeController',  'add');
$router->post('/add-recipe',   'RecipeController',  'store');
$router->get('/grocery',       'RecipeController',  'grocery');
$router->get('/remix-recipe',  'RecipeController',  'remix');
$router->post('/remix-recipe', 'RecipeController',  'storeRemix');

// Account
$router->get('/account',                  'AccountController', 'index');
$router->get('/account/trash',            'AccountController', 'trash');
$router->get('/account/settings',         'AccountController', 'settings');
$router->post('/account/profile',         'AccountController', 'updateProfile');
$router->post('/account/password',        'AccountController', 'changePassword');
$router->post('/account/archive',         'AccountController', 'archive');
$router->get('/account/archived',         'AccountController', 'archived');
$router->post('/account/restore',         'AccountController', 'restoreAccount');
$router->get('/edit-recipe',              'AccountController', 'edit');
$router->post('/edit-recipe',             'AccountController', 'update');
$router->post('/delete-recipe',           'AccountController', 'delete');
$router->post('/restore-recipe',          'AccountController', 'restoreRecipe');
$router->post('/delete-recipe-permanent', 'AccountController', 'permanentDelete');

$router->dispatch();