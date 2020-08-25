<?php

namespace Tests;

use BladeScript\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function tearDown(): void
    {
        $this->artisan('script:clear');
        $this->artisan('view:clear');

        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * Create view that renders the given content.
     *
     * @param  string $name
     * @param  string $content
     * @param  array  $data
     * @return string
     */
    public function getView($name, $content, $data = [])
    {
        File::put($path = resource_path("views/{$name}.blade.php"), $content);

        return view($name);

        return new View(
            $this->app['view'],
            $this->app['view.engine.resolver']->resolve('blade'),
            $name, $path, $data
        );
    }
}
