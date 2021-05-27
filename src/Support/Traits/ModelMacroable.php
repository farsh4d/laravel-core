<?php
/**
 * Written By Farshad Hassani
 */

namespace Core\Support\Traits;


use Closure;
use ReflectionClass;
use ReflectionMethod;
use BadMethodCallException;

/**
 * ModelMacroable trait
 */
trait ModelMacroable
{
    protected static $macros = [];
    
    /**
     * Register a custom macro.
     *
     * @param  string $name
     * @param  object|callable  $macro
     */
    public static function macro(string $name, $macro)
    {
        static::$macros[$name] = $macro;
    }
    
    /**
     * Mix another object into the class.
     *
     * @param  object  $mixin
     */
    public static function mixin($mixin)
    {
        $methods = (new ReflectionClass($mixin))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );

        foreach ($methods as $method) {
            $method->setAccessible(true);
            static::macro($method->name, $method->invoke($mixin));
        }
    }

    /**
     * Chack where the method exists in macros
     *
     * @param string $name
     * @return bool
     */
    public static function hasMacro(string $name): bool
    {
        return isset(static::$macros[$name]);
    }

    /**
     * Handle dynamic static method
     *
     * @param [type] $method
     * @param [type] $parameters
     * @return void
     */
    public static function __callStatic($method, $parameters)
    {
        if (static::hasMacro($method)) {
            $macro = static::$macros[$method];
            if ($macro instanceof Closure) {
                return call_user_func_array(Closure::bind($macro, null, static::class), $parameters);
            }
    
            return call_user_func_array($macro, $parameters);
        }

        parent::__callStatic($method, $parameters);
    }

    /**
     * Handle dynamic method
     *
     * @param [type] $method
     * @param [type] $parameters
     * @return void
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            $macro = static::$macros[$method];
            if ($macro instanceof Closure) {
                return call_user_func_array($macro->bindTo($this, static::class), $parameters);
            }
    
            return call_user_func_array($macro, $parameters);            
        }

        return parent::__call($method, $parameters);
    }
}