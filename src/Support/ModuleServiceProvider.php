<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Support;


use FilesystemIterator;
use Illuminate\Support\Arr;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class ModuleServiceProvider
 *
 * Base of ModuleProviders.
 * Warning: Dont scrap files to load them automaticly. 
 * 
 * @package Modules\Core\Providers
 */
abstract class ModuleServiceProvider extends ServiceProvider
{
    protected $listen    = [];
    protected $subscribe = [];

    protected $namespace;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->namespace   = 'Modules';
    }

    /**
     * Get The path of module
     *
     * @param boolean $additive
     * @return string
     */
    public function getPath($additive = false)
    {
        $path = dirname(dirname((new \ReflectionClass(static::class))->getFileName()));
        
        return $additive 
            ? $path . DIRECTORY_SEPARATOR . $additive 
            : $path;
    }

    /**
     * Route loader
     * Load route of a module
     * 
     * @param string $file
     * @param array  $middleware
     */
    public function loadRoute($file, array $attributes = [])
    {
        $namespace  = $this->namespace . '\\'.$this->getUcfirstModuleName()."\\Http\\Controllers";
        
        $holder = [
            'middleware' => 'web',
            'namespace'  => $namespace
        ];
        

        Route::group(array_merge($holder, $attributes), $this->getPath($file));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getUcfirstModuleName()
    {
        return ucfirst($this->getModuleName());    
    }

    public function getLowerModuleName()
    {
        return strtolower($this->getModuleName());
    }

    public function getModuleName()
    {
        $namespace = explode('\\', get_class($this));
        
        return $namespace[1];
    }

    /**
     * A simple function to boot event-listener
     *
     * @param string $event
     * @param string $listener
     */
    protected function listen($event, $listener)
    {
        Event::listen($event, $listener);
    }

    protected function addPolicy($model, $policy)
    {
        Gate::policy($model, $policy);
    }

    /**
     * A simple function to boot subcriber
     * 
     * @param object|string subscriber
     */
    protected function subscribe($subscriber)
    {
        Event::subscribe($subscriber);
    }

    protected function middleware($alias, $class)
    {
        $router = $this->app['router'];

        if(method_exists($router, 'aliasMiddleware')) {
            $router->aliasMiddleware($alias, $class);
        } else {
            $router->middleware($alias, $class);
        }
    }

    protected function loadHelper($file_path)
    {
        require_once $this->getPath($file_path);
    }

    /**
     * Add a command to schedule
     * 
     * @return void
     */
    protected function addSchedule($class)
    {
        $this->app->booted(function () use($class) {
            $schedule = $this->app->make(Schedule::class);
            (new $class($schedule))->handle(); 
        });
    }
    
    /**
     * Publish config file
     *
     * @param string $file
     * @param string $path
     * @return void
     */
    protected function publishConfig($file, $path = 'Config')
    {
        $path        = $this->getPath($path);
        $source_file = $path . DIRECTORY_SEPARATOR . $file;

        if(!file_exists($source_file)) {
            throw new FileNotFoundException("The $file not exists in $path");
        }
        
        $this->publishes([
            $source_file => config_path($file),
        ], 'config');
    }

    /**
     * Merge config file with default config
     *
     * @param string $file
     * @param string $path
     * @return void
     */
    protected function mergeConfig($file, $path = 'Config', $recursively = false)
    {
        $source_file = $this->getPath($path) . DIRECTORY_SEPARATOR . $file;

        if(!file_exists($source_file)) {
            throw new FileNotFoundException("The $file not exists in $path");
        }
        
        //TODO Find a better way
        // $key  = basename($file, '.php');
        $key  = pathinfo($file, PATHINFO_FILENAME);;

        $this->mergeConfigFrom($source_file, $key, $recursively);
    }

    /**
     * Merge the given configuration with the existing configuration with recursively option.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key, $recursively = false)
    {
        $config = $this->app['config']->get($key, []);

        $value = $recursively 
            ? $this->mergeConfigs(require $path, $config)
            : array_merge(require $path, $config)
        ;

        $this->app['config']->set($key, $value);
    }

    /**
     * Merge recursively the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    private function mergeConfigs($merging, $original)
    {
        $array = array_merge($original, $merging);
        
        foreach ($original as $key => $value) {
            if (!is_array($value)) {
                continue;
            }

            if (!Arr::exists($merging, $key)) {
                continue;
            }

            if (is_numeric($key)) {
                continue;
            }

            $array[$key] = $this->mergeConfigs($value, $merging[$key]);
        }

        return $array;
    }

    /**
     * Load views
     *
     * @return void
     */
    protected function loadViews()
    {
        $view_path   = resource_path('views/modules/' . $this->getLowerModuleName());
        $source_path = $this->getPath("Resources/views");

        $this->publishes([
            $source_path => $view_path,
        ], 'views');

        $merged_views = collect(Config::get('view.paths'))
            ->map(function($path) {
                return $path . DIRECTORY_SEPARATOR .'modules' . DIRECTORY_SEPARATOR . $this->getLowerModuleName();
            })
            ->merge([$source_path])
            ->all()
        ;
        
        foreach($merged_views as $merged_view) {
            $this->loadViewsFrom($merged_view, $this->getLowerModuleName());
        }
    }

    protected function loadAlias($class, $alias)
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias($class, $alias);
    }
}