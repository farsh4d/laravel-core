<?php

namespace Modules\Core\Fractal;


/**
 * Class Transformer
 *
 * @package Modules\Core\Fractal
 */
abstract class Transformer
{
    use Fraction;
    
    /**
     * @param $data
     * 
     * @return array
     */
    public abstract function transform($data) :array;
}