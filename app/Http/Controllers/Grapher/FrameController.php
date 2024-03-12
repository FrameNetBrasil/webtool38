<?php

namespace App\Http\Controllers\Grapher;

use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Repositories\RelationType;
use App\Services\FrameService;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Illuminate\Http\Request;

#[Middleware(name: 'web')]
class FrameController extends Controller
{
    #[Get(path: '/grapher/frame')]
    public function frame()
    {
        $relationType = new RelationType();
        $relations = $relationType->listByFilter((object)[
            'group' => 'rgp_frame_relations'
        ])->getResult();
        $dataRelations = [];
        $config = config('webtool.relations');
        foreach($relations as $relation) {
            $dataRelations[] = [
                'value' => $relation['idRelationType'],
                'entry' => $relation['entry'],
                'name' => $config[$relation['entry']]['direct'],
            ];
        }
        data('relations', $dataRelations);
        return $this->render('frame');
    }

    #[Post(path: '/grapher/frame/graph/{idEntity?}')]
    public function frameGraph(int $idEntity = null)
    {
        $nodes = session("graphNodes") ?? [];
        $idFrame = data('idFrame') ?? null;
        if (!is_null($idFrame)) {
            $frame = new Frame($idFrame);
            $nodes = [$frame->idEntity];
        }
        if (data('idRelationType')) {
            $idRelationType = (array)data('idRelationType');
        } else {
            $idRelationType = session('idRelationType') ?? [];
        }
        if (!is_null($idEntity)) {
            if ($idEntity == 0) {
                $nodes = [];
            } else {
                $nodes = [...$nodes, $idEntity];
            }
        }
        session([
            "graphNodes" => $nodes,
            "idRelationType" => $idRelationType
        ]);
        data('graph', RelationService::listFrameRelationsForGraph($nodes, $idRelationType));
        return $this->render('frameGraph');
    }

    #[Post(path: '/grapher/framefe/graph/{idEntityRelation}')]
    public function frameFeGraph(int $idEntityRelation = null)
    {
        $nodes = session("graphNodes") ?? [];
        $idRelationType = session('idRelationType');
        $graph = RelationService::listFrameRelationsForGraph($nodes, $idRelationType);
        $feGraph = RelationService::listFrameFERelationsForGraph($idEntityRelation);
        foreach($feGraph['nodes'] as $idNode => $node) {
            $graph['nodes'][$idNode] = $node;
        }
        foreach($feGraph['links'] as $idSource => $links) {
            foreach($links as $idTarget => $link) {
                $graph['links'][$idSource][$idTarget] = $link;
            }
        }
        data('graph', $graph);
        return $this->render('frameGraph');
    }

}
