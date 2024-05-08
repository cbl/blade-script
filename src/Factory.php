<?php

namespace BladeScript;

use BladeScript\Contracts\ScriptEngine;

class Factory
{
    /**
     * Style stack.
     *
     * @var array
     */
    protected $stack = [];

    /**
     * Rendered styles.
     *
     * @var array
     */
    protected $rendered = [];

    /**
     * Engine resolver.
     *
     * @var \BladeScript\Contracts\ScriptEngine
     */
    protected $engine;

    /**
     * Create new Factory instance.
     *
     * @param ScriptEngine $resolver
     */
    public function __construct(ScriptEngine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Create style instance from view.
     *
     * @param  string $path
     * @return Script
     */
    public function make($path)
    {
        if ($this->inStack($path)) {
            return $this->stack[$path];
        }

        $script = new Script($path, $this->engine);

        // Adding style to stack.
        $this->stack[$path] = $script;

        return $script;
    }

    /**
     * Determine if style has been created.
     *
     * @param  string $path
     * @return bool
     */
    public function inStack($path)
    {
        return array_key_exists($path, $this->stack);
    }

    /**
     * Render stack.
     *
     * @param  bool   $flat
     * @return string
     */
    public function render()
    {
        $styles = '';

        foreach ($this->stack as $path => $style) {
            $styles .= "\n".$style->render();
        }

        return "\n<script>{$styles}</script>\n";
    }

    /**
     * Determine if path has been rendered.
     *
     * @param  string $path
     * @return bool
     */
    public function isRendered($path)
    {
        return array_key_exists($path, $this->rendered);
    }

    /**
     * Wrap style when not flat.
     *
     * @param  string $style
     * @param  bool   $flat
     * @return string
     */
    protected function wrap(string $style, $flat = false)
    {
        if (trim($style) == '') {
            return;
        }

        return "<script>{$style}</script>";
    }

    /**
     * Determine wether new styles are discovered that can be included.
     *
     * @return bool
     */
    public function hasNew()
    {
        foreach ($this->stack as $path => $style) {
            if (! $this->isRendered($path)) {
                return true;
            }
        }

        return false;
    }
}
