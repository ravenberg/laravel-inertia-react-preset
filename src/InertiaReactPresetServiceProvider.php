<?php

namespace Ravenberg\InertiaReactPreset;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\PresetCommand;
use Illuminate\Support\ServiceProvider;

class InertiaReactPresetServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        PresetCommand::macro('inertia-react', function(Command $command) {
            InertiaReactPreset::install($command);
            $command->info('Inertiajs with Reactjs scaffolding complete.');
            $command->info('Don\'t forget to run npm install && npm run dev');
        });
    }
}
