<?php

namespace BladeScript\Minifier;

use MatthiasMullie\Minify;
use BladeScript\Contracts\ScriptMinifier;

class MullieMinifier implements ScriptMinifier
{
    /**
     * Minify script.
     *
     * @param string $script
     * @return string
     */
    public function minify(string $script)
    {
        $mullie = $this->getMullieMinifier();

        $mullie->add($script);

        return $mullie->minify();
    }

    /**
     * Get Matthias mullie javascript minifier.
     *
     * @see https://github.com/matthiasmullie/minify
     *
     * @return MatthiasMullie\Minify\JS
     */
    public function getMullieMinifier()
    {
        return new Minify\JS();
    }
}
