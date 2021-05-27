<?php
/**
 * Written By Farshad Hassani
 */

namespace Modules\Core\Support;


use Illuminate\Support\Str;

class ModelResolver
{
    /**
     * Create new model instance
     *
     * @param string $class
     * @param integer $id
     * @param boolean $with_trashed
     * @return mixed
     */
    public function generate($class, $id = 0, $with_trashed = false)
    {
        $class = $this->findClassNamespace($class);
        
        $model = new $class();

        if($id === 0 || $id === '0') {
            return $model;
        }

        if(is_hashid($id)) {
            $id = hashid($id);
        }

        return $with_trashed
        ? $model->withTrashed()->findOrNew($id)
        : $model->findOrNew($id);
    }

    /**
     * Find class namespace with class name and module name
     *
     * @param string $class
     * @return string
     */
    private function findClassNamespace($class)
    {
        if(Str::contains($class, '::')) {
            // Str::before does exists in 5.5 and later
            // $module_name = Str::before($class,'::');
            $module_name = strstr($class, '::', true);
            $module_name = Str::studly($module_name);
            
            // Str::after does exists in 5.4 and later
            // $class_name = Str::after($class, '::');
            $class_name = strstr($class, '::');
            $class_name = str_replace('::', '', $class_name);
            $class_name = Str::studly($class_name);

            $class = MODULES_NAMESPACE . $module_name  . '\\' . ENTITY_NAMESPACE . $class_name;
        }

        return $class;
    }
}
