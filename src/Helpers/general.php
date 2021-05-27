<?php

use Carbon\Carbon;
use Modules\Core\Classes\Core;
use Modules\Core\Facades\Hashids;
use Modules\Core\Fractal\Fractal;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Modules\Core\Fractal\Transformer;
use Modules\Core\Support\ModelResolver;

if(!function_exists('core')) {
    /**
     * The Core class helper
     * 
     * @return Core
     */
    function core()
    {
        return new Core;
    }
}

if(!function_exists('hashid')) {
    /**
     * Convert id to hashis and reverse
     * 
     * @param string|array $string
     * @return array|string|integer|bool
     */
    function hashid($param)
    {
        if(is_numeric($param)) {
            return Hashids::encode((int)$param);
        }

        if(is_string($param)) {
            $decoded = Hashids::decode((string)$param);

            return is_null($decoded) || empty($decoded) ? 0 : $decoded[0];
        }

        if(is_array($param)) {
            return array_map(function($item) {
                return hashid($item);
            }, $param);
        }

        return false;
    }
}

if(!function_exists('is_hashid')) {
    /**
     * @param string $string
     *
     * @return bool
     * @throws \Exception
     */
    function is_hashid($string)
    {
        return !( is_numeric($string) || empty(hashid($string)) );
    }
}

if(!function_exists('array_to_object')) {
    /**
     * @param array $array
     * @return object
     */
    function array_to_object($array)
    {
        $encoded = json_encode($array);
        $decoded = json_decode($encoded);

        return $decoded;
    }
}

if(!function_exists('get_locale')) {
    /**
     * @return mixed
     */
    function get_locale()
    {
        return App::getLocale();
    }
}

if(!function_exists('has_tr')) {
    /**
     * @param string $key
     * @return mixed
     */
    function has_tr($key)
    {
        return Lang::has($key, get_locale());
    }
}

if(!function_exists('current_route')) {
    /**
     * @return object
     */
    function current_route()
    {
        return Route::getCurrentRoute();
    }
}

if(!function_exists('render')) {
    /**
     * To render View object
     *
     * @param null          $view
     * @param array         $data
     * @param array         $mergeData
     * @param callable|null $callback
     * @return string
     * @throws Throwable
     */
    function render($view = null, $data = [], $mergeData = [], callable $callback = null)
    {
        return view($view, $data, $mergeData)->render($callback);
    }
}

if(!function_exists('pd')) {
    /**
     * Converts English digits to Persian digits and some Arabic notation letters to Persian notation letters
     *
     * @param $string
     * @return mixed
     */
    function pd($string)
    {
        $persian_chars = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '۴', '۵', '۶', 'ی', 'ک', 'ک',];
        $latin_chars   = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '٤', '٥', '٦', 'ي', 'ك', 'ك',];

        return str_replace($latin_chars, $persian_chars, $string);
    }
}

if(!function_exists('ed')) {

    /**
     * Converts Persian digits to English digits
     *
     * @param $string
     * @return mixed
     */
    function ed($string)
    {
        $persian_chars = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٤', '٥', '٦'];
        $latin_chars   = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '4', '5', '6'];

        return str_replace($persian_chars, $latin_chars, $string);
    }
}

if (!function_exists('model')) {
    /**
     * @param      $class
     * @param int  $id
     * @param bool $with_trashed
     * @return \Modules\Core\Eloquent\Model
     */
    function model($class, $id = 0, $with_trashed = false)
    {
        return (new ModelResolver)->generate($class, $id, $with_trashed);
    }
}

if (!function_exists('array_values_r')) {
    /**
     * Get array values Recursively
     * 
     * @param array $array
     * @return array
     */
    function array_values_r($array) 
    {
        $flat = [];
      
        foreach($array as $value) {
            if (is_array($value)) {
                $flat = array_merge($flat, array_values_r($value));
            }
            else {
                array_push($flat, $value);
            }
        }
        
        return $flat;
    }
}

