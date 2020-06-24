<?php

namespace BladeScript;

use Illuminate\View\View;
use BladeScript\Contracts\ScriptEngine;

class Script
{
    /**
     * Path where style is located.
     *
     * @var string
     */
    protected $path;

    /**
     * Style engine.
     *
     * @var \BladeStyle\Contracts\StyleEngine
     */
    protected $engine;

    /**
     * Create new style instance.
     *
     * @param  string  $name
     * @param  ScriptEngine  $compiler
     * @return void
     */
    public function __construct($path, ScriptEngine $engine)
    {
        $this->path = $path;
        $this->engine = $engine;
    }

    /**
     * Render style.
     *
     * @return string
     */
    public function render()
    {
        return $this->engine->get($this->path);
    }
}
