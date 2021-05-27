<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Http;


use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Modules\Core\Contracts\FormRequest as FormRequestContract;

/**
 * Class FormRequest
 *
 * @package Modules\Core\Http
 */
abstract class FormRequest extends BaseFormRequest implements FormRequestContract
{
    protected $data              = [];
    protected $original_data     = [];
    public $module_name          = false;
    public $lang                 = 'validation.attributes';


    /**
     * Check user is authorized or not
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Call before validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        //
    }

    /**
     * Call after validation passed
     *
     * @return void
     */
    protected function afterValidation()
    {
        //
    }

    /**
     * Validate the class instance.
     *
     * @return void
     */
    public function validate()
    {
        parent::validate();
        
        $this->afterValidation();
    }

    /**
     * @param      $attr
     * @param null $_
     */
    public function unset($attr, $_ = null)
    {
        $params = func_get_args();

        foreach($params as $param) {
            if(Arr::has($this->data, $param)) {
                unset($this->data[$param]);
            }
        }
    }


    /**
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {   
        return $this->{$property} ?? $this->data[$property] ?? $this->get($property);
    }


    /**
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        $this->attributes->set($property, $value);

        $this->data[$property] = $value;
    }


    /**
     * @return bool
     */
    protected function inCreationMode()
    {
        return request()->route('hash_id') == hashid(0)
            ||
            request()->route('hash_id') == null;
    }


    /**
     * @return bool
     */
    protected function inEditionMode()
    {
        return !$this->inCreationMode();
    }


    /**
     * @param $key
     *
     * @return array|null|string
     */
    protected function lang($key)
    {
        $key = $this->lang.'.'.$key;
        
        if($this->module_name) {
            $key = $this->module_name.'::'.$key;
        }

        return trans($key);
    }

    public function trans()
    {
        return [];    
    }

    public function attributes()
    {
        $trans = $this->trans();
        $attributes = [];
        foreach ($trans as $key) {
            $attributes[$key] = $this->lang($key);
        }

        return $attributes;
    }

    /**
     * Retrieve an input item from the request.
     *
     * @param  string  $key
     * @param  string|array|null  $default
     * @return string|array
     */
    public function input($key = null, $default = null)
    {
        $data = $this->query->all() + $this->getInputSource()->all();
        // $data = array_merge($data, $this->data);

        return data_get($data, $key, $default);
    }

    public function all($keys = null)
    {
        $input = array_replace_recursive($this->input(), $this->allFiles(), $this->data);

        if (! $keys) {
            return $input;
        }

        $results = [];

        foreach (is_array($keys) ? $keys : func_get_args() as $key) {
            Arr::set($results, $key, Arr::get($input, $key));
        }

        return $results;
    }
}