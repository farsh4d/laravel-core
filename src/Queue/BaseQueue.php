<?php

namespace Modules\Core\Queue;


use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * BaseQueue class
 */
class BaseQueue implements ShouldQueue
{
    public $force_queue = false;
    public $tries       = 1;
    public $timeout     = 60;

    use InteractsWithQueue;

    public function __get($name)
    {
        if ($name == 'queue') {
            if($this->force_queue !== false) {
                return config('queue.prefix') . $this->force_queue;
            }else {
                $class_name = (new \ReflectionClass($this))->getShortName();
                
                return config('queue.prefix') . $class_name;
            }
        }
    }
}