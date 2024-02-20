<?php

namespace App\Repositories;

use App\Models\ObjectSentenceMMModel;
use Maestro\Persistence\Repository;
use Orkester\Persistence\PersistenceManager;

class ObjectSentenceMM extends Repository
{
    public ?string $name;
    public ?int $idObjectSentenceMM;
    public ?int $startWord;
    public ?int $endWord;
    public ?int $idFrameElement;
    public ?int $idLU;
    public ?int $idLemma;
    public ?int $idSentenceMM;
    public ?int $idObjectMM;
    public ?object $sentenceMM;
    public ?object $objectMM;

    public function __construct(int $id = null)
    {
        parent::__construct(ObjectSentenceMMModel::class, $id);
    }

    public function getAnnotation($idSentenceMM)
    {
        $criteria = $this->getCriteria();
        $objects = $criteria
            ->select('*')
            ->where('idSentenceMM', '=', $idSentenceMM)
            ->where('idObjectMM <> -1')
            ->orderBy('startWord')
            ->asQuery()->getResult();
        $lu = new LU();
        $result = [];
        foreach ($objects as $object) {
            if ($object['idLU']) {
                $lu->getById($object['idLU']);
                $object['lu'] = $lu->getName();
            }
            $result[] = (object)$object;
        }
        return $result;
    }

    public function updateAnnotation($data)
    {
        $transaction = $this->beginTransaction();
        try {
            if ($data->idObjectSentenceMM == -1) {
                $deleteCriteria = $this->getDeleteCriteria();
                $deleteCriteria->where('idObjectMM', '=', $data->idObjectMM);
                $deleteCriteria->where('idSentenceMM', '=', $data->idSentenceMM);
                $deleteCriteria->delete();
                $this->setPersistent(false);
            } else {
                $this->getById($data->idObjectSentenceMM);
            }
            if (is_numeric($data->name)) { // idLemma
                $lemma = new Lemma($data->name);
                $data->name = $lemma->getName();
                $data->idLemma = $lemma->getIdlemma();
            }
            $this->setData($data);
            $this->save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteAnnotation($toDelete)
    {
        $transaction = $this->beginTransaction();
        try {
            $deleteCriteria = $this->getDeleteCriteria();
            $deleteCriteria->where('idObjectSentenceMM', 'IN', $toDelete);
            $deleteCriteria->delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateObject($data)
    {
        if ($data->idObjectSentenceMM != -1) {
            $this->getById($data->idObjectSentenceMM);
        }
        PersistenceManager::beginTransaction();
        try {
            $object = (object)[
                'idFrameElement' => $data->idFrameElement,
                'idLemma' => $data->idLemma,
            ];
            $this->saveData($object);
            Timeline::addTimeline('objectsentencemm', $this->getId(),'U');
            PersistenceManager::commit();
        } catch (\Exception $e) {
            PersistenceManager::rollback();
            throw new \Exception($e->getMessage());
        }
    }


}