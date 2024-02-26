<?php

namespace App\Http\Controllers\Structure;

use App\Data\CreateCorpusData;
use App\Data\SearchCorpusData;
use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Entry;
use App\Services\AppService;
use App\Services\CorpusService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class CorpusController extends Controller
{
    #[Get(path: '/corpus')]
    public function browse()
    {
        data('search', session('searchCorpus') ?? SearchCorpusData::from());
        return $this->render('browse');
    }

    #[Get(path: '/corpus/new')]
    public function new()
    {
        return $this->render("new");
    }

    #[Post(path: '/corpus')]
    public function postCorpus()
    {
        try {
            $corpus = new Corpus();
            $corpus->create(CreateCorpusData::from(data('new')));
            data('corpus', $corpus);
            return $this->clientRedirect("/corpus/{$corpus->idCorpus}");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/corpus/grid')]
    public function grid()
    {
        data('search', SearchCorpusData::from(data('search')));
        session(['searchCorpus' => $this->data->search]);
        return $this->render("grid");
    }

    #[Post(path: '/corpus/listForTree')]
    public function listForTree()
    {
        $search = SearchCorpusData::from($this->data);
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

    #[Get(path: '/corpus/{id}')]
    #[Get(path: '/corpus/{id}/main')]
    public function edit(string $id)
    {
        $corpus = new Corpus($id);
        data('corpus', $corpus);
        return $this->render("edit");
    }
    #[Delete(path: '/corpus/{id}')]
    public function delete(string $id)
    {
        try {
            $corpus = new Corpus($id);
            $corpus->delete();
            return $this->clientRedirect("/corpus");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/corpus/{id}/entries')]
    public function entries(string $id)
    {
        $corpus = new Corpus($id);
        data('corpus', $corpus);
        $entry = new Entry();
        data('entries', $entry->listByIdEntity($corpus->idEntity));
        data('languages', AppService::availableLanguages());
        return $this->render("Structure.Entry.main");
    }
    #[Get(path: '/corpus/{id}/documents')]
    public function documents(string $id)
    {
        data('idCorpus', $id);
        return $this->render("Structure.Document.child");
    }

    #[Get(path: '/corpus/{id}/documents/formNew')]
    public function formNewDocument(string $id)
    {
        data('idCorpus', $id);
        return $this->render("Structure.Document.formNew");
    }

    #[Get(path: '/corpus/{idCorpus}/documents/grid')]
    public function gridDocument(string $id)
    {
        data('idCorpus', $id);
        debug(DocumentController::listForTreeByCorpus($id));
        data('documents', DocumentController::listForTreeByCorpus($id));
        return $this->render("Structure.Document.grid");
    }

}
