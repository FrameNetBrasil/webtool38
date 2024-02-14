<?php

namespace App\Repositories;

class OriginMM extends Repository
{

    public static function config()
    {
        return array(
            'log' => array(),
            'validators' => array(),
            'converters' => array()
        );
    }

    public function getLookup() {
        $criteria = $this->getCriteria()
            ->select('idOriginMM,origin')
            ->asQuery();
        return $criteria->getResult();
    }

}
