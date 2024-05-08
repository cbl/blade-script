<?php

namespace BladeScript\Components;

use BladeScript\Factory;
use BladeScript\Script;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class ScriptComponent extends Component
{
    /**
     * Script language.
     *
     * @var string
     */
    public $lang;

    /**
     * Script instance.
     *
     * @var Script
     */
    public $script;

    /**
     * Script Factory instance.
     *
     * @var Factory
     */
    public $scriptFactory;

    /**
     * Create new StyleComponent instance.
     *
     * @param  Factory $factory
     * @param  string  $lang
     * @return void
     */
    public function __construct(Factory $factory, $lang = 'css')
    {
        $this->lang = $lang;
        $this->scriptFactory = $factory;

        $this->makeScript();
    }

    /**
     * Make script.
     *
     * @return void
     */
    public function makeScript()
    {
        $path = $this->getPathFromTrace();

        if (! $path) {
            return;
        }

        $this->script = $this->scriptFactory->make($path);
    }

    /**
     * Get path from trace.
     *
     * @return string
     */
    protected function getPathFromTrace()
    {
        foreach (debug_backtrace() as $trace) {
            if (! array_key_exists('file', $trace)) {
                continue;
            }

            if (! Str::startsWith($trace['file'], config('view.compiled'))) {
                continue;
            }

            return $this->getPathFromCompiled($trace['file']);
        }
    }

    /**
     * Get path from compiled view.
     *
     * @param  string $compiled
     * @return string
     */
    protected function getPathFromCompiled($compiled)
    {
        return trim(Str::between(File::get($compiled), '/**PATH', 'ENDPATH**/'));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return void
     */
    public function render()
    {
        //
    }
}
