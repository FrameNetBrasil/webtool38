<?php

namespace App\Services;

use App\Repositories\Color;
use App\Repositories\LayerType;
use App\Repositories\Type;

class AnnotationService
{
    public static function getLayerType(): object
    {
        $lt = new LayerType();
        $lts = $lt->listAll();
        $result = new \stdclass();
        foreach ($lts as $row) {
            $node = new \stdclass();
            $node->entry = $row['entry'];
            $node->name = $row['name'];
            $idLT = $row['idLayerType'];
            $result->$idLT = $node;
        }
        return $result;
    }

    public static function getInstantiationType(): array
    {
        $type = new Type();
        $instances = $type->getInstantiationType()->asQuery()->getResult();
        $array = [];
        $id = [];
        $obj = new \stdclass();
        foreach ($instances as $instance) {
            if ($instance['instantiationType'] != 'APos') {
                $value = $instance['idInstantiationType'];
                $obj->$value = $instance['instantiationType'];
                $node = new \stdclass();
                $id[$instance['instantiationType']] = $instance['idInstantiationType'];
//                if ($instance['instantiationType'] == 'Normal') {
//                    $node->idInstantiationType = 0;
//                    $node->instantiationType = '-';
//                } else {
//                    $node->idInstantiationType = $instance['idInstantiationType'];
//                    $node->instantiationType = $instance['instantiationType'];
//                }
                $node->value = $instance['idInstantiationType'];
                $node->label = $instance['instantiationType'];
                $array[] = $node;
            }
        }
        $result = [
            'id' => $id,
            'array' => $array,
            'obj' => $obj
        ];
        return $result;
    }

    public static function getColor()
    {
        $color = new Color();
        $colors = $color->listAll();
        $result = new \stdclass();
        foreach ($colors as $c) {
            $node = new \stdclass();
            $node->rgbFg = '#' . $c['rgbFg'];
            $node->rgbBg = '#' . $c['rgbBg'];
            $idColor = $c['idColor'];
            $result->$idColor = $node;
        }
        return $result;
    }

    public static function getColorArray()
    {
        $color = new Color();
        $colors = $color->listAll();
        $result = [];
        foreach ($colors as $c) {
            $node = new \stdclass();
            $node->rgbFg = '#' . $c['rgbFg'];
            $node->rgbBg = '#' . $c['rgbBg'];
            $idColor = $c['idColor'];
            $result[$idColor] = $node;
        }
        return $result;
    }
}