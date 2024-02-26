<?php

namespace App\Http\Controllers\Structure;

use App\Data\CreateLemmaData;
use App\Data\SearchLemmaData;
use App\Data\UpdateLemmaData;
use App\Http\Controllers\Controller;
use App\Repositories\Lemma;
use App\Services\AppService;
use App\Services\LemmaService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class LemmaController extends Controller
{
    #[Get(path: '/lemma')]
    public function browse()
    {
        data('search', session('searchLexicon') ?? SearchLemmaData::from());
        return $this->render('browse');
    }

    #[Post(path: '/lemma/grid')]
    public function grid()
    {
        data('search', SearchLemmaData::from(data('search')));
        session(['searchLexicon' => $this->data->search]);
        return $this->render("grid");
    }

    #[Get(path: '/lemma/listForSelect')]
    public function listForSelect()
    {
        $lemma = new Lemma();
        return $lemma->listForSelect(data('q'))->getResult();
    }

    #[Post(path: '/lemma/listForTree')]
    public function listForTree()
    {
        $search = SearchLemmaData::from($this->data);
        $result = [];
        $id = data('id', default: '');
        if ($id != '') {
            $idLemma = substr($id, 1);
            $resultLexeme = LexemeController::listForTreeByLemma($idLemma);
            return $resultLexeme;
        } else {
            $icon = 'material-icons-outlined wt-tree-icon wt-icon-lemma';
            $lemma = new Lemma();
            $lemmas = $lemma->listByFilter($search)->getResult();
            foreach ($lemmas as $row) {
                $node = [];
                $node['id'] = 'l' . $row['idLemma'];
                $node['type'] = 'lemma';
                $node['name'] = $row['name'];
                $node['state'] = 'closed';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-lemma';
                $node['children'] = [];
                $result[] = $node;
            }
            $total = count($result);
            return [
                'total' => $total,
                'rows' => $result,
                'footer' => [
                    [
                        'type' => 'lemma',
                        'name' => ["{$total} record(s)", ''],
                        'iconCls' => $icon
                    ]
                ]
            ];
        }
    }

    #[Get(path: '/lemma/new')]
    public function new()
    {
        data('idLanguage', AppService::getCurrentLanguage()['idLanguage']);
        return $this->render("new");
    }

    #[Get(path: '/lemma/{id}/edit')]
    public function editLemma(string $id)
    {
        $lemma = new Lemma($id);
        data('lemma', $lemma);
        return $this->render("formEdit");
    }
    #[Get(path: '/lemma/{id}')]
    #[Get(path: '/lemma/{id}/main')]
    public function edit(string $id)
    {
        $lemma = new Lemma($id);
        data('lemma', $lemma);
        $lemma->retrieveAssociation('language');
        data('language', $lemma->language);
        return $this->render("edit");
    }


    #[Post(path: '/lemma')]
    public function postLemma()
    {
        try {
            $lemma = new Lemma();
            $data = CreateLemmaData::validateAndCreate((array)data('new'));
            $lemma->create($data);
            data('lemma', $lemma);
            return $this->clientRedirect("/lemma/{$lemma->idLemma}");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/lemma/{id}')]
    public function delete(string $id)
    {
        try {
            $lemma = new Lemma($id);
            $lemma->delete();
            return $this->clientRedirect("/lemma");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Put(path: '/lemma/{id}')]
    public function update(string $id)
    {
        try {
            $lemma = new Lemma($id);
            $data = UpdateLemmaData::validateAndCreate((array)data('update'));
            $lemma->update($data);
            return $this->clientRedirect("/lemma/{$lemma->idLemma}");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/lemma/{id}/lexemes')]
    public function lexemes(string $id)
    {
        data('idLemma', $id);
        return $this->render("lexemes");
    }

    #[Get(path: '/lemma/{id}/lexemes/formNew')]
    public function formNewLexemes(string $id)
    {
        data('idLemma', $id);
        return $this->render("lexemesFormNew");
    }

    #[Get(path: '/lemma/{id}/lexemes/grid')]
    public function gridLexemes(string $id)
    {
        data('idLemma', $id);
        data('lexemes', LexemeController::listForTreeByLemma($id));
        return $this->render("lexemesGrid");
    }

}
