<?php

namespace App\Services;

use App\Repositories\Lemma;
use Orkester\Manager;


class LemmaService
{

    public static function listForSelect()
    {
        $data = Manager::getData();
        debug($data);
        $q = $data->q ?? '';
        $frame = new Lemma();
        debug($frame->listForSelect($q)->getResult());
        return $frame->listForSelect($q)->getResult();
    }

}