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
class DomainController extends Controller
{
    #[Get(path: '/grapher/domain')]
    public function domain()
    {
        $relationType = new RelationType();
        $relations = $relationType->listByFilter((object)[
            'group' => 'rgp_frame_relations'
        ])->getResult();
        $this->data->relations = [];
        $config = config('webtool.relations');
        foreach($relations as $relation) {
            $this->data->relations[] = [
                'value' => $relation['idRelationType'],
                'entry' => $relation['entry'],
                'name' => $config[$relation['entry']]['direct'],
            ];
        }
        return $this->render('domain');
    }

    #[Post(path: '/grapher/domain/graph/{idEntity?}')]
    public function frameGraph(int $idEntity = null)
    {
        ddump($this->data);
        $nodes = session("graphNodes") ?? [];
        if (isset($this->data->idSemanticType)) {
            $idSemanticType = $this->data->idSemanticType;
        } else {
            $idSemanticType = 0;
        }
        if (isset($this->data->idRelationType)) {
            $idRelationType = (array)$this->data->idRelationType;
        } else {
            $idRelationType = session('idRelationType');
        }
        ddump('idEntity ' . $idEntity);
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
        $this->data->graph = RelationService::listDomainForGraph($idSemanticType, $idRelationType);
        ddump($this->data);
        return $this->render('domainGraph');
    }

    #[Post(path: '/grapher/framefe/graph/{idEntityRelation}')]
    public function frameFeGraph(int $idEntityRelation = null)
    {
        ddump($this->data);
        $nodes = session("graphNodes") ?? [];
        $idRelationType = session('idRelationType');
        $this->data->graph = RelationService::listFrameRelationsForGraph($nodes, $idRelationType);
        $feGraph = RelationService::listFrameFERelationsForGraph($idEntityRelation);
        foreach($feGraph['nodes'] as $idNode => $node) {
            $this->data->graph['nodes'][$idNode] = $node;
        }
        foreach($feGraph['links'] as $idSource => $links) {
            foreach($links as $idTarget => $link) {
                $this->data->graph['links'][$idSource][$idTarget] = $link;
            }
        }
        ddump($this->data);
        return $this->render('frameGraph');
    }

}
