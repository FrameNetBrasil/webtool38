<?php

namespace App\Repositories;

use App\Services\AppService;
use App\Services\RelationService;
use Orkester\Persistence\Repository;

class Frame extends Repository
{
    public function getById(int $id): void
    {
        $criteria = $this->getCriteria()
            ->where('idFrame', '=', $id)
            ->where('idLanguage', '=', AppService::getCurrentIdLanguage());
        $this->retrieveFromCriteria($criteria);
    }

    public function getByName(string $name): void
    {
        $criteria = $this->getCriteria()
            ->where('name', '=', $name)
            ->where('idLanguage', '=', AppService::getCurrentIdLanguage());
        $this->retrieveFromCriteria($criteria);
    }

    public function create($data)
    {
        $this->beginTransaction();
        try {
            $baseEntry = strtolower('frm_' . $data->nameEn);
            $entity = new Entity();
            $idEntity = $entity->create('FR', $baseEntry);
            $entry = new Entry();
            $entry->create($baseEntry, $data->nameEn, $idEntity);
            $id = $this->saveData([
                'entry' => $baseEntry,
                'active' => 1,
                'defaultName' => $data->nameEn,
                'idEntity' => $idEntity
            ]);
            Timeline::addTimeline("frame", $id, "C");
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function getDescription()
    {
        return $this->getEntry();
    }

    public function getByIdEntity($idEntity)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("frame.idEntity = {$idEntity}");
        Base::entryLanguage($criteria);
        $this->retrieveFromCriteria($criteria);
    }

    public function getByEntry($entry)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("entry = '{$entry}'");
        $this->retrieveFromCriteria($criteria);
    }

    public function getEntryObject()
    {
        $criteria = $this->getCriteria()->select('entries.name, entries.description, entries.nick');
        $criteria->where("idFrame = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->asObjectArray()[0];
    }



    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('idFrame, entry, active, idEntity, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idFrame) {
            $criteria->where("idFrame = {$filter->idFrame}");
        }
        if ($filter->lu) {
            $criteria->distinct(true);
            Base::relation($criteria, 'LU lu', 'Frame', 'rel_evokes');
            $criteria->where("lu.name LIKE '{$filter->lu}%'");
        }
        if ($filter->fe) {
            $criteriaFE = FrameElement::getCriteria();
            $criteriaFE->select('frame.idFrame, entries.name as name');
            $criteriaFE->where("entries.name LIKE '{$filter->fe}%'");
            Base::entryLanguage($criteriaFE);
            Base::relation($criteriaFE, 'FrameElement', 'Frame', 'rel_elementof');
            $criteria->distinct(true);
            $criteria->tableCriteria($criteriaFE, 'fe');
            $criteria->where("idFrame = fe.idFrame");
        }
        if ($filter->frame) {
            $criteria->where("entries.name LIKE '{$filter->frame}%'");
        }
        if ($filter->idLU) {
            Base::relation($criteria, 'LU lu', 'Frame', 'rel_evokes');
            if (is_array($filter->idLU)) {
                $criteria->where("lu.idLU", "IN", $filter->idLU);
            } else {
                $criteria->where("lu.idLU = {$filter->idLU}");
            }
        }
        return $criteria;
    }

    public function listForExport($idFrames)
    {
        $criteria = $this->getCriteria()->select('idFrame, entry, active, idEntity')->orderBy('entry');
        $criteria->where("idFrame", "in", $idFrames);
        return $criteria;
    }

    public function listForSelect($name = '')
    {
        $criteria = $this->getCriteria()->select(['idFrame', 'entries.name'])->orderBy('entries.name');
        Base::entryLanguage($criteria);
        $name = (strlen($name) > 1) ? $name : 'none';
        $criteria->where("upper(entries.name)", "startswith", strtoupper($name));
        return $criteria;
    }

    public function listFE()
    {
        $fe = new FrameElement();
        $criteria = $fe->getCriteria()->select(['idFrameElement', 'entry', 'entries.name', 'entries.description', 'coreType', 'color.rgbFg', 'color.rgbBg',
            'typeInstance.idTypeInstance as idCoreType', 'color.idColor']);
        Base::entryLanguage($criteria);
        $criteria->where("idFrame = {$this->idFrame}");
        $criteria->orderBy('typeInstance.idTypeInstance, entries.name');
        return $criteria;
    }

    public function listLU()
    {
        $lu = new LU();
        $criteria = $lu->getCriteria()
            ->select(['idLU', 'name', 'senseDescription'])
            ->where("idFrame", "=", $this->idFrame)
            ->where("lemma.idLanguage", "=", AppService::getCurrentIdLanguage())
            ->orderBy('name');
        return $criteria;
    }

