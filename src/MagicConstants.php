<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core;


use ReflectionClass;


/**
 * MagicConstants class
 *
 * @package modules\core\classes
 */
trait MagicConstants
{
    protected function constants()
    {
        $refl = new ReflectionClass($this);
        
        return $refl->getConstants();
    }

    protected function asArray()
    {
        return array_values($this->constants());
    }

    protected function asString($glue = ',')
    {
        return implode($glue, $this->asArray());
    }

    protected function keysAsArray()
    {
        $keys = array_keys($this->constants());

        return array_map(function($key) {
            return strtolower($key);
        }, $keys);
    }

    protected function keysAsString($glue = ',')
    {
        return implode($glue, $this->keysAsArray());
    }

    protected function value($key)
    {
        $key = strtoupper($key);

        return $this->constants()[$key];
    }

    protected function search($value)
    {
        $key = array_search($value, $this->constants());

        return strtolower($key);
    }

    public static function __callStatic($method, $arguments)
    {
        return (new static)->{$method}(...$arguments);
    }

    public function __call($method, $arguments)
    {
        return $this->{$method}($arguments);
    }
}