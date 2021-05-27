<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Support;


use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;

/**
 * Class OverrideService
 *
 * @package Modules\Core\Services
 */
class OverrideService extends ModuleService
{
    protected $service_name = 'override';

    /**
     * @param string $type
     * 
     * @return $this
     */
    public function type($type)
    {
        return $this->set('type', $type);
    }

    /**
     * @param string $category
     * 
     * @return $this
     */
    public function category($category)
    {
        return $this->set('category', $category);
    }

    /**
     * @param string $expression
     * 
     * @return $this
     */
    public function expression($expression)
    {
        return $this->set('expression', $expression);
    }

    /**
     * @param string $replaced_with
     * 
     * @return $this
     */
    public function replacedWith($replaced_with)
    {
        return $this->set('replaced_with', $replaced_with);
    }

    /**
     * @param string $options
     * 
     * @return $this
     */
    public function options($options)
    {
        return $this->set('options', $options);
    }
}