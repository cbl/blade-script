<?php

namespace BladeScript;

use BladeScript\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Blade;
use BladeScript\Engines\CompilerEngine;
use BladeScript\Engines\MinifierEngine;
use BladeScript\Compiler\ScriptCompiler;
use BladeScript\Minifier\MullieMinifier;
use BladeScript\Components\ScriptComponent;
use BladeScript\Commands\ScriptCacheCommand;
use BladeScript\Commands\ScriptClearCommand;
use BladeScript\Components\ScriptsComponent;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPublishes();

        if (!config('script.compiled') && $this->app->runningInConsole()) {
            return;
        }

        $this->registerMinifier();
        $this->registerMinifierEngine();

        $this->registerCompilerEngine();
        $this->registerScriptCompiler();

        $this->registerScriptClearCommand();
        $this->registerScriptCacheCommand();

        $this->registerFactory();

        $this->registerBladeComponents();
    }

    /**
     * Register blade components.
     *
     * @return void
     */
    public function registerBladeComponents()
    {
        Blade::component('script', ScriptComponent::class);
        Blade::component('scripts', ScriptsComponent::class);
    }

    /**
     * Register minifier.
     *
     * @return void
     */
    protected function registerMinifier()
    {
        $this->app->singleton('script.minifier.mullie', function ($app) {
            return new MullieMinifier;
        });
    }

    /**
     * Register minifier engine.
     *
     * @return void
     */
    protected function registerMinifierEngine()
    {
        $this->app->singleton('script.engine.minifier', function ($app) {
            return new MinifierEngine($app['script.minifier.mullie']);
        });
    }

    /**
     * Register style factory.
     *
     * @return void
     */
    public function registerFactory()
    {
        $this->app->singleton('script.factory', function ($app) {
            $engine = $app['script.engine.compiler'];

            return new Factory($engine);
        });

        $this->app->alias('script.factory', Factory::class);
    }

    /**
     * Register script compiler.
     *
     * @return void
     */
    public function registerScriptCompiler()
    {
        $this->app->singleton('script.compiler', function ($app) {
            return new ScriptCompiler(
                $app['script.engine.minifier'],
                $app['files'],
                config('script.compiled')
            );
        });
    }

    /**
     * Register script compiler engine.
     *
     * @return void
     */
    protected function registerCompilerEngine()
    {
        $this->app->singleton('script.engine.compiler', function ($app) {
            return new CompilerEngine($app['script.compiler']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerScriptClearCommand()
    {
        $this->app->singleton('command.script.clear', function ($app) {
            return new ScriptClearCommand($app['files']);
        });

        $this->commands(['command.script.clear']);
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerScriptCacheCommand()
    {
        $this->app->singleton('command.script.cache', function ($app) {
            return new ScriptCacheCommand($app['script.factory']);
        });

        $this->commands(['command.script.cache']);
    }

    /**
     * Register publishes.
     *
     * @return void
     */
    public function registerPublishes()
    {
        $this->publishes([
            __DIR__ . '/../storage/' => storage_path('framework/scripts')
        ], 'storage');

        $this->publishes([
            __DIR__ . '/../config/script.php' => config_path('script.php')
        ], 'config');
        
        if (!File::exists(storage_path('framework/scripts'))) {
            File::copyDirectory(__DIR__ . '/../storage/', storage_path('framework/scripts'));
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../config/script.php',
            'script'
        );
    }
}
