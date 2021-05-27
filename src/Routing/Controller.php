<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Routing;


use Modules\Core\Eloquent\Model;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;

/**
 * Class Controller
 *
 * @package Modules\Core\Routing
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs;

    protected $module_name = false;
    protected $view_folder = false;
    protected $model       = false;

    /**
     * @param int  $id
     * @param bool $with_trashed
     *
     * @return Model
     */
    protected function model($id = 0, $with_trashed = false)
    {
        return model($this->model, $id, $with_trashed);
    }
}