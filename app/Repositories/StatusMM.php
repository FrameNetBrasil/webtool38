<?php

namespace App\Repositories;

class StatusMM extends Repository
{

    public static function config()
    {
        return array(
            'log' => array(),
            'validators' => array(),
            'converters' => array()
        );
    }

}
