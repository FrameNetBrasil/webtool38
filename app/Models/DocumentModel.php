<?php

namespace App\Models;

use App\Services\AppService;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;

class DocumentModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        self::table('document');
        self::attribute('idDocument', key: Key::PRIMARY);
        self::attribute('entry');
        self::attribute('active');
        self::attribute('idEntity', key: Key::FOREIGN);
        self::attribute('idGenre', key: Key::FOREIGN);
        self::attribute('author');
        self::attribute('name', reference: 'entries.name');
        self::attribute('description', reference: 'entries.description');
        self::attribute('idLanguage', reference: 'entries.idLanguage');
        self::associationMany('entries', model: EntryModel::class, keys: 'idEntity:idEntity');
        self::associationOne('corpus', model: CorpusModel::class, key: 'idCorpus');
        self::associationMany('paragraphs', model: ParagraphModel::class, keys: 'idDocument');
        self::associationMany('sentences', model: SentenceModel::class, associativeTable: 'document_sentence');
        self::associationMany('documentMM', model: DocumentMMModel::class, keys: 'idDocument');
        self::associationMany('genre', model: GenreModel::class, keys: 'idGenre');
    }

    public static function getById(int $id): object|array|null
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [
            ['corpus.entries.idLanguage', '=', $idLanguage],
            ['entries.idLanguage', '=', $idLanguage],
            ['idDocument','=',$id]
        ];
        return parent::one($filters, ['idDocument','entry','name','description','idCorpus','corpus.name as corpusName']);
    }
    public static function listByCorpusDocument(string $corpusName = '', string $documentName = ''): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [];
        $filters[] = ['corpus.entries.idLanguage', '=', $idLanguage];
        if ($corpusName != '') {
            $filters[] = ['corpus.name', 'startswith', $corpusName];
        }
        $filters[] = ['entries.idLanguage', '=', $idLanguage];
        if ($documentName != '') {
            $filters[] = ['name', 'startswith', $documentName];
        }
        $filters[] = ['corpus.active','=',1];
        $filters[] = ['active','=',1];
        return self::list($filters, [
            'corpus.name as corpusName',
            'corpus.idCorpus',
            'name as documentName',
            'idDocument'
        ], [['corpus.name'],['name']]);
    }

    public static function listSentencesByDocument(int $idDocument, string $word = '', int $offset = 0, int $limit = 0): array
    {
        $filters = [];
        $filters[] = ['idDocument','=', $idDocument];
        if ($word != '') {
            $filters[] = ['sentences.text','contains', $word];
        }
        $count = self::getCriteria()
            ->where($filters)
            ->select('count(sentences.idSentence) as c')
            ->get()[0]['c'];
        $criteria = self::getCriteria()
            ->where($filters)
            ->select([
                'sentences.idSentence',
                'sentences.text',
            ])
            ->orderBy('sentences.idSentence')
            ->offset($offset);
        if ($limit > 0) {
            $criteria->limit($limit);
        }
        $result = $criteria
            ->getResult()
            ->toArray();
        return [
            'result' => $result,
            'count' => $count
        ];
    }


}
