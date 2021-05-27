<?php
/**
 * Writtren by Farshad Hassani
 */

namespace Modules\Core\Contracts;

/**
 * interface Scheduler
 * 
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