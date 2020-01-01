<?php

namespace Ravenberg\InertiaReactPreset;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Foundation\Console\Presets\Preset;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InertiaReactPreset extends Preset
{
    /**
     * @param Command $command
     */
    public static function install(Command $command)
    {
        static::cleanResources();
        static::removeNodeModules();
        static::updatePackages();
        static::updateComposer();
        static::configureMix();

        static::removeDefaultViews();
        static::exportInertiaBladeView();
        static::exportReactViews();
        static::exportCssFiles();

        static::exportInertiaServiceProvider();
        static::registerInertiaServiceProvider();
        static::exportAuthControllers();
        static::updateRoutes();
    }

    /**
     * Removes the default front-end scaffolding.
     */
    public static function cleanResources()
    {
        File::deleteDirectory(resource_path('sass'));
        File::delete(resource_path('js/app.js'));
        File::delete(resource_path('js/bootstrap.js'));
    }

    /**
     * Update front-end (npm) dependencies
     *
     * @param $packages
     * @return array
     */
    protected static function updatePackageArray($packages)
    {
        $packagesToRemove = ['axios', 'lodash', 'sass', 'sass-loader'];
        $packagesToAdd = [
            '@babel/plugin-proposal-class-properties' => '^7.7.0',
            '@babel/preset-react' => '^7.0.0',
            '@inertiajs/inertia' => '^0.1.7',
            '@inertiajs/inertia-react' => '^0.1.4',
            'react' => '^16.2.0',
            'react-dom' => '^16.2.0',
            'prop-types' => '^15.7.2',
            'tailwindcss' => '^1.1.3',
            'postcss-nesting' => '^7.0.1',
            'postcss-import' => '^12.0.1',
            'browser-sync' => '^2.26.7',
            'browser-sync-webpack-plugin' => '^2.0.1',
        ];

        return array_merge($packagesToAdd, Arr::except($packages, $packagesToRemove));
    }

    /**
     * Rewrite the composer.json file
     */
    protected static function updateComposer()
    {
        $packages = json_decode(file_get_contents(base_path('composer.json')), true);
        $packages['require'] = static::updateComposerArray($packages['require']);
        ksort($packages['require']);

        file_put_contents(
            base_path('composer.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    /**
     * Get all needed composer packages for including the preset ones
     *
     * @param array $packages
     * @return array
     */
    protected static function updateComposerArray(array $packages)
    {
        return array_merge(['inertiajs/inertia-laravel' => '^0.1'], $packages);
    }

    /**
     * Override the webpack.mix.js file with the preset's one
     */
    public static function configureMix()
    {
        File::copy(__DIR__.'/stubs/webpack.mix.js', base_path('webpack.mix.js'));
    }

    /**
     * Export the preset's react views
     */
    public static function exportReactViews()
    {
        File::copyDirectory(__DIR__.'/stubs/resources/js', resource_path('js'));
    }

    /**
     * Export the preset's css files (tailwind)
     */
    public static function exportCssFiles() {
        File::copyDirectory(__DIR__.'/stubs/resources/css', resource_path('css'));
    }

    /**
     * Export a separate service provider for Inertia
     */
    public static function exportInertiaServiceProvider()
    {
        File::copy(
            __DIR__.'/stubs/providers/InertiaServiceProvider.stub',
            app_path('Providers/InertiaServiceProvider.php')
        );
    }

    /**
     * Configure the InertiaServiceProvider in app.php
     */
    public static function registerInertiaServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', Container::getInstance()->getNamespace());
        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\InertiaServiceProvider::class')) {
            return;
        }

        // Write the InertiaServiceProvider's FQCN below the RouteServiceProvider
        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];
        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];
        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\\RouteServiceProvider::class,".$eol,
            "{$namespace}\\Providers\\RouteServiceProvider::class,".$eol."        {$namespace}\Providers\InertiaServiceProvider::class,".$eol,
            $appConfig
        ));

        // Update namespace in InertiaServiceProvider
        file_put_contents(app_path('Providers/InertiaServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/InertiaServiceProvider.php'))
        ));
    }

    /**
     * Remove framework's default views
     */
    public static function removeDefaultViews()
    {
        File::delete(resource_path('/views/home.blade.php'));
        File::delete(resource_path('/views/welcome.blade.php'));
    }

    /**
     * Export the single Inertia blade view
     */
    public static function exportInertiaBladeView()
    {
        File::copy(__DIR__.'/stubs/resources/views/app.blade.stub', resource_path('/views/app.blade.php'));
    }

    /**
     * Export the Auth controllers that override the default ones that try to render blade views.
     */
    public static function exportAuthControllers()
    {
        File::delete(app_path('Http/Controllers/LoginController.php'));
        File::copy(__DIR__.'/stubs/Controllers/Auth/LoginController.stub', app_path('Http/Controllers/Auth/LoginController.php'));
        File::delete(app_path('Http/Controllers/ForgotPasswordController.php'));
        File::copy(__DIR__.'/stubs/Controllers/Auth/ForgotPasswordController.stub', app_path('Http/Controllers/Auth/ForgotPasswordController.php'));
        File::delete(app_path('Http/Controllers/ResetPasswordController.php'));
        File::copy(__DIR__.'/stubs/Controllers/Auth/ResetPasswordController.stub', app_path('Http/Controllers/Auth/ResetPasswordController.php'));
    }

    /**
     * Export the preset's routes
     */
    public static function updateRoutes()
    {
        File::copy(__DIR__.'/stubs/routes/web.stub', base_path('/routes/web.php'));
    }
}
