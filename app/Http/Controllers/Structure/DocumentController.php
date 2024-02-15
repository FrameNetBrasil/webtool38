<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Document;
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
class DocumentController extends Controller
{
    #[Post(path: '/document')]
    public function newDocument()
    {
        try {
            $document = new Document();
            $document->create($this->data->new);
            $this->data->document = $document;
            return $this->clientRedirect("/document/{$document->idDocument}/edit");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/document/{id}/edit')]
    public function edit(string $id)
    {
        $this->data->document = new Document($id);
        return $this->render("pageEdit");
    }

    #[Get(path: '/document/{id}/entries')]
    public function formEntries(string $id)
    {
        $this->data->document = new Document($id);
        $entry = new Entry();
        $this->data->entries = $entry->listByIdEntity($this->data->document->idEntity);
        $this->data->languages = AppService::availableLanguages();
        return $this->render("entries");
    }

    #[Put(path: '/document/{id}/entries')]
    public function entries(int $id)
    {
        try {
            EntryService::updateEntries($this->data);
            return $this->renderNotify("success", "Translations recorded.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
