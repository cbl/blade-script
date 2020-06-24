<?php

namespace BladeScript\Components;

use BladeScript\Factory;
use Illuminate\View\Component;

class ScriptsComponent extends Component
{
    /**
     * Factory instance.
     *
     * @var \BladeScript\Factory
     */
    public $scripts;

    /**
     * Create ScriptsComponent instance.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $scripts)
    {
        $this->scripts = $scripts;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return $this->scripts->render();
    }
}
