<?php

namespace Tests;

use Illuminate\Support\Facades\File;

class BladeScriptTest extends TestCase
{
    /** @test */
    public function it_doesnt_render_script()
    {
        $view = $this->getView('foo', '<x-script>console.log("Foo");</x-script>');
        $this->assertEquals('', $view->render());
    }

    /** @test */
    public function test_scripts_renders_script_tag()
    {
        $view = $this->getView('foo', '<x-scripts/>');
        $this->assertStringContainsString('<script></script>', $view->render());
    }

    /** @test */
    public function it_puts_scripts_to_script_tag()
    {
        $this->getView('foo', '<x-script>console.log("Foo");</x-script>');
        $this->getView('bar', '<x-script>console.log("Bar");</x-script>');
        $view = $this->getView('baz', '@include(\'foo\')@include(\'bar\')<x-scripts/>');

        $this->assertStringContainsString(
            "<script>\nconsole.log(\"Foo\");\nconsole.log(\"Bar\");</script>", $view->render()
        );
    }

    /** @test */
    public function it_stores_scripts_to_storage()
    {
        $view = $this->getView('foo', '<x-script>console.log("Foo");</x-script><x-scripts/>');
        $view->render();
        $path = storage_path('framework/scripts/'.sha1($view->getPath()).'.js');
        $this->assertTrue(File::exists($path));
        $this->assertSame('console.log("Foo");', File::get($path));
    }

    /** @test */
    public function test_script_clear_command()
    {
        $view = $this->getView('foo', '<x-script>console.log("Foo")</x-script><x-scripts/>');
        $view->render();
        $path = storage_path('framework/scripts/'.sha1($view->getPath()).'.js');
        $this->assertTrue(File::exists($path));
        $this->artisan('script:clear');
        $this->assertFalse(File::exists($path));
    }

    /** @test */
    public function test_script_cache_command()
    {
        $view = $this->getView('foo', '<x-script>body{background:red}</x-script>');
        $path = storage_path('framework/scripts/'.sha1($view->getPath()).'.js');
        $this->assertFalse(File::exists($path));
        $this->artisan('script:cache');
        $this->assertTrue(File::exists($path));
    }

    /** @test */
    public function it_can_minify_scripts()
    {
        $this->app['config']->set('script.minify', true);
        $view = $this->getView('foo', '<x-script>true</x-script>');
        $path = storage_path('framework/scripts/'.sha1($view->getPath()).'.js');
        $this->artisan('script:cache');

        // true => !0
        $this->assertSame('!0', File::get($path));
    }

    /** @test */
    public function it_break_line_between_scripts()
    {
        $this->getView('foo', '<x-script>true</x-script>');
        $this->getView('bar', '<x-script>false</x-script>');
        $view = $this->getView('baz', '@include(\'foo\')@include(\'bar\')<x-scripts/>');

        $this->assertStringContainsString(
            "<script>\ntrue\nfalse</script>", $view->render()
        );
    }
}
