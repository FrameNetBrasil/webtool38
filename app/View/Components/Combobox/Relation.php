<?php

namespace App\View\Components\Combobox;

use App\Repositories\RelationType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Relation extends Component
{
    public array $options;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public string $group
    )
    {
        $groupEntries = [
            'frame' => 'rgp_frame_relations',
            'fe' => 'rgp_fe_relations',
            'cxn' => 'rgp_cxn_relations',
            'constraints' => 'rgp_constraints',
            'qualia' => 'rgp_qualia'
        ];
        $relationType = new RelationType();
        $relations = $relationType->listByFilter((object)[
            'group' => $groupEntries[$this->group]
        ])->getResult();
        $this->options = [];
        $config = config('webtool.relations');
        foreach($relations as $relation) {
            $this->options[] = [
                'value' => 'd' . $relation['idRelationType'],
                'entry' => $relation['entry'],
                'name' => $config[$relation['entry']]['direct'],
            ];
        }
        if ($group == 'frame') {
            $this->options[] = [
                'value' => 'd0',
                'entry' => '0',
                'name' => '-- inverse relations --',
            ];
            foreach ($relations as $relation) {
                $this->options[] = [
                    'value' => 'i' . $relation['idRelationType'],
                    'entry' => $relation['entry'],
                    'name' => $config[$relation['entry']]['inverse'],
                ];
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.combobox.relation');
    }
}
