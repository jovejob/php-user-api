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
        return call_user_func_array($route['callback'], $matches);
      }
    }

    http_response_code(404);
    echo json_encode(["error" => "Route not found"]);
  }
}
