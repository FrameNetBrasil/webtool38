<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Domain;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Services\AppService;
use App\Services\CorpusService;
use App\Services\EntryService;
use App\Services\FrameService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;
use Illuminate\Support\Facades\Request;

#[Middleware(name: 'auth')]
class CorpusController extends Controller
{
    #[Get(path: '/corpus')]
    public function browse()
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('pageBrowse');
    }

    #[Get(path: '/corpus/new')]
    public function new()
    {
        return $this->render("pageNew");
    }

    #[Post(path: '/corpus')]
    public function newCorpus()
    {
        try {
            $corpus = new Corpus();
            $corpus->create($this->data->new);
            $this->data->corpus = $corpus;
            return $this->clientRedirect("/corpus/{$corpus->idCorpus}/edit");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/corpus/grid')]
    public function grid()
    {
        $this->data->search->_token = csrf_token();
        $response = $this->render("slotGrid");
        $query = [
            'search_corpus' => $this->data->search->corpus,
            'search_document' => $this->data->search->document,
        ];
        $response->header('HX-Replace-Url', '/corpus?' . http_build_query($query));
        return $response;
    }

    #[Post(path: '/corpus/listForTree')]
    public function listForTree()
    {
        return CorpusService::listForTree();
    }

    #[Get(path: '/corpus/{id}/edit')]
    public function edit(string $id)
    {
        $this->data->corpus = new Corpus($id);
        return $this->render("pageEdit");
    }

    #[Delete(path: '/corpus/{id}/delete')]
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
    public function formEntries(string $id)
    {
        $this->data->corpus = new Corpus($id);
        $entry = new Entry();
        $this->data->entries = $entry->listByIdEntity($this->data->corpus->idEntity);
        $this->data->languages = AppService::availableLanguages();
        return $this->render("entries");
    }

    #[Put(path: '/corpus/{id}/entries')]
    public function entries(int $id)
    {
        try {
            EntryService::updateEntries($this->data);
            return $this->renderNotify("success", "Translations recorded.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/corpus/{id}/documents')]
    public function documents(string $id)
    {
        $this->data->idCorpus = $id;
        return $this->render("documents");
    }

    #[Get(path: '/corpus/{id}/documents/formNew')]
    public function formNewDocument(string $id)
    {
        $this->data->idCorpus = $id;
        return $this->render("Structure.Corpus.Document.formNew");
    }

    #[Get(path: '/corpus/{idCorpus}/documents/grid')]
    public function gridDocument(string $id)
    {
        $this->data->idCorpus = $id;
        $this->data->documents = CorpusService::listDocumentForGrid($id);
        return $this->render("Structure.Corpus.Document.grid");
    }

}
