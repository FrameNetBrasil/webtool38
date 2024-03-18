<?php

namespace App\Models;

use Orkester\Persistence\Model;
use App\Repositories\Frame as FrameRepository;

class Frame extends Model
{

    public ?int $idFrame;
    public ?string $entry;
    public ?int $active;
    public ?int $idEntity;
    public ?string $name;
    public ?string $description;
    public ?int $idLanguage;
    public ?object $entity;
    public ?array $lus;
    public ?array $fes;
    public ?array $entries;
    public ?array $relations;
    public ?array $inverseRelations;

    public function getClassificationLabels()
    {
        $classification = [];
        $result = FrameRepository::getClassification($this->idFrame);
        debug($result);
        foreach ($result as $framal => $values) {
            foreach ($values as $row) {
                $classification[$framal][] = $row['name'];
            }
        }
        $classification['id'][] = "#" . $this->idFrame;
        return $classification;
    }


}
