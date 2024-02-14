<?php

namespace App\Services;

use App\Repositories\Base;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\LU;
use App\Repositories\ViewFrame;
use App\Repositories\ViewLU;
use Orkester\Manager;


class ReportLUService
{

    public static function report(int $idLU): array
    {
        $lu = new ViewLU($idLU);
        $lu->retrieveAssociation("language");
        $lu->retrieveAssociation("frame", AppService::getCurrentIdLanguage());
        $report['lu'] = $lu;
        return $report;
    }

}