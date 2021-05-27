<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Http;


use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Modules\Core\Http\Contracts\ApiStatus;
use Modules\Core\Http\Traits\SimpleResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Contracts\Transformable as TransformableContract;

/**
 * Class ApiResponse
 *
 * @package Modules\Core\Http
 */
class ApiResponse implements ApiStatus
{
    use SimpleResponse;

    private $fields = [
        'status'      => self::STATUS_ERROR,
        'action'      => null, // Automatic binding removed
        'tag'         => null, // Deprecated and will be removed in 3.0.0
        'message'     => null, // Deprecated and will be removed in 3.0.0
        'data'        => [],
        'api_version' => '1.0.0',
    ];

    public function __call($name, $arguments)
    {
        if(!array_key_exists($name, $this->fields)) {
            throw new \Exception("$name is not modified in fields array, so you can\'t call it as a function");
        }

        if(array_key_exists($name, $this->fields)) {
            $this->fields[$name] = Arr::first($arguments);
        }

        return $this;
    }

    public function __get($var)
    {
        return $this->fields[$var] ?? null;
    }

    public function __set($var, $val)
    {
        if(!array_key_exists($var, $this->fields)) {
            throw new \Exception("$var is not modified in fields array, so you can\'t set it as a object variable");
        }

        $this->fields[$var] = $val;
    }

    public function get()
    {
        return $this->fields;    
    }

    
    /**
     * @param bool $fail_status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($status = Response::HTTP_OK,  $headers = [])
    {
        $this->corrections();

        return response()->json($this->fields, $status, $headers);   
    }

    /**
     * Modificate data
     * 
     * @return void
     */
    protected function corrections()
    {
        if(!is_string($this->fields['message'])) {
            $this->fields['data'] = $this->fields['message'];
        }

        if($this->fields['data'] instanceof TransformableContract) {
            $this->fields['data'] = $this->fields['data']->transform();
        }

        if($this->fields['data'] instanceof LengthAwarePaginator) {
            $this->fields = array_merge($this->fields, $this->fields['data']->toArray());
        }

        // based on new version
        unset($this->fields['message']);
        unset($this->fields['action']);
    }
}