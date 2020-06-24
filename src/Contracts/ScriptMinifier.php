<?php

namespace BladeScript\Contracts;

interface ScriptMinifier
{
    /**
     * Minify script.
     *
     * @return string
     */
    public function minify(string $script);
}
