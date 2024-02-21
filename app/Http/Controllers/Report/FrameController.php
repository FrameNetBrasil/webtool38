<?php

namespace App\Http\Controllers\Report;

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
    #[Post(path: '/report/grid/frame')]
    public function grid()
    {
        $this->data->search->_token = csrf_token();
        $response = $this->render("grid");
        $query = [
            'search_frame' => $this->data->search->frame,
            'search_fe' => $this->data->search->fe,
            'search_lu' => $this->data->search->lu,
        ];
        $response->header('HX-Replace-Url', '/report/frame?' . http_build_query($query));
        return $response;
    }

    #[Get(path: '/report/frame')]
    public function browse(int $idFrame = null)
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('pageBrowse');
    }

    #[Post(path: '/report/frame/listForTree')]
    public function listForTree() {
        $data = Manager::getData();
        debug($data);
        $result = [];
        $id = $data->id ?? '';
        if ($id != '') {
            $idFrame = substr($id, 1);
            $resultFE = FEController::listForTreeByFrame($idFrame);
            $resultLU = LUController::listForTreeByFrame($idFrame);
            return array_merge($resultFE, $resultLU);
        } else {
            $filter = $data;
            if (!(($filter->fe ?? false) || ($filter->lu ?? false))) {
                $frame = new ViewFrame();
                $frames = $frame->listByFilter($filter)->asQuery()->getResult();
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
                $icon = 'material-icons-outlined wt-tree-icon wt-icon-frame';
            } else {
                if ($filter->fe ?? false) {
                    $result = FEController::listForTreeByName($filter->fe);
                    $icon = "material-icons wt-tree-icon wt-icon-fe-core";
                } else if ($filter->lu ?? false) {
                    $result = LUController::listForTreeByName($filter->lu);
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
        $data = Manager::getData();
        $q = $data->q ?? '';
        $frame = new Frame();
        return $frame->listForSelect($q)->getResult();
    }

    #[Get(path: '/report/frame/{idFrame}/{lang?}')]
    public function report(int|string $idFrame, string $lang = '')
    {
        $this->data->report = ReportFrameService::report($idFrame, $lang);
        return $this->render('report');
    }

}
