<?php

namespace Amber\Http;

class Request
{
    private $get;
    private $post;
    private $cookie;
    private $headers;
    private $method;
    private $uri;
    private $routeValue;
    private $routeParams = [];

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookie = $_COOKIE;
        $this->headers = $this->getHeaders();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
    }

    public function get(string $key, $default = null)
    {
        return isset($this->get[$key]) ? $this->get[$key] : $default;
    }

    public function post(string $key, $default = null)
    {
        return isset($this->post[$key]) ? $this->post[$key] : $default;
    }

    public function cookie(string $key, $default = null)
    {
        return isset($this->cookie[$key]) ? $this->cookie[$key] : $default;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function headers()
    {
        return $this->headers;
    }

    public function value()
    {
        return $this->routeValue;
    }

    public function setValue(array|callable $value)
    {
        $this->routeValue = $value;
    }

    public function params(string $key = null)
    {
        if (!isset($key)) {
            return $this->routeParams;
        }
        return $this->routeParams[$key];
    }

    public function setParams(array $params)
    {
        $this->routeParams = $params;
    }

    public function isPost()
    {
        return $this->method === 'POST';
    }

    public function isAjax()
    {
        return isset($this->headers['X-Requested-With']) && $this->headers['X-Requested-With'] === 'XMLHttpRequest';
    }

    private function getHeaders() {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))))] = $value;
            }
        }
        return $headers;
    }
}