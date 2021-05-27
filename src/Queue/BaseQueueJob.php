<?php

namespace Modules\Core\Queue;


use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * BaseQueueJob class
 */
class BaseQueueJob implements ShouldQueue
{
    public $force_queue = false;
    public $tries       = 1;
    public $timeout     = 60;

    use Dispatchable, InteractsWithQueue, SerializesModels, Queueable;

    public function __construct()
    {
        if($this->force_queue !== false) {
            $this->queue = config('queue.prefix') . $this->force_queue;
        }else {
            $class_name = (new \ReflectionClass($this))->getShortName();
            
            $this->queue = config('queue.prefix') . $class_name;
        }
    }
}