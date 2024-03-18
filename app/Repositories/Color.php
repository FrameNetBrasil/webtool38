<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class Color extends Repository
{
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('idColor, name, rgbFg, rgbBg')->orderBy('idColor');
        if ($filter->idColor) {
            $criteria->where("idColor = {$filter->idColor}");
        }
        return $criteria;
    }

    public function listForSelect()
    {
        $criteria = $this->getCriteria()->select("idColor, name, rgbFg, rgbBg")->orderBy('name');
        return $criteria;
    }

}
