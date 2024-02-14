<?php

namespace App\Models;

use App\Services\AppService;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;


class DocumentMMModel extends Model
{
    public static function map(ClassMap $classMap): void
    {
        
        self::table('documentmm');
        self::attribute('idDocumentMM', key: Key::PRIMARY);
        self::attribute('name', reference: 'document.name');
        self::attribute('title');
        self::attribute('originalFile');
        self::attribute('sha1Name');
        self::attribute('audioPath');
        self::attribute('videoPath');
        self::attribute('videoWidth', type: Type::INTEGER);
        self::attribute('videoHeight', type: Type::INTEGER);
        self::attribute('alignPath');
        self::attribute('flickr30k');
        self::attribute('enabled');
        self::attribute('idDocument', type: Type::INTEGER, key: Key::FOREIGN);
        self::attribute('idLanguage', type: Type::INTEGER, key: Key::FOREIGN);
        self::associationOne('document', model: DocumentModel::class, key: 'idDocument:idDocument');
        self::associationMany('sentenceMM', model: SentenceMMModel::class, keys: 'idDocumentMM:idDocumentMM');
    }

    public static function getById(int $id): object|array|null
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [
            ['idDocumentMM','=',$id]
        ];
        return parent::one($filters, ['*']);
    }

    public static function getByIdDocument(int $idDocument): object|array|null
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [
            ['document.corpus.entries.idLanguage', '=', $idLanguage],
            ['document.entries.idLanguage', '=', $idLanguage],
            ['idDocument','=',$idDocument]
        ];
        return parent::one($filters, ['idDocumentMM','idDocument','title','videoPath','videoHeight','videoWidth']);
    }

    public static function listByCorpusDocumentStatic(string $corpusName = '', string $documentName = ''): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [];
        $filters[] = ['document.corpus.entries.idLanguage', '=', $idLanguage];
        if ($corpusName != '') {
            $filters[] = ['document.corpus.name', 'startswith', $corpusName];
        }
        $filters[] = ['document.entries.idLanguage', '=', $idLanguage];
        if ($documentName != '') {
            $filters[] = ['document.name', 'startswith', $documentName];
        }
        $filters[] = ['document.corpus.active','=',1];
        $filters[] = ['document.active','=',1];
        $filters[] = ['flickr30k','>',0];
        return self::list($filters, [
            'document.corpus.name as corpusName',
            'document.corpus.idCorpus',
            'document.name as documentName',
            'document.idDocument'
        ], [['document.corpus.name'],['document.name']]);
    }

    public static function listByCorpusDocumentDynamic(string $corpusName = '', string $documentName = ''): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [];
        $filters[] = ['document.corpus.entries.idLanguage', '=', $idLanguage];
        if ($corpusName != '') {
            $filters[] = ['document.corpus.name', 'startswith', $corpusName];
        }
        $filters[] = ['document.entries.idLanguage', '=', $idLanguage];
        if ($documentName != '') {
            $filters[] = ['document.name', 'startswith', $documentName];
        }
        $filters[] = ['document.corpus.active','=',1];
        $filters[] = ['document.active','=',1];
        $filters[] = ['videoPath','<>',""];
        return self::list($filters, [
            'document.corpus.name as corpusName',
            'document.corpus.idCorpus',
            'document.name as documentName',
            'document.idDocument',
            'idDocumentMM'
        ], [['document.corpus.name'],['document.name']]);
    }

    public static function listSentencesByDocument(int $idDocument, string $word = '', int $offset = 0, int $limit = 0): array
    {
        $filters = [];
        $filters[] = ['idDocument','=', $idDocument];
        if ($word != '') {
            $filters[] = ['sentenceMM.sentence.text','contains', $word];
        }
        $count = self::getCriteria()
            ->where($filters)
            ->select('count(sentenceMM.idSentence) as c')
            ->get()[0]['c'];
        $criteriaCount1 = ObjectSentenceMMModel::getCriteria()
            ->where('sentenceMM.documentMM.idDocument','=', $idDocument)
            ->groupBy('idSentenceMM')
            ->select(['idSentence','count(distinct idObjectMM) as quant']);
        $criteria = self::getCriteria()
            ->where($filters)
            ->select([
                'sentenceMM.idSentence',
                'sentenceMM.idSentenceMM',
                'sentenceMM.sentence.text',
                'c1.quant'
            ])
            ->joinSub($criteriaCount1, 'c1', 'sentenceMM.idSentenceMM', '=', 'c1.idSentenceMM')
            ->orderBy('sentenceMM.idSentence')
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