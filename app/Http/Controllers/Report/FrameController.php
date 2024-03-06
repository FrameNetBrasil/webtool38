<?php

namespace App\Http\Controllers\Report;

use App\Data\SearchFrameData;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Structure\FEController;
use App\Http\Controllers\Structure\LUController;
use App\Repositories\Frame;
use App\Repositories\ViewFrame;
use App\Services\FrameService;
use App\Services\ReportFrameService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Illuminate\Http\Request;
use Orkester\Manager;

#[Middleware(name: 'web')]
class FrameController extends Controller
{
    #[Get(path: '/report/frame')]
    public function browse()
    {
        data('search', session('searchFrame') ?? SearchFrameData::from());
        return $this->render('browse');
    }

    #[Post(path: '/report/grid/frame')]
    public function grid()
    {
        data('search', SearchFrameData::from(data('search')));
        session(['searchFrame' => $this->data->search]);
        return $this->render("grid");
    }


    #[Post(path: '/report/frame/listForTree')]
    public function listForTree() {
        $search = SearchFrameData::from($this->data);
        $result = [];
        $id = data('id', default:'');
        if ($id != '') {
            $idFrame = substr($id, 1);
            $resultFE = FEController::listForTreeByFrame($idFrame);
            $resultLU = LUController::listForTreeByFrame($idFrame);
            return array_merge($resultFE, $resultLU);
        } else {
            $icon = 'material-icons-outlined wt-tree-icon wt-icon-frame';
            if (($search->fe == '') && ($search->lu == '')) {
                $frame = new ViewFrame();
                $frames = $frame->listByFilter($search)->getResult();
                foreach ($frames as $row) {
                    $node = [];
                    $node['id'] = 'f' . $row['idFrame'];
                    $node['type'] = 'frame';
                    $node['name'] = [$row['name'], $row['description']];
                    $node['state'] = 'closed';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-frame';
                    $node['children'] = [];
                    $result[] = $node;
                }
            } else {
                if ($search->fe != '') {
                    $result = FEController::listForTreeByName($search->fe);
                    $icon = "material-icons wt-tree-icon wt-icon-fe-core";
                } else if ($search->lu != '') {
                    $result = LUController::listForTreeByName($search->lu);
                    $icon = 'material-icons-outlined wt-tree-icon wt-icon-lu';
                }
            }
            $total = count($result);
            return [
                'total' => $total,
                'rows' => $result,
                'footer' => [
                    [
                        'type' => 'frame',
                        'name' => ["{$total} record(s)", ''],
                        'iconCls' => $icon
                    ]
                ]
            ];
        }
    }

    #[Get(path: '/report/frame/listForSelect')]
    public function listForSelect()
    {
        $frame = new Frame();
        return $frame->listForSelect(data('q'))->getResult();
    }

    #[Get(path: '/report/frame/{idFrame}/{lang?}')]
    public function report(int|string $idFrame, string $lang = '')
    {
        data('report',ReportFrameService::report($idFrame, $lang));
        return $this->render('report');
    }

}
