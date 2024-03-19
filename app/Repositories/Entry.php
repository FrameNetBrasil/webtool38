<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class Entry extends Repository
{

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*, language.language')->orderBy('entry');
        if (isset($filter->idEntry)) {
            $criteria->where("idEntry = {$filter->idEntry}");
        }
        if (isset($filter->entry)) {
            $criteria->where("entry LIKE '{$filter->entry}%'");
        }
        if (isset($filter->entries)) {
            $criteria->where("entry", "IN", $filter->entries);
        }
        if (isset($filter->idLanguage)) {
            $criteria->where("idLanguage = {$filter->idLanguage}");
        }
        if (isset($filter->idEntity)) {
            $criteria->where("idEntity = {$filter->idEntity}");
        }
        return $criteria;
    }

    public static function listByIdEntity(int $idEntity): array
    {
        $result = static::getCriteria()
            ->select('*')
            ->where('idEntity', '=', $idEntity)
            ->get()
            ->keyBy('idLanguage')
            ->toArray();
        return $result;
    }

    public function listForExport($entry)
    {
        $criteria = $this->getCriteria()->select('*, language.language')->orderBy('entry');
        $criteria->where("entry = '{$entry}'");
        return $criteria;
    }

    public function listForUpdate($filter)
    {
        $criteria = $this->getCriteria()->select("idEntry, entry, name, concat(substr(description,1,50),'...') as shortDescription, language.language");
        if ($filter->entry) {
            $criteria->where("entry = '{$filter->entry}'");
        }
        $criteria->orderBy("language.language");
        return $criteria;
    }

    public function getUndefinedLanguages($entry)
    {
        $criteria = $this->getCriteria()->select("idLanguage");
        $criteria->where("entry = '{$entry}'");
        $language = new Language();
        $languages = $language->getCriteria()->select("idLanguage, language")
            ->where('idLanguage', 'not in', $criteria)
            ->asQuery()->chunkResult('idLanguage', 'language');
        return $languages;
    }

    public function newEntry($entry, $idEntity, $name = null)
    {
        $languages = Base::languages();
        foreach ($languages as $idLanguage => $language) {
            $this->setPersistent(false);
            $this->setEntry($entry);
            $this->setName($name ?: $entry);
            $this->setDescription($name ?: $entry);
            $this->setNick($name ?: $entry);
            $this->setIdLanguage($idLanguage);
            $this->setIdEntity($idEntity);
            $this->save();
            Timeline::addTimeline("entry", $this->getId(), "S");
        }
    }

    public function updateIdEntity($idEntity)
    {
        if ($this->isPersistent()) {
            $this->setIdEntity($idEntity);
            $this->save();
            Timeline::addTimeline("entry", $this->getId(), "S");
        }
    }

    public function newEntryByData($data)
    {
        $languages = Base::languages();
        foreach ($languages as $idLanguage => $language) {
            $this->setPersistent(false);
            $this->setEntry($data->entry);
            $this->setName($data->name);
            $this->setDescription($data->description ?: $data->name);
            $this->setNick($data->nick ?: $data->name);
            $this->setIdLanguage($idLanguage);
            $this->setIdEntity($data->idEntity);
            $this->save();
            Timeline::addTimeline("entry", $this->getId(), "S");
        }
    }

    public function updateEntry($oldEntry, $newEntry, $name = '')
    {
        $criteria = $this->getUpdateCriteria();
        $criteria->where("entry = '{$oldEntry}'");
        if ($name != '') {
            $criteria->addColumnAttribute('entry');
            $criteria->addColumnAttribute('name');
            $criteria->update([$newEntry, $name]);
        } else {
            $criteria->addColumnAttribute('entry');
            $criteria->update($newEntry);
        }
    }

    public function deleteEntry($entry)
    {
        $criteria = $this->getDeleteCriteria();
        $criteria->addColumnAttribute('entry');
        $criteria->where("entry = '{$entry}'");
        $criteria->delete();
    }

    public function deleteByIdEntity($idEntity): void
    {
        $criteria = $this->getCriteria();
        $criteria->select('idEntity');
        $criteria->where("idEntity = {$idEntity}");
        $criteria->delete();
    }

    public function cloneEntry($sourceEntry, $targetEntry)
    {
        $criteria = $this->getCriteria()->select("idEntry, name, description, nick, idLanguage");
        $criteria->where("entry = '{$sourceEntry}'");
        $criteria->asQuery()->each(function ($row) use ($targetEntry) {
            $entry = new Entry();
            $entry->setEntry($targetEntry);
            $entry->setName($row['name']);
            $entry->setDescription($row['description']);
            $entry->setNick($row['nick']);
            $entry->setIdLanguage($row['idLanguage']);
            $entry->setIdEntity($row['idEntity']);
            $entry->save();
            Timeline::addTimeline("entry", $entry->getId(), "S");
        });
    }

    public function create($entry, $name, $idEntity)
    {
        // get idLanguage
        $languages = Language::listAll();
        foreach ($languages as $language) {
            $this->setPersistent(false);
            $this->entry = $entry;
            $this->name = $name;
            $this->description = $name;
            $this->nick = $name;
            $this->idLanguage = $language['idLanguage'];
            $this->idEntity = $idEntity;
            $this->save();
            Timeline::addTimeline("entry", $this->getId(), "S");
        }
    }

    public function createFromData($entry)
    {
        // get idLanguage
        $idLanguage = Base::getIdLanguage($entry->language);
        if ($idLanguage != '') {
            $this->setPersistent(false);
            $this->setEntry($entry->entry);
            $this->setName($entry->name);
            $this->setDescription($entry->description);
            $this->setNick($entry->nick);
            $this->setIdLanguage($idLanguage);
            $this->setIdEntity($entry->idEntity);
            $this->save();
            Timeline::addTimeline("entry", $this->getId(), "S");
        }
    }

    public function addLanguage($entry, $idLanguage)
    {
        $this->setPersistent(false);
        $this->setEntry($entry);
        $this->setName($entry);
        $this->setDescription($entry);
        $this->setNick($entry);
        $this->setIdLanguage($idLanguage);
        $this->setIdEntity($entry->idEntity);
        $this->save();
        Timeline::addTimeline("entry", $this->getId(), "S");
    }

    public function updateByIdEntity($idEntity, $idLanguage, $name)
    {
        $criteria = $this->getCriteria()
            ->select("*")
            ->where('idEntity', '=', $idEntity)
            ->where('idLanguage', '=', $idLanguage);
        $this->retrieveFromCriteria($criteria);
        $this->setPersistent(true);
        $this->setName($name);
        $this->setDescription($name);
        $this->setNick($name);
        $this->save();
        Timeline::addTimeline("entry", $this->getId(), "S");
    }


}

