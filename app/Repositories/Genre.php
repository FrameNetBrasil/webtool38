<?php


namespace App\Repositories;

use App\Models\GenreModel;
use Maestro\Persistence\Repository;

class Genre extends Repository {
    public ?int $idGenre;
    public ?string $entry;
    public ?int $idGenreType;
    public ?int $idEntity;

    public function __construct(int $id = null)
    {
        parent::__construct(GenreModel::class, $id);
    }

    
    public function getDescription(){
        return $this->entry;
    }

    public function listAllGenres()
    {
        $criteria = $this->getCriteria()->select('idGenre, entries.name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        return $criteria;
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('idGenre,idGenreType, entry, entries.name name')->orderBy('idGenre');
        if ($filter->idGenre){
            $criteria->where("idGenre = {$filter->idGenre}");
        }
        if ($filter->idGenreType){
            $criteria->where("idGenreType = {$filter->idGenreType}");
        }
        if ($filter->entry){
            $criteria->where("entry LIKE '%{$filter->entry}%'");
        }
        Base::entryLanguage($criteria);
        return $criteria;
    }
/*
    public function save($data)
    {
        $this->setData($data);
        $transaction = $this->beginTransaction();
        try {
            if (!$this->isPersistent()) {
                $entity = new Entity();
                $entity->setAlias($this->getEntry());
                $entity->setType('GR');
                $entity->save();
                $this->setIdEntity($entity->getId());
                $entry = new Entry();
                $entry->newEntry($this->getEntry(),$entity->getId());
            }
            parent::save();
            Timeline::addTimeline("genre",$this->getId(),"S");
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
*/
    
}
