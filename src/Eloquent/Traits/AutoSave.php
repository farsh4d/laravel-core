<?php

namespace Modules\Core\Eloquent\Traits;


use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Trait AutoSave
 * @package Modules\Core\Eloquent\Traits
 */
trait AutoSave
{
    /**
     * @param array $data
     *
     * @return array
     */
    private function ignoreData(array $data)
    {
        return collect($data)->reject(function($value, $key) {
            if($this->hasSetMutator($key)) {
                return false;
            }

            return $key[0] == '_' ?? false;
        })->toArray();
    }


    /**
     * @param array $data
     *
     * @return array
     */
    private function correctData(array $data)
    {
        $data = $this->ignoreData($data);
        
        $column_listing = $this->getColumnListing();
        $correct_data   = [];
        
        foreach($data as $item => $value) {
            if(in_array($item, $column_listing)) {
                $correct_data[$item] = $value;
            }
            
            if($this->hasSetMutator($item)) {
                $this->{"set".Str::studly(Str::camel($item))."Attribute"}($value);
            }
        }

        return $correct_data;
    }

    private function ignoreUsingTimestamps()
    {
        // ignore to use default timestamp
        $this->timestamps = false;
    }


    /**
     * Add CREATED_AT and UPDATED_AT if not exists
     * 
     * @param array $data
     * 
     * @return void
     */
    private function checkTimestamps($data)
    {
        if(array_key_exists(static::CREATED_AT, $data)) {
            $this->setCreatedAt($data[static::CREATED_AT]);
        }

        if(array_key_exists(static::UPDATED_AT, $data)) {
            $this->setUpdatedAt($data[static::UPDATED_AT]);
        }
    }


    /**
     * @param array $data
     *
     * @return bool
     */
    public function autoSave(array $data)
    {
        // Save Meta Variables in meta column and
        // Remove extra variables
        $data = $this->metSave($data);

        $this->checkTimestamps($data);

        //Correct Data : Ignore variables not exists in columns or with a "_" prefix
        $data = $this->correctData($data);

        $this->fill($data);

        return $this->save();
    }


    /**
     * @param array $data
     *
     * @return integer
     */
    public function autoSaveId(array $data)
    {
        $this->autoSave($data);

        return $this->id;
    }


    /**
     * @param array $data
     *
     * @return $this
     */
    public function autoSaveObject(array $data)
    {
        $this->autoSave($data);

        return $this;
    }
}