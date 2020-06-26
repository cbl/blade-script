<?php

namespace BladeScript\Engines;

use BladeScript\Contracts\ScriptMinifier;

class MinifierEngine
{
    /**
     * Minifier.
     *
     * @var \BladeScript\Contracts\ScriptMinifier
     */
    protected $minifier;

    /**
     * Create new MinifierEngine instance.
     *
     * @param CssMinifier $minifier
     */
    public function __construct(ScriptMinifier $minifier)
    {
        $this->minifier = $minifier;
    }

    /**
     * Set minifier.
     *
     * @param CssMinifier $minifier
     * @return void
     */
    public function setMinifier(ScriptMinifier $minifier)
    {
        $this->minifier = $minifier;
    }

    /**
     * Minify css string
     *
     * @return string
     */
    public function minify(string $script)
    {
        return $this->minifier->minify($script);
    }
}
