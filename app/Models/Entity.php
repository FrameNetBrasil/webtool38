<?php

namespace App\Models;

use Orkester\Persistence\Model;

class Entity extends Model
{
    public ?int $idEntity;
    public ?string $type;
    public ?string $alias;
    public ?int $idOld;

    public static $entityModel = [
        'AS' => 'typeinstance', // annotation status
        'CE' => 'constructionelement',
        'CP' => 'concept',
        'CT' => 'typeinstance', // core type
        'CX' => 'construction',
        'FE' => 'frameelement',
        'FR' => 'frame',
        'GL' => 'genericlabel',
        'IT' => 'typeinstance', // instantiation type
        'LT' => 'labeltype',
        'LU' => 'lu',
        'PS' => 'pos',
        'SC' => 'subcorpus',
        'ST' => 'semantictype',
        'UR' => 'udrelation',
        'UV' => 'udfeature'
    ];

}
