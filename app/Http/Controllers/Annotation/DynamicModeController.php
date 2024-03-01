<?php

namespace App\Http\Controllers\Annotation;

use App\Data\SearchDynamicModeData;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Structure\DocumentController;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\DocumentMM;
use App\Repositories\DynamicObjectMM;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;


#[Middleware(name: 'auth')]
class DynamicModeController extends Controller
{
    #[Get(path: '/annotation/dynamicMode')]
    public function browse()
    {
        data('search', session('searchDynamicMode') ?? SearchDynamicModeData::from());
        return $this->render('browse');
    }

    #[Post(path: '/annotation/dynamicMode/grid')]
    public function grid()
    {
        data('search', SearchDynamicModeData::from(data('search')));
        session(['searchDynamicMode' => $this->data->search]);
        return $this->render("grid");
    }

    #[Post(path: '/annotation/dynamicMode/listForTree')]
    public function listForTree()
    {
        $search = SearchDynamicModeData::from($this->data);
        $result = [];
        $id = data('id', default: '');
        if ($id != '') {
            if ($id[0] == 'c') {
                $idCorpus = substr($id, 1);
                $result = DocumentController::listForTreeByCorpus($idCorpus);
            }
            if ($id[0] == 'd') {
                $idDocument = substr($id, 1);
                $result = DocumentController::listForTreeByDocument($idDocument);
            }
            return $result;
        } else {
            $icon = 'material-icons-outlined wt-tree-icon wt-icon-corpus';
            if ($search->document == '') {
                $corpus = new Corpus();
                $corpora = $corpus->listByFilter($search)->asQuery()->getResult();
                foreach ($corpora as $row) {
                    $node = [];
                    $node['id'] = 'c' . $row['idCorpus'];
                    $node['type'] = 'corpus';
                    $node['name'] = [$row['name']];
                    $node['state'] = 'closed';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-corpus';
                    $node['children'] = [];
                    $result[] = $node;
                }
            } else {
                $result = DocumentController::listForTreeByName($search->document);
                $icon = "material-icons wt-tree-icon wt-icon-document";
            }
            $total = count($result);
            return [
                'total' => $total,
                'rows' => $result,
                'footer' => [
                    [
                        'type' => 'corpus',
                        'name' => ["{$total} record(s)", ''],
                        'iconCls' => $icon
                    ]
                ]
            ];
        }

    }

    #[Get(path: '/annotation/dynamicMode/{idDocument}')]
    public function annotation(int $idDocument)
    {
        $document = new Document($idDocument);
        $document->retrieveAssociation('corpus');
        data('document', $document);
        $documentMM = new DocumentMM();
        $documentMM->getByIdDocument($idDocument);
        data('documentMM', $documentMM);
        data("objects", []);
        return $this->render("annotation");
    }

    #[Get(path: '/annotation/dynamicMode/gridObjects/{idDocument}')]
    public function gridObjects(int $idDocument)
    {
        $dynamicObjectMM = new DynamicObjectMM();
//        data('idDocument', $idDocument);
//        data('objects', $dynamicObjectMM->getObjectsByDocument($idDocument));
//        return $this->render("Annotation.DynamicMode.Annotation.gridObjects");
        return $dynamicObjectMM->getObjectsByDocument($idDocument);
    }


}
