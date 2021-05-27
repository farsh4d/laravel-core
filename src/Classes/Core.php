<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Classes;


use Modules\Core\Database\Seeder;

/**
 * Class Core
 *
 * @package modules\core\classes
 */
class Core
{
    /**
     * @param string $model
     * @param array  $data
     *
     * @return bool
     */
    public function seeder(string $model, array $data, $truncate = false, $ignore_count = false)
    {
        return (new Seeder($model, $data, $truncate, $ignore_count))->run();
    }
}