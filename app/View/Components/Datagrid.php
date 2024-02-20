<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Datagrid extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public string $title,
        public string $type  = 'child',
        public string $height = '',
        public string $header = '',
        public string $extraTitle = '',
        public bool $center = false,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if ($this->type == 'child') {
            $this->height = "256px";
        }
        return view('components.datagrid');
    }
}
