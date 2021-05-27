<?php

namespace Modules\Core\Console\Scheduling;


use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\CallbackEvent;
use Modules\Core\Contracts\Scheduler as SchedulerContract;

/**
 * Class Scheduler
 * @package Modules\Core\Console\Scheduling
 */
abstract class Scheduler implements SchedulerContract
{
    protected $schedule;
    
    protected $without_overlapping = false;

    /**
     * Construct of Scheduler
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Handle Schedule
     */
    public function handle()
    {
        $frequency           = Str::before($this->frequency(), ':');
        $frequency_switches  = explode(',', Str::after($this->frequency(), ':'));
        $additional          = Str::before($this->additional(), ':');
        $additional_switches = explode(',', Str::after($this->additional(), ':'));

        $callback_event = $this->schedule
            ->call(function () {
                $this->{"job"}();
            })
            ->$frequency(... $frequency_switches)
            ->$additional(... $additional_switches)
            ->name($this->getName())
            ->when($this->condition())
        ;

        if ($this->withoutOverlapping()) {
            $callback_event->withoutOverlapping();
        }

        $this->customHandle($callback_event);
    }

    /**
     * Custom Handle for Additional Options
     */
    protected function customHandle($callback_event)
    {
        // Custom handle schedule for additional options
    }

    /**
     * @return string
     */
    public function frequency()
    {
        return 'dailyAt:2:00';
    }

    /**
     * @return string
     */
    public function additional()
    {
        return 'between:0:00,23:59';
    }

    /**
     * @return bool
     */
    protected function condition()
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function withoutOverlapping()
    {
        return $this->without_overlapping;
    }

    /**
     * Override this if you need custom name for scheduler
     * 
     * @return string
     */
    protected function getName()
    {
        return (new ReflectionClass($this))->getShortName();
    }
}