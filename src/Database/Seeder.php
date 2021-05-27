<?php

namespace Modules\Core\Database;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/**
 * Class Seeder
 * @package Modules\Core\Database
 */
class Seeder
{
    /**
     * @var array
     */
    private $data;
    /**
     * @var string
     */
    private $table_name;


    private $truncate = false;
    private $force    = false;


    /**
     * Seeder constructor.
     *
     * @param string $model
     * @param array  $data
     * @param bool   $truncate
     */
    public function __construct (string $model, array $data, $truncate = false, $force = false)
    {
        if(Str::contains($model, '::')) {
            $this->table_name = model($model)->getTable();
        }else if(class_exists($model)) {
            $this->table_name = (new $model)->getTable();
        }else {
            $this->table_name = $model;
        }
        
        $this->data     = $data;
        $this->truncate = $truncate;
        $this->force    = $force;
    }


    /**
     * @return bool
     */
    public function run()
    {
        $ok = true;

        $count = DB::table($this->table_name)->count();
        if($count > 0 && !$this->truncate && !$this->force) {
            return $ok;
        }

        if ($this->truncate) {
            DB::table($this->table_name)->truncate();
        }

        foreach ($this->data as $datum) {
            $ok &= DB::table($this->table_name)->insert($datum);
        }

        return $ok;
    }
}