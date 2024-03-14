<?php

namespace App\Repositories;

use App\Models\ConceptModel;
use App\Models\ConstructionElementModel;
use App\Models\ConstructionModel;
use App\Models\DomainModel;
use App\Models\EntityModel;
use App\Models\FrameElementModel;
use App\Models\FrameModel;
use App\Models\GenericLabelModel;
use App\Models\LayerTypeModel;
use App\Models\LUModel;
use App\Models\QualiaModel;
use App\Models\RelationModel;
use App\Models\SemanticTypeModel;
use App\Models\ViewRelationModel;
use App\Services\AppService;
use Orkester\Persistence\Repository;
use Orkester\Persistence\Enum\Join;

class ViewRelation extends Repository
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


    public function __construct(int $id = null)
    {
        parent::__construct(RelationModel::class, $id);
    }

    public function listByType($relationType, $entity1Type, $entity2Type = '', $idEntity1 = '', $idEntity2 = '')
    {
        $criteria = $this->getCriteria()->select('relationType, entity1Type, entity2Type, entity3Type, idEntity1, idEntity2, idEntity3');
        $criteria->where("relationType = '{$relationType}'");
        $criteria->where("entity1Type = '{$entity1Type}'");
        if ($entity2Type != '') {
            $criteria->where("entity2Type = '{$entity2Type}'");
        }
        if ($idEntity1 != '') {
            $criteria->where("idEntity1 = {$idEntity1}");
        }
        if ($idEntity2 != '') {
            $criteria->where("idEntity2 = {$idEntity2}");
        }
        return $criteria;
    }

    public function listForFrameGraph(int $idEntity): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        return $this->getCriteria()
            ->select(['idEntityRelation','idRelationType', 'relationType.entry', 'entity1Type', 'entity2Type', 'entity3Type', 'idEntity1', 'idEntity2', 'idEntity3',
                'frame1.name frame1Name',
                'frame2.name frame2Name',
            ])
            ->where('entity1Type', '=', 'FR')
            ->where('entity2Type', '=', 'FR')
            ->where('frame1.idLanguage', '=', $idLanguage)
            ->where('frame2.idLanguage', '=', $idLanguage)
            ->whereRaw("((idEntity1 = {$idEntity}) or (idEntity2 = {$idEntity}))")
            ->getResult();
    }

    /*
     * Remove rel_inheritance_cxn
    */
    public function deleteInheritanceCxn($idEntityRelation)
    {
        $transaction = $this->beginTransaction();
        try {
            $cmd = <<<HERE
DELETE FROM entityrelation
WHERE idEntityRelation = {$idEntityRelation}

HERE;
            $this->getDb()->executeCommand($cmd);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \exception($e->getMessage());
        }

    }


}

