<?php

namespace Ravenberg\InertiaReactPreset;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\PresetCommand;
use Illuminate\Support\ServiceProvider;

class InertiaReactPresetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        PresetCommand::macro('inertia-react', function(Command $command) {

            InertiaReactPreset::install($command);
            $command->info('ok done');
        });
    }
}
