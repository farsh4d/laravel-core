<?php

namespace Modules\Core\Fractal;


/**
 * Fraction class
 *
 * @since 1.8.4
 * @package modules\core\fractal
 */
trait Fraction
{
    public function simpleTransform($model, $transformer)
    {
        return fractal($model, new $transformer)->transform();
    }

    public function paginatedTransform($models, $transformer)
    {
        return fractal($models, new $transformer)->transformAsPaginated();
    }
}