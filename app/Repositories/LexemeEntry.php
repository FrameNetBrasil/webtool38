<?php

namespace App\Repositories;

use App\Models\LexemeEntryModel;
use Maestro\Persistence\Repository;

class LexemeEntry extends Repository
{

    public ?int $idLexemeEntry;
    public ?int $lexemeOrder;
    public ?int $breakBefore;
    public ?int $headWord;
    public ?int $idLexeme;
    public ?int $idLemma;
    public ?object $lemma;
    public ?object $lexeme;
    public ?object $wordForm;

    public function __construct(int $id = null)
    {
        parent::__construct(LexemeEntryModel::class, $id);
    }

    public function deleteByLemma(int $idLemma)
    {
        $this->getCriteria()
            ->where('idLemma', '=', $idLemma)
            ->delete();

    }


}
