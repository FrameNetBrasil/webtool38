<?php

namespace App\Repositories;

use App\Models\TimelineModel;
use Carbon\Carbon;
use Maestro\Manager;
use Maestro\Persistence\Repository;
use Maestro\Security\MAuth;

class Timeline extends Repository
{

    public ?int $idTimeline;
    public ?string $tlDateTime;
    public ?string $author;
    public ?string $operation;
    public ?string $tableName;
    public ?int $idTable;
    public ?int $idUser;

    public function __construct(int $id = null)
    {
        parent::__construct(TimelineModel::class, $id);
    }

    public function getDescription()
    {
        return '';//$this->getTimeline();
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idTimeline');
        if ($filter->idTimeline) {
            $criteria->where("idTimeline = {$filter->idTimeline}");
        }
        return $criteria;
    }

    public function newTimeline($tl, $operation = 'S')
    {
        $timeline = 'tl_' . $tl;
        $result = $this->getCriteria()->select('max(numOrder) as max')->where("upper(timeline) = upper('{$timeline}')")->asQuery()->getResult();
        $max = $result[0]['max'];
        $this->setPersistent(false);
        $this->operation = $operation;
        $this->tlDateTime = Carbon::now();
        $this->idUser = Base::getCurrentUser()->getId();
        $this->author = MAuth::getLogin() ? MAuth::getLogin()->login : 'offline';
        $this->save();
        return $timeline;
    }

    public function updateTimeline($oldTl, $newTl)
    {
        $oldTl = 'tl_' . $oldTl;
        $newTl = 'tl_' . $newTl;
//        $criteria = $this->getUpdateCriteria();
//        $criteria->addColumnAttribute('timeline');
//        $criteria->where("timeline = '{$oldTl}'");
//        $criteria->update($newTl);
        return $newTl;
    }

    public static function addTimeline($tableName, $idTable, $operation = 'S')
    {
        $tl = new TimeLine();
        $tl->operation = $operation;
        $tl->tlDateTime = Carbon::now();
        $tl->idUser = Base::getCurrentUser()->getId();
        $tl->author = MAuth::getLogin() ? MAuth::getLogin()->login : 'offline';
        $tl->tableName = $tableName;
        $tl->idTable = $idTable;
        $tl->save();
    }


}

