<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Support;


use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class ModuleService
 *
 * @package Modules\Core\Support
 */
abstract class ModuleService
{
    protected $service_name;

    protected $default = false;
    protected $key = null;
    protected $order = 100;
    protected $permit = true;
    protected $condition = true;
    protected $slug = null;

    protected static $service = [];

    protected static $class = null;


    public $app;

    /**
     * ModuleService constructor.
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param $key
     *
     * @return $this
     */
    public function add($key)
    {
        $this->key = $key;
        Arr::set(
            self::$service,
            "$this->service_name.$this->key",
            [
                'default'   => $this->default,
                'key'       => $this->key,
                'order'     => $this->order,
                'permit'    => $this->permit,
                'condition' => $this->condition,
                'slug'      => $this->slug,
            ]
        );

        return $this;
    }


    /**
     * @param $field_name
     * @param $value
     *
     * @return $this
     */
    protected function set($field_name, $value)
    {
        Arr::set(self::$service, "$this->service_name.$this->key.$field_name", $value);

        return $this;
    }


    /**
     * @param $field_name
     *
     * @return mixed
     */
    protected function get($field_name)
    {
        return Arr::get(self::$service, "$this->service_name.$this->key.$field_name");
    }


    /**
     * @return Collection|null
     */
    public function getService($key = null)
    {
        $this->corrections();

        $service = self::$service[$this->service_name] ?? [];

        $collection = collect($service)->sortBy('order') ?? null;

        if($key != null) {
            $collection = $collection->map(function($item) use($key) {
                    return $item[$key] ?? null;
                })->toArray()
            ;
        }

        return $collection;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function order($value)
    {
        $this->set('order', $value);

        return $this;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function permit($value)
    {
        $this->set('permit', $value);

        return $this;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function condition($value)
    {
        $this->set('condition', $value);

        return $this;
    }


    /**
     * Translate string if has tr:
     *
     * @param $value
     *
     * @return string
     */
    protected function tr($value)
    {
        if(Str::contains($value, 'tr:')) {
            $value = str_replace('tr:', '', $value);
            $value = trans($value);
        }

        return $value;
    }


    /**
     * To correct anything before render
     *
     * @return void
     */
    protected function corrections()
    {
        //
    }
}