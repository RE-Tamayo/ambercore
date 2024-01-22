<?php

namespace Amber\Http;

use Amber\Core\Container;

class Kernel
{
    protected static $instance;

    private function __construct()
    {
        // prevent instantiation
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function handle(Request $request)
    {
        if ($this->ifType('object', $request->value())) {
            $this->handleCallback($request);
        }
        if ($this->ifType('array', $request->value())) {
            $result = $this->handleController($request);
        }
        $response = new Response();
        $response->setHeader('Content-Type', 'text/html');
        $response->setHeader('HTTP/1.1', '200 OK');
        $response->setContent($result);
        $response->send();
    }

    private function handleCallback(Request $request)
    {
        $params = $request->params();
        if (isset($params)) {
            return call_user_func_array($request->value(), $params);
        }
        return call_user_func($request->value(), $this);
    }

    public function ifType(string $type, array|callable $value): bool
    {
        if($type == gettype($value) && is_callable($value)) {
            return true;
        }
        if ($type == gettype($value)) {
            return true;
        }
        return false;
    }

    private function handleController(Request $request)
    {
        $container = Container::getInstance();
        $controllerClass = $request->value()[0];
        $methodName = $request->value()[1];
      
        $container->bind($controllerClass, function () use ($controllerClass) {
            return new $controllerClass();
        });
        $controller = $container->resolve($controllerClass);
        $result = $controller->$methodName($request);
        
        return $result;
    }

    public function handleError(int $code, string $message): Response
    {
        $response = new Response();
        $response->setHeader('Content-Type', 'text/html');
        $response->setHeader('HTTP/1.1', $code . ' ' . $message);
        $response->setContent($message);
        $response->send();
    }
}