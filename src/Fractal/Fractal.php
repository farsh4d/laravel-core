<?php

namespace Modules\Core\Fractal;


use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Modules\Core\Fractal\Transformer;

/**
 * Class Fractal
 *
 * @package Modules\Core\Fractal
 */
class Fractal
{
    private $data;
    private $transformer;

    public function __construct($data, Transformer $transformer)
    {
        $this->transformer = $transformer;
        $this->data        = $data;
    }

    /**
     * Transform Collection or model with Custom Transformer
     *
     *  @return \Illuminate\Support\Collection
     */
    public function transform()
    {
        $data = $this->map($this->data);
        $data = $this->checkMapMethods($data);

        return $data;
    }

    public function transformAsPaginated()
    {
        try{
            $this->data->getCollection()->transform(function ($transformable) {
                $data = $this->transformer->transform($transformable);
                $data = $this->checkMapMethods($data);
                
                return $data;
            });
        }catch(\Exception $e) {
            throw new \Exception('we getting error with message "('.$e.')." Make sure the data be is a paginated collection!');
        }

        return $this->data;
    }

    protected function map($data)
    {
        if(is_object($data) && !$data instanceof Collection) {
            return $this->transformer->transform($data);
        }
        
        $data = $data instanceof Collection ? $data : collect($data);

        return $data->transform(function ($transformable) {
            return $this->transformer->transform($transformable);
        });
    }

    protected function checkMapMethods($data)
    {
        foreach($data as $key => $value) {
            $method = "map".Str::studly(Str::camel($key));
            if(method_exists($this->transformer, $method)) {
                $data[$key] = $this->transformer->{$method}($value);
            }
        }

        return $data;
    }
}