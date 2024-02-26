<?php

namespace App\View\Components\Checkbox;

use App\Repositories\Frame;
use App\Repositories\SemanticType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FramalDomain extends Component
{
    public function __construct(
        public string $id,
        public string $label,
        public int    $idFrame,
        public array  $options = []
    )
    {
        $frame = new Frame($this->idFrame);
        $classification = $frame->getClassification()['rel_framal_domain'];
        $names = $classification->pluck('name')->all();

        $st = new SemanticType();
        $domains = $st->listFrameDomain()->getResult();
        $this->options = [];
        foreach ($domains as $domain) {
            $this->options[] = [
                'value' => $domain['idSemanticType'],
                'name' => $domain['name'],
                'checked' => in_array($domain['name'], $names) ? 'checked' : '',
                'disable' => false,
            ];
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.checkbox.framal-domain');
    }
}
