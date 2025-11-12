<?php

namespace Core;

use Controllers\AdminController;
use Controllers\AuthController;
use Controllers\UserController;

class Router {
    


    /**
     * An array to store all the registered routes.
     * @var array
     */
    protected $routes = [];




    /**
     * Add a route to the routing table. This is the method that was missing.
     *
     * @param string $method The HTTP method (GET, POST, etc.)
     * @param string $uri The URI pattern (e.g., '/users/profile')
     * @param array $action The [ControllerClass, methodName] to execute
     */
    public function addRoute(string $method, string $uri, array $action): void
    {
        // We use an associative array to store routes by their method and URI
        // for fast lookups. The URI is normalized by removing slashes.
        $this->routes[strtoupper($method)][trim($uri, '/')] = $action;
    }




    /**
     * Find the matching route and dispatch it.
     *
     * @param string $uri The requested URI from the browser.
     * @param string $method The request method.
     * @param Database $db The database connection instance, which we'll pass to the controller.
     */
    public function dispatch(string $uri, string $method, Database $db): void
    {
        // Sanitize the URI from the query string and leading/trailing slashes.
        $uri = trim(strtok($uri, '?'), '/');
        $method = strtoupper($method);
        
        // Check if a route exists for the given method and URI
        if (isset($this->routes[$method][$uri])) {
            [$controller, $methodName] = $this->routes[$method][$uri];

            if (class_exists($controller)) {
                // Create an instance of the controller, passing the database connection.
                // This is a simple but powerful form of "Dependency Injection".
                $controllerInstance = new $controller($db);

                if (method_exists($controllerInstance, $methodName)) {
                    // Call the specified method on the controller instance.
                    $controllerInstance->$methodName();
                } else {
                    $this->abort(500, "Method '{$methodName}' not found in controller '{$controller}'.");
                }
            } else {
                $this->abort(500, "Controller class '{$controller}' not found.");
            }
        } else {
            // No route matched, send a 404 Not Found response.
            $this->abort(404, "404 Not Found: No route for '{$uri}'.");
        }
    }



    
    /**
     * A simple helper method to handle HTTP errors and stop execution.
     *
     * @param int $code The HTTP status code (e.g., 404, 500).
     * @param string $message The error message to display.
     */
    protected function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        // In a real application, you would load a fancy error view here.
        // For now, just echoing the message is fine for debugging.
        echo $message; 
        exit();
    }



    
}