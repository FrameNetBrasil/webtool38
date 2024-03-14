<?php

namespace App\Repositories;

use App\Models\ColorModel;
use Orkester\Persistence\Repository;

class Color extends Repository
{

    public ?int $idColor;
    public ?string $name;
    public ?int $rgbFg;
    public ?int $rgbBg;

    public function __construct(int $id = null)
    {
        parent::__construct(ColorModel::class, $id);
    }

    public function getDescription()
    {
        return $this->getName();
    }

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
