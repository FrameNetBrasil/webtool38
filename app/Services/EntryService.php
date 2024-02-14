<?php

namespace App\Services;

use App\Repositories\Entry;

class EntryService
{
    public static function updateEntries(object $entries)
    {
        $languages = AppService::availableLanguages();
        $entry = new Entry();
        foreach ($languages as $language) {
            $idLanguage = $language['idLanguage'];
            $idEntry = "idEntry_{$idLanguage}";
            $idName = "name_{$idLanguage}";
            $idDescription = "description_{$idLanguage}";
            $data = [
                'name' => $entries->$idName,
                'description' => $entries->$idDescription,
            ];
            $entry->getById($entries->$idEntry);
            $entry->saveData($data);
        }
    }
}