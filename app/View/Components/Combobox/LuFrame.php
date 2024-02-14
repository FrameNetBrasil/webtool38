<?php

namespace App\View\Components\Combobox;

use App\Services\FrameService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LuFrame extends Component
{
    public array $options;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string  $id,
        public string  $label,
        public int     $idFrame,
        public ?string $value = null
    )
    {
        $lus = FrameService::listLUforSelect($this->idFrame);
        $this->options = [];
        foreach ($lus as $lu) {
            $this->options[] = [
                'idLU' => $lu['idLU'],
                'name' => $lu['name'],
            ];
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.combobox.lu-frame');
    }
}
