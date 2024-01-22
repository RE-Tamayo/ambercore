<?php

namespace Amber\Http;

class Router
{
    protected array $routes = [];
    private Kernel $kernel;

    public function __construct()
    {
        $this->kernel = Kernel::getInstance();
    }

    public function run(Request $request)
    {
        $this->dispatch($request);
    }

    public function dispatch(Request $request)
    {
        $method = strtoupper($request->method());
        $uri = $request->uri();
        if (array_key_exists($method, $this->routes) && $this->matchRoute($uri, $this->routes[$method], $request)) {
            $this->kernel->handle($request);
        } else {
            //PUT RESPONSES IN THE CONFIG
            //CHECK FOR CONFIG IF 404 IS AVAILABLE IN CONFIG
            $this->kernel->handleError(404, 'Resource not found');
        }
    }

    protected function matchRoute(string $uri, array $routes, Request $request): bool
    {
        foreach ($routes as $route => $dispatch) {
            $pattern = $this->compilePattern($route);
            if (preg_match($pattern, $uri, $matches)) {
                $routeParams = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                foreach ($routeParams as $key => $value) {
                    if (str_contains($value, '?')) {
                        $arr = explode('?', $value);
                        $routeParams[$key] = $arr[0];
                    }
                }
                $request->setParams($routeParams);
                $request->setValue($dispatch);
                return true;
            }
        }
        return false;
    }
    
    protected function compilePattern(string $route): string
    {
        $pattern = preg_replace_callback('/\{([a-zA-Z0-9_]+)\}/', function ($matches) {
            return "(?P<" . $matches[1] . ">[^/]+)";
        }, $route);
        $pattern = "#^" . $pattern . "$#";
        return $pattern;
    }

    public function add(string $method, string $route, array|callable $dispatch): void
    {
        $method = strtoupper($method);
        $this->routes[$method][$route] = $dispatch;
    }

}