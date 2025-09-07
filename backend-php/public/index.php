<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/db.php';
require_once '../app/helpers/Response.php';
require_once '../app/helpers/JWT.php';

// Simple router
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove query string and base path
$path = parse_url($request_uri, PHP_URL_PATH);
$path = str_replace('/backend-php/public', '', $path);

// Route definitions
$routes = [
    // Auth routes
    'POST /auth/login' => 'AuthController@login',
    'POST /auth/register' => 'AuthController@register',
    'POST /auth/logout' => 'AuthController@logout',
    'GET /auth/me' => 'AuthController@me',
    
    // Product routes
    'GET /products' => 'ProductController@index',
    'GET /products/{id}' => 'ProductController@show',
    'POST /products' => 'ProductController@store',
    'PUT /products/{id}' => 'ProductController@update',
    'DELETE /products/{id}' => 'ProductController@destroy',
    
    // Cart routes
    'GET /cart' => 'CartController@index',
    'POST /cart/add' => 'CartController@add',
    'PUT /cart/update' => 'CartController@update',
    'DELETE /cart/remove/{id}' => 'CartController@remove',
    'DELETE /cart/clear' => 'CartController@clear',
    
    // Order routes
    'GET /orders' => 'OrderController@index',
    'GET /orders/{id}' => 'OrderController@show',
    'POST /orders' => 'OrderController@store',
    'PUT /orders/{id}/status' => 'OrderController@updateStatus',
    
    // Admin routes
    'GET /admin/orders' => 'AdminController@orders',
    'GET /admin/users' => 'AdminController@users',
    'PUT /admin/orders/{id}/status' => 'AdminController@updateOrderStatus',
];

// Find matching route
$matched_route = null;
$route_params = [];

foreach ($routes as $route => $handler) {
    list($method, $pattern) = explode(' ', $route, 2);
    
    if ($method !== $request_method) {
        continue;
    }
    
    // Convert route pattern to regex
    $regex = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
    $regex = '#^' . $regex . '$#';
    
    if (preg_match($regex, $path, $matches)) {
        $matched_route = $handler;
        array_shift($matches); // Remove full match
        $route_params = $matches;
        break;
    }
}

if (!$matched_route) {
    Response::error('Route not found', 404);
}

// Parse handler
list($controller_name, $method_name) = explode('@', $matched_route);

// Load controller
$controller_file = "../app/controllers/{$controller_name}.php";
if (!file_exists($controller_file)) {
    Response::error('Controller not found', 500);
}

require_once $controller_file;

// Create controller instance and call method
$controller = new $controller_name();
if (!method_exists($controller, $method_name)) {
    Response::error('Method not found', 500);
}

try {
    $controller->$method_name($route_params);
} catch (Exception $e) {
    Response::error($e->getMessage(), 500);
}
?>