    public static function listByFrameDomain(int $idSemanticTypeEntity)
    {
        $relation = new ViewRelation();
        $criteria = $relation->getCriteria()
            ->select(['idEntity1 as idEntity', 'frame1.name'])
            ->where("entry", "=", "rel_framal_domain")
            ->where("idEntity2", "=", $idSemanticTypeEntity)
            ->where("frame1.idLanguage", "=", AppService::getCurrentIdLanguage())
            ->orderBy('frame1.name');
        return $criteria;
    }

    public function listFECoreSet(): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $idFrame = $this->idFrame;
        $cmd = <<<HERE

        SELECT e1.name fe1, e2.name fe2
        FROM view_relation r
          JOIN view_frameelement fe1
            ON (r.idEntity1 = fe1.idEntity)
          JOIN entry e1
            ON (fe1.entry = e1.entry)
          JOIN view_frameelement fe2
            ON (r.idEntity2 = fe2.idEntity)
          JOIN entry e2
            ON (fe2.entry = e2.entry)
          WHERE (r.relationtype = 'rel_coreset')
            AND (fe1.idFrame     = {$idFrame})
            AND (fe2.idFrame     = {$idFrame})
            AND (e1.idLanguage   = {$idLanguage})
            AND (e2.idLanguage   = {$idLanguage})
HERE;
//        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        $result = $this->query($cmd);
        debug($result);
        $index = [];
        $i = 0;
        foreach ($result as $row) {
            if (!isset($index[$row['fe1']]) && !isset($index[$row['fe2']])) {
                $i++;
                $index[$row['fe1']] = $i;
                $index[$row['fe2']] = $i;
            } elseif (!isset($index[$row['fe1']])) {
                $index[$row['fe1']] = $index[$row['fe2']];
            } else {
                $index[$row['fe2']] = $index[$row['fe1']];
            }
        }
        $feCoreSet = [];
        foreach ($index as $fe => $i) {
            $feCoreSet[$i][] = $fe;
        }
        return $feCoreSet;
    }

//    public function listAll($idLanguage)
//    {
//        $criteria = $this->getCriteria()->select('*, entries.name as name')->orderBy('entries.name');
//        Base::entryLanguage($criteria);
//        return $criteria;
//    }

    public function listDirectRelations()
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $cmd = <<<HERE

        SELECT RelationType.entry, entry_relatedFrame.name, relatedFrame.idEntity, relatedFrame.idFrame, EntityRelation.idEntityRelation
        FROM Frame
            INNER JOIN Entity entity1
                ON (Frame.idEntity = entity1.idEntity)
            INNER JOIN EntityRelation
                ON (entity1.idEntity = EntityRelation.idEntity1)
            INNER JOIN RelationType
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity2
                ON (EntityRelation.idEntity2 = entity2.idEntity)
            INNER JOIN Frame relatedFrame
                ON (entity2.idEntity = relatedFrame.idEntity)
            INNER JOIN Entry entry_relatedFrame
                ON (relatedFrame.entry = entry_relatedFrame.entry)
        WHERE (Frame.idFrame = {$this->getId()})
            AND (RelationType.entry in (
                'rel_causative_of',
                'rel_inchoative_of',
                'rel_inheritance',
                'rel_perspective_on',
                'rel_precedes',
                'rel_see_also',
                'rel_subframe',
                'rel_using'))
           AND (entry_relatedFrame.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_relatedFrame.name

HERE;
        //$result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,idFrame');
        $result = $this->query($cmd);
        return $result;
    }

    public function listInverseRelations()
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $cmd = <<<HERE

        SELECT RelationType.entry, entry_relatedFrame.name, relatedFrame.idEntity, relatedFrame.idFrame, EntityRelation.idEntityRelation
        FROM Frame
            INNER JOIN Entity entity2
                ON (Frame.idEntity = entity2.idEntity)
            INNER JOIN EntityRelation
                ON (entity2.idEntity = EntityRelation.idEntity2)
            INNER JOIN RelationType
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity1
                ON (EntityRelation.idEntity1 = entity1.idEntity)
            INNER JOIN Frame relatedFrame
                ON (entity1.idEntity = relatedFrame.idEntity)
            INNER JOIN Entry entry_relatedFrame
                ON (relatedFrame.entry = entry_relatedFrame.entry)
        WHERE (Frame.idFrame = {$this->getId()})
            AND (RelationType.entry in (
                'rel_causative_of',
                'rel_inchoative_of',
                'rel_inheritance',
                'rel_perspective_on',
                'rel_precedes',
                'rel_see_also',
                'rel_subframe',
                'rel_using'))
           AND (entry_relatedFrame.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_relatedFrame.name

HERE;
        //$result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,idFrame');
        $result = $this->query($cmd);
        return $result;
    }

    public function registerTemplate($idTemplate)
    {
        $template = new Template($idTemplate);
        $fes = $template->listFEforNewFrame()->asQuery()->getResult();
        Base::createEntityRelation($this->getIdEntity(), 'rel_hastemplate', $template->getIdEntity());
        $frameElement = new FrameElement();
        foreach ($fes as $fe) {
            $newFE = new \StdClass();
            $newFE->entry = $this->getEntry() . '_' . $fe['entry'] . '_' . $template->getEntry();
            $newFE->idCoreType = $fe['idCoreType'];
            $newFE->idColor = $fe['idColor'];
            $newFE->idEntity = $fe['idEntity'];
            $newFE->idFrame = $this->getId();
            $frameElement->setPersistent(false);
            $frameElement->setData($newFE);
            $frameElement->save($newFE);
            Base::createEntityRelation($frameElement->getIdEntity(), 'rel_hastemplate', $newFE->idEntity);
        }
    }

