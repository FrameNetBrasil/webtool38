<?php

namespace App\View\Components\Combobox;

use App\Services\SemanticTypeService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SemanticType extends Component
{
    public $list;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public string $label,
        public string $placeholder = '',
        public string $root = ''
    )
    {
        $this->list = SemanticTypeService::listForComboGrid($root);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.combobox.semantic-type');
    }
}
