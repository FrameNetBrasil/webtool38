<?php

namespace App\View\Components\Checkbox;

use App\Repositories\Frame;
use App\Services\FrameService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FeFrame extends Component
{

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public string $label,
        public int $idFrame,
        public array $options = []
    )
    {
        $icon = config('webtool.fe.icon.tree');
        $frame = new Frame($idFrame);
        $fes = $frame->listFE()->asQuery()->getResult();
        $this->options = [];
        foreach($icon as $i => $j) {
            foreach ($fes as $fe) {
                if ($fe['coreType'] == $i) {
                    $this->options[] = [
                        'value' => $fe['idFrameElement'],
                        'name' => $fe['name'],
                        'disable' => false,
                        'icon' => $j,
                        'color' => "color_{$fe['idColor']}"
                    ];
                }
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.checkbox.fe-frame');
    }
}
