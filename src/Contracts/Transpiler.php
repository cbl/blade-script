<?php

namespace BladeScript\Contracts;

interface Transpiler
{
    /**
     * Transpile script and return result.
     *
     * @param string $script
     * @return string
     */
    public function transpile($script);
}
