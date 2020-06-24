<?php

namespace BladeScript\Contracts;

interface ScriptEngine
{
    /**
     * Get compiled script from path.
     *
     * @param string $path
     * @return string
     */
    public function get(string $path);
}
