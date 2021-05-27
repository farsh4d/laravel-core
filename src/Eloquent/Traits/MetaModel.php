<?php

namespace Modules\Core\Eloquent\Traits;


use Illuminate\Support\Str;
use Illuminate\Support\Collection;

/**
 * Trait MetaModel
 * 
 * @package Modules\Core\Eloquent\Traits
 */
trait MetaModel
{
    /**
     * @param array $data
     *
     * @return Collection
     */
    protected function metSave(array $data)
    {
        // If has meta key we set is in meta attribute
        $data_meta = $data['meta'] ?? [];

        // Make as a Collection
        $data = collect($data);

        // Get meta vars
        $meta_vars = $this->metaVars();

        $meta = collect($meta_vars)
            ->map(function($value) use ($data) {
                if($data->has($value))
                    return [$value => $data->get($value)];

                return false;
            })
            ->collapse()
            ->toArray()
        ;

        $data['meta'] = $this->combineWithMeta($meta);
        
        // combine data_meta with meta
        if(!empty($data_meta) && is_array($data_meta)) {
            $data['meta'] = array_merge($data['meta'], $data_meta);
        }
        
        $data['meta'] = serialize($data['meta']);
        return $data->forget($meta_vars)->toArray();
    }


    /**
     * @return array
     */
    protected function metaVars()
    {
        $meta_vars = [];
        $methods   = get_class_methods($this);

        $methods = collect($methods)->reject(function($method) {
            return !Str::contains($method, ['MetaFields',
                                           'metaFields']);
        })->toArray();

        foreach($methods as $method) {
            $meta_vars = array_merge($meta_vars, $this->$method());
        }

        return $meta_vars;
    }


    /**
     * combine array with meta
     *
     * @param array $data
     * @return array
     */
    protected function combineWithMeta(array $data = [])
    {
        return empty($data) ? $this->meta : array_merge($this->meta, $data);
    }

    /**
     * @return array
     */
    public function getMetaAttribute()
    {
        $meta = $this->attributes['meta'] ?? null;
        
        if(is_array($meta)) {
            return $meta;
        }

        if($meta === null || trim($meta) === '') {
            return [];
        }

        // According to the previous version, We save JSON data in meta column,
        // So to prevent any issue this section added.
        if(is_json($meta)) {
            return json_decode($meta);
        }

        return unserialize($meta);
    }

    public function getMet($key, $default = null)
    {
        if(array_key_exists($key, $this->meta)) {
            return $this->meta[$key];
        }

        return $default;
    }
}