<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Routing;


use Illuminate\View\View;
use Modules\Core\Eloquent\Model;
use Modules\Core\Fractal\Fraction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class ApiController
 *
 * @package Modules\Core\Routing
 */
class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, Fraction;
}