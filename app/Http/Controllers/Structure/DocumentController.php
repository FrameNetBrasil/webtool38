<?php

namespace App\Http\Controllers\Structure;

use App\Data\CreateDocumentData;
use App\Data\SearchCorpusData;
use App\Http\Controllers\Controller;
use App\Repositories\Base;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Domain;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\UserAnnotation;
use App\Repositories\ViewAnnotationSet;
use App\Services\AppService;
use App\Services\CorpusService;
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
    public static function listForTreeByCorpus(int $idCorpus): array
    {
        $result = [];
        $document = new Document();
        $documents = $document->listByFilter((object)['idCorpus' => $idCorpus])->asQuery()->getResult();
        foreach ($documents as $document) {
            $node = [];
            $node['id'] = 'd' . $document['idDocument'];
            $node['idDocument'] = $document['idDocument'];
            $node['type'] = 'document';
            $node['name'] = $document['name'];
            $node['state'] = 'closed';
            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-document';
            $node['children'] = [];
            $result[] = $node;
        }
        return $result;
    }
    public static function listForTreeByName(string $name): array
    {
        $result = [];
        $document = new Document();
        $documents = $document->listByFilter((object)['name' => $name])->asQuery()->getResult();
        foreach ($documents as $document) {
            $node = [];
            $node['id'] = 'd' . $document['idDocument'];
            $node['type'] = 'document';
            $node['name'] = $document['name'];
            $node['state'] = 'closed';
            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-document';
            $node['children'] = [];
            $result[] = $node;
        }
        return $result;

    }

    public static function listForTreeByDocument(int $idDocument): array
    {
        $result = [];
        $document = new Document($idDocument);
        $sentences = $document->listSentencesForAnnotation();
        $as = new ViewAnnotationSet();
        $userAnnotation = new UserAnnotation();
        $sentenceForAnnotation = $userAnnotation->listSentenceByUser(Base::getCurrentUserId(), $idDocument);
        $hasSentenceForAnnotation = (count($sentenceForAnnotation) > 0);
        foreach ($sentences as $sentence) {
            if ($hasSentenceForAnnotation) {
                if (!in_array($sentence['idSentence'], $sentenceForAnnotation)) {
                    continue;
                }
            }
            $targets = $as->listTargetBySentence($sentence['idSentence']);
            $text = CorpusService::decorateSentence($sentence['text'], $targets);
            $node = [];
            $node['id'] = 's' . $sentence['idSentence'];
            $node['type'] = 'sentence';
            $node['name'] = "[{$sentence['idSentence']}] {$text}";
            $node['state'] = 'open';
            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-sentence';
            $node['children'] = null;
            $result[] = $node;
        }
        return $result;
    }

    #[Get(path: '/document/{id}/edit')]
    public function edit(string $id)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $document = new Document($id);
        data('document', $document);
        $document->retrieveAssociation("corpus", $idLanguage);
        return $this->render("edit");
    }

    #[Get(path: '/document/{id}/main')]
    public function main(string $id)
    {
        data('_layout', 'main');
        return $this->edit($id);
    }

    #[Post(path: '/document')]
    public function newDocument()
    {
        try {
            $document = new Document();
            $document->create(CreateDocumentData::from(data('new')));
            $this->trigger('reload-gridDocument');
            return $this->renderNotify("success", "Document created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/document/{id}')]
    public function delete(string $id)
    {
        try {
            $document = new Document($id);
            $document->delete();
            $this->trigger('reload-gridDocument');
            return $this->renderNotify("success", "Document deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }


    #[Get(path: '/document/{id}/entries')]
    public function formEntries(string $id)
    {
        $document = new Document($id);
        data('document', $document);
        $entry = new Entry();
        data('entries', $entry->listByIdEntity($document->idEntity));
        data('languages', AppService::availableLanguages());
        return $this->render("Structure.Entry.main");
    }

}