//    public function save(): ?int
//    {
//        $transaction = $this->beginTransaction();
//        try {
//            $entity = new Entity();
//            $entity->setAlias($this->getEntry());
//            $entity->setType('FR');
//            $entity->save();
//            $entry = new Entry();
//            $entry->newEntry($this->getEntry(), $entity->getId());
//            $this->setIdEntity($entity->getId());
//            $this->setActive(true);
//            //Base::entityTimelineSave($this->getIdEntity());
//            $idFrame = parent::save();
//            Timeline::addTimeline("frame", $this->getId(), "S");
////            if ($data->idTemplate) {
////                $this->registerTemplate($data->idTemplate);
////            }
//            $transaction->commit();
//            return $idFrame;
//        } catch (\Exception $e) {
//            $transaction->rollback();
//            throw new \Exception($e->getMessage());
//        }
//    }

    public function delete()
    {
        $this->beginTransaction();
        try {
            $idEntity = $this->idEntity;
            // remove entry
            $entry = new Entry();
            $entry->deleteByIdEntity($idEntity);
            // remove frame-relations
            RelationService::deleteAll($idEntity);
            // remove this frame
            Timeline::addTimeline("frame", $this->getId(), "D");
            parent::delete();
            // remove entity
            $entity = new Entity($idEntity);
            $entity->delete();
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
            Base::updateTimeLine($this->getEntry(), $newEntry);
            $entity = new Entity($this->getIdEntity());
            $entity->setAlias($newEntry);
            $entity->save();
            $entry = new Entry();
            $entry->updateEntry($this->getEntry(), $newEntry);
            $this->setEntry($newEntry);
            parent::save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function getRelations($empty = false)
    {
        $relations = ['direct' => [], 'inverse' => []];
        if (!$empty) {
            $relations['direct'] = $this->listDirectRelations();
            $relations['inverse'] = $this->listInverseRelations();
        }
        return $relations;
    }

    public function createNew($data, $inheritsFromBase)
    {
        $relations = $this->getRelations(true);
        $transaction = $this->beginTransaction();
        try {
            $this->save($data);
            Timeline::addTimeline("frame", $this->getId(), "S");
//            if ($data->idTemplate) {
//                if ($inheritsFromBase) {
//                    $template = new Template($data->idTemplate);
//                    $base = $template->getBaseFrame()->asQuery()->getResult();
//                    if (count($base)) {
//                        $idFrameBase = $base[0]['idFrame'];
//                        $frameBase = new Frame($idFrameBase);
//                        $relations = $frameBase->getRelations();
//                        Base::createEntityRelation($frameBase->getIdEntity(), 'rel_inheritance', $this->getIdEntity());
//                    }
//                }
//            }
            $transaction->commit();
            return $relations;
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function createFromData($frame)
    {
        $this->setPersistent(false);
        $this->setEntry($frame->entry);
        $this->setActive($frame->active);
        $this->setIdEntity($frame->idEntity);
        parent::save();
        Timeline::addTimeline("frame", $this->getId(), "S");
    }

    public function getClassification(): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $cmd = <<<HERE

        SELECT RelationType.entry, entry_semanticType.name
        FROM Frame
            INNER JOIN Entity entity1
                ON (Frame.idEntity = entity1.idEntity)
            INNER JOIN EntityRelation
                ON (entity1.idEntity = EntityRelation.idEntity1)
            INNER JOIN RelationType
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity2
                ON (EntityRelation.idEntity2 = entity2.idEntity)
            INNER JOIN SemanticType
                ON (entity2.idEntity = semanticType.idEntity)
            INNER JOIN Entry entry_semanticType
                ON (semanticType.idEntity = entry_semanticType.idEntity)
        WHERE (Frame.idFrame = {$this->getId()})
            AND (RelationType.entry in (
                'rel_framal_type',
                'rel_framal_domain',
                'rel_framal_cluster'))
           AND (entry_semanticType.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_semanticType.name

HERE;
//        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name');
        $result = collect($this->query($cmd))->groupBy('entry')->all();
        return $result;
    }

    public function getClassificationLabels()
    {
        $classification = [];
        $result = $this->getClassification();
        foreach ($result as $framal => $values) {
            foreach ($values as $row) {
                $classification[$framal][] = $row['name'];
            }
        }
        $classification['id'][] = "#" . $this->idFrame;
        return $classification;
    }

    public function listFEDirectRelations(int $idEntityRelationBase)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $cmd = <<<HERE

        SELECT EntityRelation.idEntityRelation, RelationType.entry,
               entry_fe.name feName, fe.coreType feCoreType, fe.idFrameElement feIdFrameElement, fe.idColor feIdColor, fe.idEntity feIdEntity,
               entry_relatedFE.name relatedFEName, relatedFE.coreType relatedFECoreType, relatedFE.idFrameElement relatedFEIdFrameElement, relatedFE.idColor relatedFEIdColor, relatedFE.idEntity relatedFEIdEntity
        FROM FrameElement fe
            INNER JOIN Entity entity1
                ON (fe.idEntity = entity1.idEntity)
            INNER JOIN EntityRelation
                ON (entity1.idEntity = EntityRelation.idEntity1)
            INNER JOIN RelationType
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity2
                ON (EntityRelation.idEntity2 = entity2.idEntity)
            INNER JOIN FrameElement relatedFE
                ON (entity2.idEntity = relatedFE.idEntity)
            INNER JOIN Entry entry_fe
                ON (fe.idEntity = entry_fe.idEntity)
            INNER JOIN Entry entry_relatedFE
                ON (relatedFE.idEntity = entry_relatedFE.idEntity)
        WHERE (EntityRelation.idRelation = {$idEntityRelationBase})
            AND (RelationType.entry in (
                'rel_causative_of',
                'rel_inchoative_of',
                'rel_inheritance',
                'rel_perspective_on',
                'rel_precedes',
                'rel_see_also',
                'rel_subframe',
                'rel_using'))
            AND (entry_fe.idLanguage = {$idLanguage} )
            AND (entry_relatedFE.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_fe.name

HERE;
        $result = $this->query($cmd);
        return $result;
    }

    /*
    public function listFEDirectRelations(int $idFrame, int $idRelatedFrame)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $cmd = <<<HERE

        SELECT EntityRelation.idEntityRelation, RelationType.entry,
               entry_fe.name feName, fe.coreType feCoreType, fe.idFrameElement feIdFrameElement, fe.idColor feIdColor,
               entry_relatedFE.name relatedFEName, relatedFE.coreType relatedFECoreType, relatedFE.idFrameElement relatedFEIdFrameElement, relatedFE.idColor relatedFEIdColor
        FROM FrameElement fe
            INNER JOIN Entity entity1
                ON (fe.idEntity = entity1.idEntity)
            INNER JOIN EntityRelation
                ON (entity1.idEntity = EntityRelation.idEntity1)
            INNER JOIN RelationType
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity2
                ON (EntityRelation.idEntity2 = entity2.idEntity)
            INNER JOIN FrameElement relatedFE
                ON (entity2.idEntity = relatedFE.idEntity)
            INNER JOIN Entry entry_fe
                ON (fe.idEntity = entry_fe.idEntity)
            INNER JOIN Entry entry_relatedFE
                ON (relatedFE.idEntity = entry_relatedFE.idEntity)
        WHERE (fe.idFrame = {$idFrame})
            AND (relatedFE.idFrame = $idRelatedFrame)
            AND (RelationType.entry in (
                'rel_causative_of',
                'rel_inchoative_of',
                'rel_inheritance',
                'rel_perspective_on',
                'rel_precedes',
                'rel_see_also',
                'rel_subframe',
                'rel_using'))
            AND (entry_fe.idLanguage = {$idLanguage} )
            AND (entry_relatedFE.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_fe.name

HERE;
        $result = $this->query($cmd);
        return $result;
    }
    */

}
