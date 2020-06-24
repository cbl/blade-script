<?php

namespace BladeScript\Compiler;

use BladeScript\Contracts\Transpiler;
use Illuminate\Filesystem\Filesystem;
use BladeScript\Engines\MinifierEngine;
use Illuminate\View\Compilers\CompilerInterface;
use Illuminate\View\Compilers\Compiler as ViewCompiler;

class ScriptCompiler extends ViewCompiler implements CompilerInterface
{
    /**
     * Minifier engine.
     *
     * @var \BladeScript\Engines\MinifierEngine
     */
    protected $engine;

    /**
     * Transpiler stack.
     *
     * @var array
     */
    protected $transpiler = [];

    /**
     * Create a new compiler instance.
     *
     * @param  \BladeScript\Engines\MinifierEngine $engine
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string  $cachePath
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(MinifierEngine $engine, Filesystem $files, $cachePath)
    {
        parent::__construct($files, $cachePath);

        $this->engine = $engine;
    }

    /**
     * Add transpiler.
     *
     * @return void
     */
    public function addTranspiler(Transpiler $transpiler)
    {
        $this->transpiler[] = $transpiler;
    }

    /**
     * Compile the style at the given path.
     *
     * @param  string  $path
     * @return void
     */
    public function compile($path)
    {
        $script = $this->compileScript(
            $this->getRaw($path)
        );

        if (config('script.minify')) {
            $script = $this->engine->minify($script);
        }

        $this->files->put(
            $this->getCompiledPath($path),
            $script
        );
    }

    /**
     * Compile script.
     *
     * @param string $script
     * @return string
     */
    public function compileScript(string $script)
    {
        foreach ($this->transpiler as $transpiler) {
            $script = $transpiler->transpile($script);
        }

        return $script;
    }

    /**
     * Get raw style from path.
     *
     * @param string $path
     * @return string|null
     */
    public function getRaw($path)
    {
        return $this->getStyleFromString(
            $this->files->get($path)
        );
    }

    /**
     * Get style from string.
     *
     * @param string|null $string
     * @return string
     */
    protected function getStyleFromString(string $string)
    {
        preg_match('/<x-script [^>]*>(.|\n)*?<\/x-script>/', $string, $matches);

        if (empty($matches)) {
            preg_match('/<x-script>(.|\n)*?<\/x-script>/', $string, $matches);
        }

        if (empty($matches)) {
            return;
        }

        return preg_replace('/<[^>]*>/', '', $matches[0]);
    }
}
