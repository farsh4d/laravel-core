<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Http;


use Illuminate\Http\Request;
use Modules\Core\Http\FormRequest;

/**
 * Class WebBase
 * 
 * @package Modules\Core\Http
 */
abstract class WebBase
{
    /**
     * make instance.
     *
     * @param FormRequest|Request $request
     * 
     * @return mixed
     */
    public static function render($request = null)
    {
        !is_null($request) ?: $request = request(); 

        return (new static)->handle($request);
    }


    /**
     * @param FormRequest|Request $request
     *
     * @return mixed
     */
    public abstract function handle($request);
}