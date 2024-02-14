<?php

namespace App\Models;

use App\Services\AppService;
use Orkester\Persistence\Enum\Join;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Maestro\Persistence\Model;
use Orkester\Persistence\Map\ClassMap;

class StaticObjectMMModel extends Model
{

    public static function map(ClassMap $classMap): void
    {
        
        self::table('staticobjectmm');
        self::attribute('idStaticObjectMM', key: Key::PRIMARY);
        self::attribute('scene');
        self::attribute('nobdnbox');
        self::attribute('idFlickr30kEntitiesChain', type: Type::INTEGER);
        self::associationMany('staticBBoxMM', model: StaticBBoxMMModel::class, keys: 'idStaticObjectMM');
    }

    public static function listByDocumentMM(int $idDocumentMM): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $feCriteria = FrameElementModel::getCriteria()
            ->select(['idFrameElement as idFE', 'idFrame', 'name', 'frame.name as frame'])
            ->where('entries.idLanguage', '=', $idLanguage)
            ->where('frame.entries.idLanguage', '=', $idLanguage);
        $criteria = self::getCriteria()
            ->setAssociationType('lu', Join::LEFT)
            ->where('idDocumentMM', '=', $idDocumentMM)
            ->select([
                'idObjectMM',
                'startFrame',
                'endFrame',
                'startTime',
                'endTime',
                'status',
                'origin',
                'idFrameElement',
                'fe.name as fe',
                'fe.frame',
                'idLU',
                'lu.name as lu'
            ])
            ->joinSub($feCriteria, 'fe', 'idFrameElement', '=', 'fe.idFE', 'left')
            ->orderBy('startFrame')
            ->orderBy('endFrame');
        $objects = $criteria
            ->getResult()
            ->toArray();
        foreach ($objects as $i => $object) {
//            $objects[$i]['fe'] = null;
//            $objects[$i]['frameElement'] = '';
//            if ($object['idFrameElement'] != '') {
//                $frameElement = FrameElementModel::getById($object['idFrameElement']);
//                $frame = FrameModel::getById($frameElement['idFrame']);
//                $objects[$i]['fe'] = $frameElement;
//                $objects[$i]['frameElement'] = $frame['name'] . '.' . $frameElement['name'];
//            }
//            $objects[$i]['lu'] = '';
//            if ($object['idLU'] != '') {
//                $lu = LUModel::find($object['idLU']);
//                $frame = FrameModel::getById($lu['idFrame']);
//                $objects[$i]['lu'] = $frame['name'] . '.' . $lu['name'];
//            }
//            $objects[$i]['frames'] = ObjectFrameMMModel::listByObjectMM($object['idObjectMM']);
            $objects[$i]['frameElement'] = $object['frame'] . '.' . $object['fe'];
            $objects[$i]['frames'] = [];
            $objects[$i]['idObject'] = $i + 1;
            $objects[$i]['idObjectClone'] = $objects[$i]['idObject'];
            $objects[$i]['hidden'] = false;
        }
        return $objects;
    }

    public static function listFramesByObjectMM(int $idObjectMM): array
    {
        return ObjectFrameMMModel::listByObjectMM($idObjectMM);
    }

    public static function create(int $idDocumentMM, array $object): int
    {
        Model::beginTransaction();
        $data = [
            'idDocumentMM' => $idDocumentMM,
            'name' => '',
            'startFrame' => $object['frame'],
            'endFrame' => $object['frame'],
            'startTime' => $object['time'],
            'endTime' => $object['time'],
            'status' => '1',
            'origin' => '2',
            'idFrameElement' => null,
            'idFlickr30k' => null,
            'idImageMM' => null,
            'idLemma' => null,
            'idLU' => null
        ];
        $idObjectMM = self::save($data);
        $dataFrames = [
            'frameNumber' => $object['frame'],
            'frameTime' => $object['time'],
//            'x' => $object['bbox']['x'],
//            'y' => $object['bbox']['y'],
//            'width' => $object['bbox']['width'],
//            'height' => $object['bbox']['height'],
            'x' => $object['x'],
            'y' => $object['y'],
            'width' => $object['width'],
            'height' => $object['height'],
            'blocked' => 0,
            'idObjectMM' => $idObjectMM
        ];
        ObjectFrameMMModel::save($dataFrames);
        Model::commit();
        return $idObjectMM;
    }


}