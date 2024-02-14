<?php

namespace App\Repositories;

use App\Models\LayerModel;
use Maestro\Persistence\Repository;

class Layer extends Repository
{

    public ?int $idLayer;
    public ?int $rank;
    public ?int $idLayerType;
    public ?int $idAnnotationSet;
    public ?object $layerType;
    public ?object $annotationSet;
    public ?array $labels;

    public function __construct(int $id = null)
    {
        parent::__construct(LayerModel::class, $id);
    }

    public function getDescription()
    {
        return $this->getIdLayer();
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idLayer');
        if ($filter->idLayer) {
            $criteria->where("idLayer LIKE '{$filter->idLayer}%'");
        }
        return $criteria;
    }

    public function listByAnnotationSet($idAnnotationSet)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("idAnnotationSet = {$idAnnotationSet}");
        return $criteria;
    }

    public function save(): ?int
    {
        parent::save();
        Timeline::addTimeline("layer", $this->getId(), "S");
        return $this->getId();
    }

    public function deleteByAnnotationSet(int $idAnnotationSet)
    {
        $this->beginTransaction();
        try {
            $label = new Label();
            $criteria = $this->listByAnnotationSet($idAnnotationSet);
            $result = $criteria->asQuery()->getResult();
            foreach ($result as $layer) {
                $label->getCriteria()
                    ->where("idLayer", "=", $layer['idLayer'])
                    ->delete();
                $this->getCriteria()
                    ->where("idLayer", "=", $layer['idLayer'])
                    ->delete();
            }
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function delete()
    {
        $this->beginTransaction();
        try {
            $label = new Label();
            $label->getCriteria()
                ->where("idLayer", "=", $this->getId())
                ->delete();
            Timeline::addTimeline("layer", $this->getId(), "D");
            parent::delete();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }

}

