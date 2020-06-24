<?php

namespace BladeScript\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;

class ScriptClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'script:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all compiled script files';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new config clear command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function handle()
    {
        $path = $this->laravel['config']['script.compiled'];

        if (!$path) {
            throw new RuntimeException('Script path not found.');
        }

        foreach ($this->files->glob("{$path}/*") as $script) {
            $this->files->delete($script);
        }

        $this->info('Compiled scripts cleared!');
    }
}
