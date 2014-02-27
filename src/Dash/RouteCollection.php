<?php

namespace RouterBenchmarks\Dash;

use Dash\Router\Exception\InvalidArgumentException;
use Dash\Router\Exception\OutOfBoundsException;
use Dash\Router\Http\Route\RouteInterface;
use Dash\Router\Http\RouteCollection\RouteCollectionInterface;

class RouteCollection implements RouteCollectionInterface
{

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @var int
     */
    protected $serial = 0;

    /**
     * @var bool
     */
    protected $sorted = false;

    /**
     * @throws InvalidArgumentException
     */
    public function insert($name, $route, $priority = 1)
    {
        if (!($route instanceof RouteInterface || is_array($route))) {
            throw new InvalidArgumentException(sprintf(
                '$route must either be an array or implement Dash\Router\Http\Route\RouteInterface, %s given',
                is_object($route) ? get_class($route) : gettype($route)
            ));
        }

        $this->sorted = false;

        $this->routes[$name] = array(
            'priority' => (int) $priority,
            'serial'   => $this->serial++,
            'route'    => $route,
        );
    }

    public function remove($name)
    {
        if (!isset($this->routes[$name])) {
            return;
        }

        unset($this->routes[$name]);
    }

    public function clear()
    {
        $this->routes = array();
        $this->serial = 0;
        $this->sorted = true;
    }

    public function get($name)
    {
        if (!isset($this->routes[$name])) {
            throw new OutOfBoundsException(sprintf('Route with name "%s" was not found', $name));
        }

        return $this->routes[$name]['route'];
    }

    public function current()
    {
        $node = current($this->routes);
        return ($node !== false ? $this->get(key($this->routes)) : false);
    }

    public function key()
    {
        return key($this->routes);
    }

    public function next()
    {
        $node = next($this->routes);
        return ($node !== false ? $this->get(key($this->routes)) : false);
    }

    public function rewind()
    {
        if (!$this->sorted) {
            arsort($this->routes);
            $this->sorted = true;
        }

        reset($this->routes);
    }

    public function valid()
    {
        return ($this->current() !== false);
    }
}