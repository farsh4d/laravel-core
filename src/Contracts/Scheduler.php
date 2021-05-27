<?php

namespace Modules\Core\Contracts;

/**
 * Interface Scheduler
 * @package Modules\Core\Contracts
 */
interface Scheduler
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function job();

    /**
     * Job running time
     */
    public function frequency();

    /**
     * Job additional running time limit
     */
    public function additional();
}