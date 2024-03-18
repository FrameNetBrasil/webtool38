<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Repository;

class Corpus extends Repository
{

    public function getById(int $id): void
    {
        $criteria = $this->getCriteria()
            ->where('idCorpus', '=', $id)
            ->where('idLanguage', '=', AppService::getCurrentIdLanguage());
        $this->retrieveFromCriteria($criteria);
    }
    public function getEntryObject()
    {
        $criteria = $this->getCriteria()->select('entries.name, entries.description, entries.nick');
        $criteria->where("idCorpus = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->asObjectArray()[0];
    }

    public function getName()
    {
        $criteria = $this->getCriteria()->select('entries.name as name');
        $criteria->where("idCorpus = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->getResult()[0]['name'];
    }

    public function getByEntry($entry)
    {
        $criteria = $this->getCriteria()
            ->select('*');
        $criteria->where("entry","=",$entry);
        $this->retrieveFromCriteria($criteria);
    }

//    public function listAll()
//    {
//        $criteria = $this->getCriteria()->select('idCorpus, entries.name as name')->orderBy('entry');
//        Base::entryLanguage($criteria);
//        return $criteria;
//    }

    public function listByFilter($filter)
    {
        debug("corpus Filter", $filter);
        $criteria = $this->getCriteria();
        $criteria->select(['idCorpus','entry','name'])->orderBy('name');
        $criteria->distinct(true);
        $criteria->where("active","=",1);
        Base::entryLanguage($criteria);
        if ($filter->idCorpus ?? false) {
            $criteria->where("idCorpus = '{$filter->idCorpus}'");
        }
        if ($filter->corpus ?? false) {
            $criteria->where("upper(name) LIKE upper('%{$filter->corpus}%')");
        }
        if ($filter->entry ?? false) {
            $criteria->where("upper(entry) LIKE upper('%{$filter->entry}%')");
        }
        if ($filter->document ?? false) {
            Base::entryLanguage($criteria, 'documents');
            $criteria->where("upper(documents.name) LIKE upper('%{$filter->document}%')");
        }
        if ($filter->flickr30k ?? false) {
            $criteria->where("documents.documentMM.flickr30k","=",$filter->flickr30k);
        }
        if ($filter->image ?? false) {
            $criteria->where("documents.documentMM.sentenceMM.imageMM.name","startswith",$filter->image);
        }
        return $criteria;
    }

    public function create($data)
    {
        $this->beginTransaction();
        try {

            $baseEntry = strtolower('crp_' . $data->nameEn);
            $entity = new Entity();
            $idEntity = $entity->create('CR', $baseEntry);
            $entry = new Entry();
            $entry->create($baseEntry, $data->nameEn, $idEntity);
            $id = $this->saveData([
                'entry' => $baseEntry,
                'active' => 1,
                'idEntity' => $idEntity
            ]);
            Timeline::addTimeline("corpus", $id, "C");
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function delete() {
        $this->beginTransaction();
        try {
            $id = $this->idCorpus;
            $idEntity = $this->idEntity;
            $entry = new Entry();
            $entry->deleteByIdEntity($idEntity);
            parent::delete();
            $entity = new Entity($idEntity);
            $entity->delete();
            Timeline::addTimeline("corpus", $id, "D");
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateEntry($newEntry)
    {
        $transaction = $this->beginTransaction();
        try {
            $entry = new Entry();
            $entry->updateEntry($this->getEntry(), $newEntry);
            $this->setEntry($newEntry);
            parent::save();
            Timeline::addTimeline("corpus", $this->getId(), "S");
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Upload sentenças do WordSketch com Documento anotado em cada linha. Documentos já devem estar cadastrados.
     * Atualização em 24/06/2020: LU vem marcada com word/lemma - mudança no formato do header e das linhas
     * @param type $data
     * @param type $file
     */
    public function uploadSentences($data, $file)
    {
        // em cada linha: url,doc // atualizado em 24/06/2020: cada linha é uma sentença
        $idLU = $data->idLU;
        $subCorpus = $data->subCorpus;
        $idLanguage = $data->idLanguage;
        $transaction = $this->beginTransaction();
        $subCorpus = $this->createSubCorpus($data);
        $idDocument = $data->idDocument;
        $document = new Document($idDocument);
        try {
            $sentenceNum = 0;
            $rows = file($file->getTmpName());
            foreach ($rows as $row) {
                //$row = preg_replace('/#([0-9]*)/', '', $row);
                $row = trim($row);
                if (($row[0] != '#') && ($row[0] != ' ') && ($row[0] != '')) {
                    $row = str_replace('&', 'e', $row);
                    $row = str_replace('< ', '<', $row);
                    $row = str_replace(' >', '>', $row);
                    $row = str_replace(['$.', '$,', '$:', '$;', '$!', '$?', '$(', '$)', '$\'', '$"', '$--'], ['.', ',', ':', ';', '!', '?', '(', ')', '\'', '"', '--'], $row);
                    $row = str_replace('</s>', ' ', $row);
                    $tokens = preg_split('/  /', $row);
                    $tokensSize = count($tokens);
                    if ($tokensSize == 0) {
                        continue;
                    }
                    if ($tokens[0][0] == '/') {
                        $baseToken = 1;
                    } else if ($tokens[0][0] == ')') {
                        $baseToken = 1;
                    } else {
                        $baseToken = 0;
                    }
                    $sentenceNum += 1;
                    // Nesta versão, considera que cada linha é uma sentença terminada por um ponto
                    $sentence = utf8_decode($row);
                    // Build sentence and Find target
                    ddump($sentence);
                    $replace = [' .' => ".", ' ,' => ',', ' ;' => ';', ' :' => ':', ' !' => '!', ' ?' => '?', ' >' => '>'];
                    $search = array_keys($replace);
                    $base = preg_replace('/([^\s]*)\/([^\s]*)/i', '<$1>', $sentence);
                    $base = str_replace($search, $replace, $base);
                    $sentence = '';
                    // find target
                    $targetStart = -1;
                    $targetEnd = -1;
                    for ($charCounter = 0; $charCounter < strlen($base); $charCounter++) {
                        $char = $base[$charCounter];
                        if ($char == '<') {
                            $targetStart = $charCounter;
                        } elseif ($char == '>') {
                            $targetEnd = $charCounter - 2;
                        } else {
                            $sentence .= $char;
                        }
                    }
                    // Ignores lines where the target word was not detected
                    if (($targetStart == -1) || ($targetEnd == -1)) {
                        continue;
                    }
                    $text = utf8_encode($sentence);
                    $paragraph = $document->createParagraph();
                    $sentenceObj = $document->createSentence($paragraph, $sentenceNum, $text, $idLanguage);
                    $data->idSentence = $sentenceObj->getId();
                    $data->startChar = $targetStart;
                    $data->endChar = $targetEnd;
                    $subCorpus->createAnnotation($data);
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            // rollback da transação em caso de algum erro
            $transaction->rollback();
            throw new EModelException($e->getMessage());
        }
        return;
    }

    /**
     * Upload sentenças do WordSketch com Documento anotado em cada linha. Documentos já devem estar cadastrados.
     * Usando tags Penn do TreeTagger (para textos em inglês e espanhol)
     * @param type $data
     * @param type $file
     */
    public function uploadSentencesPenn($data, $file)
    { // em cada linha: url,doc
        $idLU = $data->idLU;
        //$idCorpus = $data->idCorpus;
        $idDocument = $data->idDocument;
        $document = new Document($idDocument);
        $subCorpus = $data->subCorpus;
        $idLanguage = $data->idLanguage;
        $transaction = $this->beginTransaction();
        $subCorpus = $this->createSubCorpus($data);
        $documents = array();
        try {
            $sentenceNum = 0;
            $rows = file($file->getTmpName());
            foreach ($rows as $row) {
                $row = preg_replace('/#([0-9]*)/', '', $row);
                $row = trim($row);
                if (($row[0] != '#') && ($row[0] != ' ') && ($row[0] != '')) {
                    $row = str_replace('&', 'e', $row);
                    $row = str_replace(' < ', '  <  ', $row);
                    $row = str_replace(' > ', '  >  ', $row);
                    /*
                    // obtem nome do documento
                    $x = preg_match('/([^,]*),([^\s]*)\s/', $row, $dados);
                    if ($dados[1] != '') {
                        $docName = $dados[1];
                        ddump('==Docname ===============' . $docName);
                        $document = $documents[$docName];
                        if ($document == '') { // criar Document
                            $document = new Document();
                            $document->getbyName($docName, $idLanguage);
                            if ($document->getId() == '') { // não existe o documento informado na linha
                                ddump('=====');
                                ddump('== sem document: ' . $row);
                                ddump('=====');
                                continue;
                            }
                            $documents[$docName] = $document;
                        }
                        $row = trim(str_replace($dados[1] . ',' . $dados[2], '', $row));
                    } else {
                        continue;
                    }
                    */
                    $row = str_replace(array('$.', '$,', '$:', '$;', '$!', '$?', '$(', '$)', '$\'', '$"', '$--', "’", "“", "”"), array('.', ',', ':', ';', '!', '?', '(', ')', '\'', '"', '--', '\'', '"', '"'), $row);
                    $row = str_replace('</s>', ' ', $row);
                    // -- $result .= $row . "\n";
                    $tokens = preg_split('/  /', $row);
                    $tokensSize = count($tokens);
                    if ($tokensSize == 0) {
                        continue;
                    }
                    if ($tokens[0][0] == '/') {
                        $baseToken = 1;
                    } else if ($tokens[0][0] == ')') {
                        $baseToken = 1;
                    } else {
                        $baseToken = 0;
                    }
                    //ddump($tokens);
                    $sentenceNum += 1;
                    // Percorre a sentença para eliminar sentenças anteriores e posteriores (tags SENT ou FS)
                    $i = $baseToken;
                    $charCounter = 0;
                    $targetStart = -1;
                    $targetEnd = -1;
                    while ($i < ($tokensSize - 1)) {
                        $t = $tokens[$i];
                        $subTokens = preg_split('/\//', $t);
                        //ddump($subTokens);
                        $word = trim($subTokens[0]);
                        $tag = trim($subTokens[1]);
                        //ddump($word);
                        if (trim($word) != '') {
                            if ((trim($tag) == 'SENT') || (trim($tag) == 'FS')) {
                                if ($targetStart == -1) {
                                    $baseToken = $i + 1;
                                    $i += 1;
                                    continue;
                                } else {
                                    $tokensSize = $i + 2;
                                    break;
                                }
                            }
                            if ($word == '<') {
                                $i += 1;
                                $targetStart = $charCounter;
                                continue;
                            } elseif ($word == '>') {
                                $i += 1;
                                $targetEnd = $charCounter - 2;
                                continue;
                            }
                            $charCounter += strlen($word) + 1;
                        }
                        $i += 1;
                    }
                    // Build sentence and Find target
                    $isTarget = false;
                    $sentence = '';
                    $replace = ['"' => "'", '=' => ' '];
                    $search = array_keys($replace);
                    $i = $baseToken;
                    while ($i < ($tokensSize - 1)) {
                        $t = $tokens[$i];
                        if ($t == '<') {
                            $word = $t;
                            $isTarget = true;
                        } else if ($t == '>') {
                            $word = $t . ' ';
                            $isTarget = false;
                        } else {
                            $subTokens = preg_split('/\//', $t);
                            $word = utf8_decode($subTokens[0]);
                            $word = str_replace($search, $replace, $word);
                            if ($isTarget) {
                                $word = trim($word);
                            }
                        }
                        $sentence .= $word;
                        $i += 1;
                    }
                    ddump($sentence);
                    $replace = [' .' => ".", ' ,' => ',', ' ;' => ';', ' :' => ':', ' !' => '!', ' ?' => '?', ' >' => '>'];
                    $search = array_keys($replace);
                    $base = str_replace($search, $replace, $sentence);
                    $sentence = '';
                    $targetStart = -1;
                    $targetEnd = -1;
                    for ($charCounter = 0; $charCounter < strlen($base); $charCounter++) {
                        $char = $base[$charCounter];
                        if ($char == '<') {
                            $targetStart = $charCounter;
                        } elseif ($char == '>') {
                            $targetEnd = $charCounter - 2;
                        } else {
                            $sentence .= $char;
                        }
                    }
                    // Ignores lines where the target word was not detected
                    if (($targetStart == -1) || ($targetEnd == -1)) {
                        //  ddump('sem target: ' . $sentence);
                        continue;
                    }
                    ddump($sentence);
                    ddump($targetStart . ' - ' . $targetEnd);
                    ddump(substr($sentence, $targetStart, $targetEnd - $targetStart + 1));
                    $text = utf8_encode($sentence);
                    // -- $result .= $text . "\n";
                    $paragraph = $document->createParagraph();
                    $sentenceObj = $document->createSentence($paragraph, $sentenceNum, $text, $idLanguage);
                    $data->idSentence = $sentenceObj->getId();
                    $data->startChar = $targetStart;
                    $data->endChar = $targetEnd;
                    $subCorpus->createAnnotation($data);
                }
            }
            $transaction->commit();
        } catch (\EModelException $e) {
            // rollback da transação em caso de algum erro
            $transaction->rollback();
            throw new EModelException($e->getMessage());
        }
        return;
    }

    /**
     * Upload de sentenças de construções, em arquivo texto simples (uma sentença por linha).
     * Parâmetro data informa: idConstruction, subCorpus e idLanguage
     * @param type $data
     * @param type $file
     */
    public function uploadCxnSimpleText($data, $file)
    {
        $subCorpus = $data->subCorpus;
        $idLanguage = $data->idLanguage;
        $transaction = $this->beginTransaction();
        $subCorpus = $this->createSubCorpusCxn($data);
        $document = new Document();
        $document->getbyEntry('not_informed');
        try {
            $sentenceNum = 0;
            $rows = file($file->getTmpName());
            foreach ($rows as $row) {
                $row = preg_replace('/#([0-9]*)/', '', $row);
                $row = trim($row);
                if (($row[0] != '#') && ($row[0] != ' ') && ($row[0] != '')) {
                    $row = str_replace('&', 'e', $row);
                    $row = str_replace(' < ', '  <  ', $row);
                    $row = str_replace(' > ', '  >  ', $row);
                    $row = str_replace(array('$.', '$,', '$:', '$;', '$!', '$?', '$(', '$)', '$\'', '$"', '$--', "’", "“", "”"), array('.', ',', ':', ';', '!', '?', '(', ')', '\'', '"', '--', '\'', '"', '"'), $row);
                    $replace = [' .' => ".", ' ,' => ',', ' ;' => ';', ' :' => ':', ' !' => '!', ' ?' => '?', ' >' => '>'];
                    $search = array_keys($replace);
                    $sentence = str_replace($search, $replace, $row);
                    ddump($sentence);
                    $text = $sentence;
                    $sentenceNum += 1;
                    $paragraph = $document->createParagraph();
                    $sentenceObj = $document->createSentence($paragraph, $sentenceNum, $text, $idLanguage);
                    $data->idSentence = $sentenceObj->getId();
                    $subCorpus->createAnnotationCxn($data);
                }
            }
            $transaction->commit();
        } catch (\EModelException $e) {
            // rollback da transação em caso de algum erro
            $transaction->rollback();
            throw new EModelException($e->getMessage());
        }
    }


    public function createSubcorpus($data)
    {
        $sc = new SubCorpus();
        $sc->addSubcorpusLU($data);
        return $sc;
    }

    public function createSubcorpusCxn($data)
    {
        $sc = new SubCorpus();
        $sc->addSubcorpusCxn($data);
        return $sc;
    }

    public function listAnnotationReport($sort = 'frame', $order = 'asc')
    {
        $idLanguage = \Manager::getSession()->idLanguage;

        $from = <<<HERE
  FROM corpus
  INNER JOIN document ON (corpus.idCorpus = document.idCorpus)
  INNER JOIN paragraph ON (document.idDocument = paragraph.idDocument)
  INNER JOIN sentence ON (paragraph.idParagraph = sentence.idParagraph)
  INNER JOIN annotationset ON (sentence.idSentence = annotationset.idSentence)
  INNER JOIN view_subcorpuslu sc ON (annotationset.idSubCorpus = sc.idSubCorpus)
  INNER JOIN view_lu lu ON (sc.idLU = lu.idLU)
  INNER JOIN lemma lm ON (lu.idLemma = lm.idLemma)
  INNER JOIN entry e1 ON (lu.frameEntry = e1.entry)
  INNER JOIN entry e2 ON (document.entry = e2.entry)
  INNER JOIN language l on (lu.idLanguage = l.idLanguage)
  WHERE (e1.idLanguage = {$idLanguage} )
  AND (e2.idLanguage = {$idLanguage} )
  AND (lu.idLanguage = {$idLanguage})
  AND (corpus.idCorpus = {$this->getIdCorpus()} )
  AND (lu.idLanguage = sentence.idLanguage )
HERE;

        if (($sort == '') || ($sort == 'frame')) {
            $cmd = "SELECT corpus.idCorpus,e1.name frame,lu.name lu,e2.name doc,l.language lang,count(*) count";
            $cmd .= $from . " GROUP BY corpus.idCorpus,e1.name,lu.name,e2.name,l.language";
        }
        if ($sort == 'lu') {
            $cmd = "SELECT corpus.idCorpus,lu.name lu,e1.name frame,e2.name doc,l.language lang,count(*) count";
            $cmd .= $from . " GROUP BY corpus.idCorpus,lu.name,e1.name,e2.name,l.language";
        }
        if ($sort == 'doc') {
            $cmd = "SELECT corpus.idCorpus,e2.name doc, e1.name frame,lu.name lu,l.language lang,count(*) count";
            $cmd .= $from . " GROUP BY corpus.idCorpus,e2.name,e1.name,lu.name,l.language";
        }

        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;

    }

    public function listMultimodalByFilter($filter)
    {
        $criteria = $this->getCriteria();
        $criteria->setAssociationAlias('entries', 'centry');
        $criteria->select('distinct idCorpus, entry, centry.name as name')->orderBy('centry.name');
        Base::entryLanguage($criteria);
        $criteria->where("documents.documentmm.idDocumentMM IS NOT NULL");

        if ($filter->idCorpus) {
            $criteria->where("idCorpus = '{$filter->idCorpus}'");
        }
        if ($filter->corpus) {
            $criteria->where("upper(centry.name) LIKE upper('%{$filter->corpus}%')");
        }
        if ($filter->entry) {
            $criteria->where("upper(entry) LIKE upper('%{$filter->entry}%')");
        }
        return $criteria;
    }


}
