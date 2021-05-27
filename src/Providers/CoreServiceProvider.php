<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Providers;


use Hashids\Hashids;
use Modules\Core\View\FileViewFinder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Support\OverrideService;
use Illuminate\View\Factory as ViewFactory;
use Modules\Core\Support\ModuleServiceProvider;
use Modules\Core\Console\Commands\AssetsShortcuts;

/**
 * Class CoreServiceProvider
 *
 * @package Modules\Core\Providers
 */
class CoreServiceProvider extends ModuleServiceProvider
{
    protected $commands = [
        AssetsShortcuts::class,
    ];
    
    public function register()
    {
        $this->registerModuleServices();
        $this->mergeConfig('modules.php');
        $this->mergeConfig('hashids.php');
        $this->registerBind();
        $this->registerHelpers();

        $this->registerModulesServiceProviders();

        $this->commands($this->commands);
    }

    public function boot()
    {
        $this->publishConfig('modules.php');
        $this->publishConfig('hashids.php');

        $this->loadTranslationsFrom($this->getPath('Resources/lang'), $this->getLowerModuleName());
    }

    private function registerHelpers()
    {
        $this->loadHelper('Helpers/constVariables.php');
        $this->loadHelper('Helpers/general.php');
    }

    private function registerModulesServiceProviders()
    {
        // Not correct approch because service provider registered before core provider
        //
        
        // $providers = config('modules.providers', []);

        // foreach($providers as $provider) {
        //     $this->app->register($provider);
        // }
    }

    private function registerBind()
    {
        $this->app->bind('hashids', function() {
            $salt            = config('hashids.salt');
            $min_hash_length = config('hashids.min_hash_length');
            $alphabet        = config('hashids.alphabet');
            
            return new Hashids(
                $salt, $min_hash_length, $alphabet
            );
        });
    }

    protected function registerModuleServices()
    {
        $this->app->alias(OverrideService::class, 'overrideService');
    }
}