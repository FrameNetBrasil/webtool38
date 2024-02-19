<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Repositories\Domain;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\ViewFrame;
use App\Repositories\ViewFrameElement;
use App\Repositories\ViewLU;
use App\Services\AppService;
use App\Services\EntryService;
use App\Services\FrameService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;
use Illuminate\Support\Facades\Request;
use Orkester\Manager;

#[Middleware(name: 'auth')]
class EntryController extends Controller
{
    #[Put(path: '/entry')]
    public function entry()
    {
        try {
            $languages = AppService::availableLanguages();
            $entry = new Entry();
            foreach ($languages as $language) {
                $idLanguage = $language['idLanguage'];
                $idEntry = "idEntry_{$idLanguage}";
                $idName = "name_{$idLanguage}";
                $idDescription = "description_{$idLanguage}";
                $data = [
                    'name' => $this->data->$idName,
                    'description' => $this->data->$idDescription,
                ];
                $entry->getById($this->data->$idEntry);
                $entry->saveData($data);
            }

            return $this->renderNotify("success", "Translations recorded.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
