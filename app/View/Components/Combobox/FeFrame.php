<?php

namespace App\View\Components\Combobox;

use App\Services\FrameService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;


class FeFrame extends Component
{
    public array $options;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public string $label,
        public int $idFrame,
        public ?string $value = null,
        public bool $hasNull = false
    )
    {
        $icon = config('webtool.fe.icon.grid');
        $fes = FrameService::listFEforSelect($this->idFrame);
        $this->options = [];
        if ($this->hasNull) {
            $this->options[] = [
                'idFrameElement' => '',
                'name' => "Select FE",
                'icon' => '',
                'color' => "color_1"
            ];
            $this->options[] = [
                'idFrameElement' => '-1',
                'name' => "NULL",
                'icon' => 'material-icons-outlined wt-icon wt-icon-null',
                'color' => "color_1"
            ];
        }
        foreach($icon as $i => $j) {
            foreach ($fes as $fe) {
                if ($fe['coreType'] == $i) {
                    $this->options[] = [
                        'idFrameElement' => $fe['idFrameElement'],
                        'name' => $fe['name'],
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
        return view('components.combobox.fe-frame');
    }
}
