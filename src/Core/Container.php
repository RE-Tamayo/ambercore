<?php

namespace Amber\Core;

class Container
{
    protected static $instance;
    protected array $bindings = [];
    protected array $resolved = [];

    private function __construct()
    {
        // prevent instatiation;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function has(string $abstract)
    {
        return array_key_exists($abstract, $this->bindings);
    }

    public function bind(string $abstract, callable $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function resolve(string $abstract)
    {
        if (!$this->has($abstract)) {
           throw new \RuntimeException("Service '$abstract' has not been binded to the container.", 1);
        }
        if (!isset($this->resolved[$abstract])) {
            $this->resolved[$abstract] = call_user_func($this->bindings[$abstract], $this);
        }
        return $this->resolved[$abstract];
    }
}