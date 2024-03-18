<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Repository;

class TopFrame extends Repository
{
    public function listByFilter(object $filter)
    {
        $criteria = $this->getCriteria()
            ->select(['idTopFrame','frameBase','frameTop','frameCategory','frame.name'])
            ->where("frame.idLanguage", "=", AppService::getCurrentIdLanguage())
            ->orderBy('frame.name');
        if ($filter->idTimeline) {
            $criteria->where("idTimeline = {$filter->idTimeline}");
        }
        return $criteria;
    }

}

