<?php

namespace BladeScript\Engines;

use Illuminate\Support\Facades\File;
use BladeScript\Contracts\ScriptEngine;
use BladeScript\Compiler\ScriptCompiler;
use Illuminate\View\Compilers\CompilerInterface;

class CompilerEngine implements ScriptEngine
{
    /**
     * Script compiler instance.
     *
     * @var \BladeScript\Compiler\ScriptCompiler
     */
    protected $compiler;

    /**
     * Create a new Blade view engine instance.
     *
     * @param  \BladeScript\Compiler\ScriptCompiler  $compiler
     * @return void
     */
    public function __construct(ScriptCompiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * Get compiled style from the given path.
     *
     * @param string $path
     * @return void
     */
    public function get(string $path)
    {
        if ($this->compiler->isExpired($path)) {
            $this->compiler->compile($path);
        }

        return File::get($this->compiler->getCompiledPath($path));
    }

    /**
     * Get compiler.
     *
     * @return \BladeScript\Compiler\ScriptCompiler
     */
    public function getCompiler()
    {
        return $this->compiler;
    }
}
