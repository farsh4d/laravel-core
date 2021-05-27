<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Class AssetsShortcuts
 *
 * @package Modules\Core\Console\Commands
 */
class AssetsShortcuts extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'modules:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates shortcuts for all module assets.';



    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->assetLinks();
    }



    protected function assetLinks()
    {
        $filesystem = new Filesystem();

        $path = public_path("modules");

        if (!is_dir($path)) {
            mkdir($path);
        }

        $files = glob($path . DIRECTORY_SEPARATOR . '*');
        foreach ($files as $file) {
            if (is_file($file) or is_link($file)) {
                unlink($file);
            }
        }

        $modules = config('modules.providers');

        foreach ($modules as $module) {
            $module      = (new $module(app()));
            $module_name = $module->getLowerModuleName();
            
            $target = $module->getPath('Resources/assets');
            $link   = public_path("modules" . DIRECTORY_SEPARATOR . $module_name);

            $filesystem->link($target, $link);
            $this->info("Asset created for [$module_name]");
        }
    }
}
