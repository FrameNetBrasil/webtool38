<?php

namespace App\Repositories;

use App\Models\TimelineModel;
use App\Models\TopFrameModel;
use App\Services\AppService;
use Carbon\Carbon;
use Maestro\Manager;
use Maestro\Persistence\Repository;
use Maestro\Security\MAuth;

class TopFrame extends Repository
{
    public ?int $idTopFrame;
    public ?string $frameBase;
    public ?string $frameTop;
    public ?string $frameCategory;
    public ?float $score;

    public ?object $frame;

    public function __construct(int $id = null)
    {
        parent::__construct(TopFrameModel::class, $id);
    }

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

