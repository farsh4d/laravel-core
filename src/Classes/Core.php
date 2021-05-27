<?php

namespace Modules\Core\Classes;


use Modules\Core\Database\Seeder;

/**
 * Class Core
 * @package Modules\Core\Classes
 */
class Core
{
    /**
     * @param string $model
     * @param array $data
     * @param bool $truncate
     * @param bool $ignore_count
     * @return bool
     */
    public function seeder(string $model, array $data, $truncate = false, $ignore_count = false)
    {
        return (new Seeder($model, $data, $truncate, $ignore_count))->run();
    }
}