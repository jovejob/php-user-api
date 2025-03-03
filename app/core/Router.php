<?php

class Router
{
  private $routes = [];

  public function add($method, $path, $callback)
  {
    $this->routes[] = compact('method', 'path', 'callback');
  }

  public function dispatch()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    foreach ($this->routes as $route) {
      if ($route['method'] === $requestMethod && preg_match("#^{$route['path']}$#", $requestUri, $matches)) {
        array_shift($matches); // Remove full match

        // Check if callback is a controller method (class-based)
        if (is_array($route['callback']) && is_string($route['callback'][0])) {
          $controller = $route['callback'][0];
          $method = $route['callback'][1];

          // Instantiate the controller
          $controllerInstance = new $controller();

          // Call the method on the instance
          return call_user_func_array([$controllerInstance, $method], $matches);
        }

        // If it's a normal function callback, execute it
        return call_user_func_array($route['callback'], $matches);
      }
    }

    http_response_code(404);
    echo json_encode(["error" => "Route not found"]);
  }
}