if (!function_exists('array_keys_r')) {
    /**
     * Get array keys Recursively
     * 
     * @param array $array
     * @return array
     */
    function array_keys_r($array) 
    {
        $keys = array_keys($array);
      
        foreach ($array as $i) {
            if (is_array($i)) {
                $keys = array_merge($keys, array_keys_r($i));
            }
        }

        return $keys;
    }
}

if (!function_exists('resolve_route_from_url')) {
    /**
     * resolve route form url
     */
    function resolve_route_from_url($url) {
        return app('router')
            ->getRoutes()
            ->match(\Illuminate\Http\Request::create($url))
        ;
    }
}

if(!function_exists('persian_slug')) {
    /**
     * @param string $str
     * @param array $options
     * @return string
     */
    function persian_slug($str, array $options = [])
    {
        $str = strtr($str, $options);

        return str_replace(' ', '-', $str);
    }
}

if(!function_exists('make_bool')) {
    /**
     * @param mixed $param
     * @return bool
     */
    function make_bool($param)
    {
        // Make lower case if $bool is string
        if(is_string($param)) {
            $param = strtolower($param);
        }

        if($param == 'true' || $param === true || $param === '1') {
            return true;
        }

        return false;
    }
}

if(!function_exists('bool_to_int')) {
    /**
     * @param bool $bool
     * @return int
     */
    function bool_to_int($bool)
    {
        return make_bool($bool) ? 1 : 0;
    }
}

if(!function_exists('carbon')) {
    /**
     * @return \Carbon\Carbon
     */
    function carbon()
    {
        return new Carbon;
    }
}

if(!function_exists('is_not_object')) {
    /**
     * @return bool
     */
    function is_not_object($var)
    {
        return !is_object($var);
    }
}

if(!function_exists('is_not_empty')) {
    /**
     * @return bool
     */
    function is_not_empty($var)
    {
        return !empty($var);
    }
}

if(!function_exists('is_not_null')) {
    /**
     * @return bool
     */
    function is_not_null($var)
    {
        return !is_null($var);
    }
}

if (!function_exists('fractal')) {
    /**
     * @param \Illuminate\Support\Collection|\Modules\Core\Contracts\Transformable|array $data
     * @param object $transformer
     * @return Fractal
     */
    function fractal($data, Transformer $transformer)
    {
        return new Fractal($data, $transformer);
    }
}

if(!function_exists('load_storage')) {
    /**
     * @param string $path
     * @return string
     */
    function load_storage($path)
    {
        $path = STORAGE_PATH . $path;

        return asset($path);
    }
}

if(!function_exists('is_json')) {
    /**
     * @param string $string
     * @return bool
     */
    function is_json($string)
    {
        json_decode($string);
    
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if(!function_exists('get_id')) {
    /**
     * @param string hash_id
     * @return integer
     */
    function get_id($hash_id)
    {
        return is_hashid($hash_id) ? hashid($hash_id) : $hash_id;
    }
}

if(!function_exists('to_unix_timestamp')) {

    /**
     * @param int|string|Carbon|Verta $time
     * @param null|int|string $milliseconds
     * @param null|string $timezone
     * @return string|null
     */
    function to_unix_timestamp($time, $milliseconds = null, $timezone = null)
    {
        if(is_null($time)) {
            return null;
        }

        $unix_time = null;

        if ($this->isTimestamp($time))
            $unix_time = Carbon::createFromTimestamp($time)->format('Y-m-d\TH:i:s');

        elseif (is_string($time))
            $unix_time = $time;

        elseif($time instanceof Carbon)
            $unix_time = $time->format('Y-m-d\TH:i:s');

        elseif($time instanceof Verta)
            $unix_time = Carbon::instance($time->datetime())->format('Y-m-d\TH:i:s');

        else
            $unix_time = carbon()->parse($time)->format('Y-m-d\TH:i:s');

        if (is_not_null($milliseconds))
            $unix_time = $unix_time . '.' . $timezone;

        if (is_not_null($timezone))
            return $unix_time . $timezone;
        else
            return $unix_time;
    }
}
