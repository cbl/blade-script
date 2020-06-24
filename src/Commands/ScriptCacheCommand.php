<?php

namespace BladeScript\Commands;

use BladeScript\Factory;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Foundation\Console\ViewCacheCommand;

class ScriptCacheCommand extends ViewCacheCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'script:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Compile all of the application's Blade styles";

    /**
     * Style factory.
     *
     * @var \BladeScript\Factory
     */
    protected $script;

    /**
     * Create new StyleClearCommand instance.
     *
     * @param Factory $style
     */
    public function __construct(Factory $script)
    {
        parent::__construct();

        $this->script = $script;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('script:clear');

        $this->paths()->each(function ($path) {
            $this->compileScripts($this->bladeFilesIn([$path]));
        });

        $this->info('Blade scripts cached successfully!');
    }

    /**
     * Compile the given view files.
     *
     * @param  \Illuminate\Support\Collection  $views
     * @return void
     */
    protected function compileScripts(Collection $views)
    {
        $compiler = $this->laravel['script.compiler'];

        $views->map(function (SplFileInfo $file) use ($compiler) {
            $compiler->compile($file);
        });
    }
}
