<?php

namespace App\Repositories;

use App\Models\StaticObjectSentenceMMModel;
use Maestro\Persistence\Repository;

class StaticObjectSentenceMM extends Repository
{

    public ?int $idStaticObjectSentenceMM;
    public ?string $name;
    public ?string $startWord;
    public ?string $endWord;
    public ?int $idStaticSentenceMM;
    public ?int $idStaticObjectMM;
    public ?object $staticSentenceMM;
    public ?object $staticObjectMM;

    public function __construct(int $id = null)
    {
        parent::__construct(StaticObjectSentenceMMModel::class, $id);
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
        $transaction = $this->beginTransaction();
        try {
            $object = (object)[
                'idFrameElement' => $data->idFrameElement,
                'idLemma' => $data->idLemma,
            ];
            $this->setData($object);
            $this->save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }


}
