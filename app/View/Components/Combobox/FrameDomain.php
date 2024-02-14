<?php

namespace App\View\Components\Combobox;

use App\Repositories\SemanticType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FrameDomain extends Component
{
    public array $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public string $value,
        public string $label = '',
        public string $placeholder = ''
    )
    {
        $st = new SemanticType();
        $domains = $st->listFrameDomain()->getResult();
        $this->options = [];
        foreach ($domains as $domain) {
            $this->options[] = [
                'idSemanticType' => $domain['idSemanticType'],
                'name' => $domain['name'],
            ];
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.combobox.frame-domain');
    }
}
