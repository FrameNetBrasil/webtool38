<?php

namespace App\View\Components\Combobox;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Repositories\SemanticType as SemanticTypeRepository;

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
        $st = new SemanticTypeRepository();
        $this->list = $st->listForComboGrid($root);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.combobox.semantic-type');
    }
}
