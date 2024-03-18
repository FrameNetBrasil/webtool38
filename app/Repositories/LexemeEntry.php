<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class LexemeEntry extends Repository
{
    public function deleteByLemma(int $idLemma)
    {
        $this->getCriteria()
            ->where('idLemma', '=', $idLemma)
            ->delete();

    }


}
