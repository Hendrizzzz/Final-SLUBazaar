<?php

// Turn on error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session to handle user login state
session_start();

// 1. BOOTSTRAPPING
// =================================================================
// Load the Composer autoloader. This allows us to use namespaces without manual `require` calls.
// The path is `../` because we are one directory deep (in `public/`).
require_once __DIR__ . '/../vendor/autoload.php';

// Load the database configuration file.
$config = require_once __DIR__ . '/../config/database.php';

// 2. CORE OBJECT INSTANTIATION
// =================================================================
// Create a new instance of our custom Router.
$router = new Core\Router();

// Create the database connection. This single object will be passed to models that need it.
// This is a simple form of "Dependency Injection".
$db = new Core\Database($config);


// 3. ROUTE DEFINITION
// =================================================================
// Define the routes your application will respond to.
// The Router will match the URL from .htaccess to these definitions.

// Auth Routes
$router->addRoute('GET', '/login', ['Controllers\AuthController', 'showLogin']);
$router->addRoute('POST', '/login', ['Controllers\AuthController', 'handleLogin']);
$router->addRoute('GET', '/logout', ['Controllers\AuthController', 'logout']);
$router->addRoute('GET', '/register', ['Controllers\AuthController', 'showRegister']);
$router->addRoute('POST', '/register', ['Controllers\AuthController', 'handleRegister']);

// User-facing Routes
$router->addRoute('GET', '/', ['Controllers\UserController', 'market']); // Homepage
$router->addRoute('GET', '/user/market', ['Controllers\UserController', 'market']);
$router->addRoute('GET', '/user/profile', ['Controllers\UserController', 'profile']);
$router->addRoute('GET', '/user/settings', ['Controllers\UserController', 'settings']);
$router->addRoute('GET', '/user/auction', ['Controllers\UserController', 'auction']); // Example: /user/auction?id=123
$router->addRoute('GET', '/user/messages', ['Controllers\UserController', 'messages']);

// Admin Routes
$router->addRoute('GET', '/admin/overview', ['Controllers\AdminController', 'overview']);
$router->addRoute('GET', '/admin/users', ['Controllers\AdminController', 'users']);
$router->addRoute('GET', '/admin/listings', ['Controllers\AdminController', 'listings']);
$router->addRoute('GET', '/admin/reports', ['Controllers\AdminController', 'reports']);
$router->addRoute('GET', '/admin/analytics', ['Controllers\AdminController', 'analytics']);


// 4. DISPATCHING THE ROUTE
// =================================================================
// Get the requested URI from the `url` query parameter that .htaccess provides.
// e.g., for `example.com/user/profile`, $_GET['url'] will be 'user/profile'.
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Get the HTTP request method (e.g., 'GET', 'POST').
$method = $_SERVER['REQUEST_METHOD'];

// Ask the router to find the matching route and execute its controller action.
// We pass the database connection ($db) so the router can inject it into the controller if needed.
$router->dispatch($uri, $method, $db);