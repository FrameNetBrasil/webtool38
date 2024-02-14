<?php

namespace App\Repositories;

use App\Models\TypeInstanceModel;
use Maestro\Persistence\Repository;

class TypeInstance extends Repository
{

    public ?int $idTypeInstance;
    public ?string $entry;
    public ?string $info;
    public ?int $flag;
    public ?int $idType;
    public ?int $idEntity;
    public ?int $idColor;
    public ?string $name;
    public ?string $description;
    public ?int $idLanguage;
    public ?object $entity;
    public ?object $color;
    public ?object $type;

    public function __construct(int $id = null)
    {
        parent::__construct(TypeInstanceModel::class, $id);
    }

    public function getDescription()
    {
        return $this->getIdTypeInstance();
    }

    public function getByEntry($entry)
    {
        $criteria = $this->getCriteria()
            ->where("entry","=",$entry);
        $this->retrieveFromCriteria($criteria);
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idTypeInstance');
        if ($filter->idTypeInstance) {
            $criteria->where("idTypeInstance LIKE '{$filter->idTypeInstance}%'");
        }
        return $criteria;
    }

    public function listCoreType()
    {
        $criteria = $this->getCriteria()->select('idTypeInstance as idCoreType, entry, entries.name')->orderBy('info');
        Base::entryLanguage($criteria);
        $criteria->where("entry like 'cty_%'");
        return $criteria;
    }

    public function listStatusType()
    {
        $criteria = $this->getCriteria()->select('idTypeInstance as idStatusType, entry, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        $criteria->where("entry like 'stt_%'");
        return $criteria;
    }

    public function listAnnotationStatus($filter, $fields = '')
    {
        $criteria = $this->getCriteria();
        if ($fields != '') {
            $criteria->select($fields);
        } else {
            $criteria->select('idTypeInstance as idAnnotationStatus, entry, entries.name as name, idColor, color.name as colorName, color.rgbFg, color.rgbBg')->orderBy('entry');
        }
        Base::entryLanguage($criteria);
        $criteria->where("entry like 'ast_%'");
        if ($filter->entry) {
            $criteria->where("entry LIKE '{$filter->entry}%'");
        }
        return $criteria;
    }

    public function listConstraintType()
    {
        $criteria = $this->getCriteria()->select('idTypeInstance as idConstraintType, entry, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        $criteria->where("entry like 'typ_con_%'");
        return $criteria;
    }

    public function listConceptType()
    {
        $criteria = $this->getCriteria()->select('idTypeInstance as idConceptType, entry, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        $criteria->where("entry like 'typ_c5_%'");
        return $criteria;
    }

    public function listQualiaType()
    {
        $criteria = $this->getCriteria()->select('idTypeInstance as idQualiaType, entry, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        $criteria->where("entry like 'qla_%'");
        return $criteria;
    }

    public function getIdQualiaTypeByEntry($entry)
    {
        $criteria = $this->getCriteria()->select('idTypeInstance')->orderBy('info');
        $criteria->where("entry = '{$entry}'");
        return $criteria->asQuery()->getResult()[0]['idTypeInstance'];
    }

    public function getIdInstantiationTypeByEntry($entry)
    {
        $criteria = $this->getCriteria()->select('idTypeInstance as idInstantiationType')->orderBy('info');
        $criteria->where("entry = '{$entry}'");
        return $criteria->asQuery()->getResult()[0]['idInstantiationType'];
    }

    public function getIdCoreTypeByEntry($entry)
    {
        $criteria = $this->getCriteria()->select('idTypeInstance as idCoreType')->orderBy('info');
        $criteria->where("entry = '{$entry}'");
        return $criteria->asQuery()->getResult()[0]['idCoreType'];
    }

    public function listBFF()
    {
        $criteria = $this->getCriteria()->select('idTypeInstance as idBFF, entry, entries.description as description')->orderBy('info');
        Base::entryLanguage($criteria);
        $criteria->where("entry like 'bff_%'");
        return $criteria;
    }

    public function listUDNumber()
    {
        $cmd = <<<HERE
        SELECT ud.idEntity, ud.info
        FROM UDFeature ud
            INNER JOIN TypeInstance ti on (ud.idTypeInstance = ti.idTypeInstance)
        WHERE (ti.entry = 'udf_number[abs]')
        ORDER BY ud.info
HERE;

        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

    public function save(): ?int
    {
        //Base::entityTimelineSave($this->getIdEntity());
        parent::save();
        Timeline::addTimeline("typeinstance", $this->getId(), "S");
        return $this->getId();
    }

    public function delete()
    {
//        Base::entityTimelineDelete($this->getIdEntity());
        Timeline::addTimeline("typeinstance", $this->getId(), "D");
        parent::delete();
    }

}
