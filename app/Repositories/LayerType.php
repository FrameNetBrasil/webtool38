<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Repository;

class LayerType extends Repository
{
    public function getName()
    {
        $criteria = $this->getCriteria()->select('entries.name as name');
        $criteria->where("idLayerType = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->fields('name');
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idLayerType');
        if ($filter->idLayerType) {
            $criteria->where("idLayerType LIKE '{$filter->idLayerType}%'");
        }
        return $criteria;
    }

//    public function listAll()
//    {
//        $criteria = $this->getCriteria()->select('idLayerType, entry, allowsApositional, isAnnotation, layerOrder, idLayerGroup, idEntity, entries.name as name')->orderBy('entries.name');
//        Base::entryLanguage($criteria);
//        return $criteria;
//    }

    public function listByGroup()
    {
        $criteria = $this->getCriteria()->select('idLayerType, entries.name name')->orderBy('idLayerGroup, entries.name');
        Base::entryLanguage($criteria);
        return $criteria;
    }

    public function listLabelType($filter)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $criteria = $this->getCriteria()->select('idLayerType, entry, genericlabel.name as labelType, genericlabel.idEntity as idLabelType')
            ->orderBy('entry, genericlabel.name');
        Base::relation($criteria, 'LayerType', 'GenericLabel', 'rel_haslabeltype');
        $criteria->where("genericlabel.idLanguage = {$idLanguage}");
        if ($filter->entry) {
            $criteria->where("entry = '{$filter->entry}'");
        }
        return $criteria;
    }

    public function getByEntry($entry)
    {
        $criteria = $this->getCriteria()
            ->select('*')
            ->where("idLanguage","=", AppService::getCurrentIdLanguage())
            ->orderBy('idLayerType');
        $criteria->where("entry","=",$entry);
        $this->retrieveFromCriteria($criteria);
    }

    public function listToLU(ViewLU $lu)
    {
        $array = array('lty_fe', 'lty_gf', 'lty_pt', 'lty_other', 'lty_target', 'lty_sent');
        $lPOS = array('V' => 'lty_verb', 'N' => 'lty_noun', 'A' => 'lty_adj', 'ADV' => 'lty_adv', 'PREP' => 'lty_prep');
        $pos = $lu->getAssociation('pos');
        if (isset($lPOS[$pos->POS])) {
            $array[] = $lPOS[$pos->POS];
        }
        $criteria = $this->getCriteria();
        $criteria->select('idLayerType, entry');
        $criteria->where('entry', 'in', $array);
        $result = $criteria->asQuery()->getResult();
        return $result;
    }

    public function listToConstruction()
    {
        $array = array('lty_ce', 'lty_cee', 'lty_cstrpt', 'lty_other', 'lty_sent', 'lty_govx', 'lty_udpos', 'lty_udrelation'); // ? 'lty_pt','lty_gf',
        $criteria = $this->getCriteria();
        $criteria->select('idLayerType, entry');
        $criteria->where('entry', 'in', $array);
        $result = $criteria->asQuery()->getResult();
        return $result;
    }

    public function listCEFE()
    {
        $array = array('lty_cefe');
        $criteria = $this->getCriteria();
        $criteria->select('idLayerType, entry');
        $criteria->where('entry', 'in', $array);
        $result = $criteria->asQuery()->getResult();
        return $result;
    }

    public function saveData($data): ?int
    {
        $data->allowsApositional = $data->allowsApositional ?: '0';
        $data->isAnnotation = $data->isAnnotation ?: '0';
        $transaction = $this->beginTransaction();
        try {
            $this->setData($data);
            if (!$this->isPersistent()) {
                $entity = new Entity();
                $entity->setAlias($data->entry);
                $entity->setType('LT');
                $entity->save();
                $this->setIdEntity($entity->getId());
                $entry = new Entry();
                $entry->newEntry($data->entry, $entity->getId());
            }
            //Base::entityTimelineSave($this->getIdEntity());
            parent::save();
            Timeline::addTimeline("layertype", $this->getId(), "S");
            $transaction->commit();
            return $this->getId();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function delete()
    {
//        Base::entityTimelineDelete($this->getIdEntity());
        Timeline::addTimeline("layertype", $this->getId(), "D");
        parent::delete();
    }


}

