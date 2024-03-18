<?php

namespace App\Models;

use Orkester\Persistence\Model;

class ViewRelation extends Model
{
    public ?int $idEntityRelation;
    public ?string $relationGroup;
    public ?int $idRelationType;
    public ?string $relationType;
    public ?string $prefix;
    public ?int $idEntity1;
    public ?int $idEntity2;
    public ?int $idEntity3;
    public ?string $entity1Type;
    public ?string $entity2Type;
    public ?string $entity3Type;

    public ?object $entity1;
    public ?object $entity2;
    public ?object $entity3;
    public ?object $lu1;
    public ?object $lu2;
    public ?object $frame1;
    public ?object $frame2;
    public ?object $frame;
    public ?object $construction1;
    public ?object $construction2;
    public ?object $construction;
    public ?object $semanticType1;
    public ?object $semanticType2;
    public ?object $semanticType;
    public ?object $constructionElement1;
    public ?object $constructionElement2;
    public ?object $constructionElement;
    public ?object $frameElement1;
    public ?object $frameElement2;
    public ?object $frameElement;
    public ?object $inverseFrame;
    public ?object $inverseConstruction;
    public ?object $inverseSemanticType;
    public ?object $subtypeOfConcept;
    public ?object $associatedToConcept;
    public ?object $domain;
    public ?object $layerType;
    public ?object $genericLabel;
    public ?object $qualia;

}

