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
    public static function install(Command $command)
    {

        static::cleanResources();
        static::updatePackages();
        static::updateComposer(false);
        static::configureMix();
        static::removeDefaultViews();
        static::exportInertiaBladeView();
        static::exportReactViews();
        static::exportCssFiles();
        static::publishInertiaServiceProvider();
        static::registerInertiaServiceProvider();
        static::updateRoutes();
        static::removeNodeModules();
    }

    public static function cleanResources()
    {
        File::deleteDirectory(resource_path('sass'));
    }

    protected static function updatePackageArray($packages)
    {
        $packagesToRemove = ['axios', 'lodash', 'sass', 'sass-loader'];
        $packagesToAdd = [
            '@babel/plugin-proposal-class-properties' => '^7.7.0',
            '@babel/preset-react' => '^7.0.0',
            '@inertiajs/inertia' => '^0.1.7.',
            '@inertiajs/inertia-react' => '^0.1.13',
            'react' => '^16.2.0',
            'react-dom' => '16.2.0',
            'prop-types' => '^15.7.2',
            'tailwindcss' => '^1.1.3',
            'postcss-nesting' => '7.0.1',
            'postcss-import' => '^12.0.1',
            'browser-sync' => '^2.26.7',
            'browser-sync-webpack-plugin' => '^2.0.1',
        ];

        return array_merge($packagesToAdd, Arr::except($packages, $packagesToRemove));
    }

    protected static function updateComposer($dev = true)
    {
        if (! file_exists(base_path('composer.json'))) {
            return;
        }

        $configurationKey = $dev ? 'require-dev' : 'require';
        $packages = json_decode(file_get_contents(base_path('composer.json')), true);
        $packages[$configurationKey] = static::updateComposerArray(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('composer.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    protected static function updateComposerArray(array $packages)
    {
        return array_merge(['inertiajs/inertia-laravel' => '^0.1'], $packages);
    }

    public static function configureMix()
    {
        File::copy(__DIR__.'/stubs/webpack.mix.js', base_path('webpack.mix.js'));
    }

    public static function exportReactViews()
    {
        File::copyDirectory(__DIR__.'/stubs/resources/js', resource_path('js'));
    }

    public static function exportCssFiles() {
        File::copyDirectory(__DIR__.'/stubs/resources/css', resource_path('css'));
    }

    public static function publishInertiaServiceProvider()
    {
        File::copy(
            __DIR__.'/stubs/providers/InertiaServiceProvider.stub',
            app_path('Providers/InertiaServiceProvider.php')
        );
    }

    public static function registerInertiaServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', Container::getInstance()->getNamespace());
        $appConfig = file_get_contents(config_path('app.php'));
        if (Str::contains($appConfig, $namespace.'\\Providers\\InertiaServiceProvider::class')) {
            return;
        }
        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];
        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];
        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\\RouteServiceProvider::class,".$eol,
            "{$namespace}\\Providers\\RouteServiceProvider::class,".$eol."{$namespace}\Providers\InertiaServiceProvider::class,".$eol,
            $appConfig
        ));
        file_put_contents(app_path('Providers/InertiaServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/InertiaServiceProvider.php'))
        ));
    }

    public static function removeDefaultViews()
    {
        File::delete(resource_path('/views/home.blade.php'));
        File::delete(resource_path('/views/welcome.blade.php'));
    }

    public static function exportInertiaBladeView()
    {
        File::copy(__DIR__.'/stubs/resources/views/app.blade.stub', resource_path('/views/app.blade.php'));
    }

    public static function updateRoutes()
    {
        File::copy(__DIR__.'/stubs/routes/web.stub', base_path('/routes/web.php'));
    }
}
