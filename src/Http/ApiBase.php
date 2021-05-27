<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Http;


use Illuminate\Http\Request;
use Modules\Core\Http\ApiResponse;
use Modules\Core\Http\FormRequest;
use Modules\Core\Http\Contracts\ApiStatus;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiBase
 * 
 * @package Modules\Core\Http
 */
abstract class ApiBase implements ApiStatus
{
    /**
     * make instance.
     *
     * @param FormRequest|Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function render($request = null)
    {
        !is_null($request) ?: $request = request(); 

        return (new static)->handle($request, new ApiResponse);
    }


    /**
     * @param FormRequest|Request $request
     * @param ApiResponse         $response
     * @return \Illuminate\Http\JsonResponse
     */
    public abstract function handle($request, ApiResponse $response);
}