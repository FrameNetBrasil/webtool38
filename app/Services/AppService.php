<?php

namespace App\Services;

use App\Repositories\Base;

class AppService
{
    public static function getCurrentLanguage()
    {
        return session('currentLanguage');
    }

    public static function setCurrentLanguage(int $idLanguage)
    {
        $languages = Base::languagesDescription();
        $data = $languages[$idLanguage][0];
        $data['idLanguage'] = $idLanguage;
        session(['currentLanguage' => $data]);
    }

    public static function getCurrentIdLanguage()
    {

        return session('currentLanguage')['idLanguage'] ?? session('idLanguage');
    }

    public static function availableLanguages()
    {
        $data = [];
        $languages = config('webtool.user')[3]['language'][3];
        foreach ($languages as $l => $language) {
            $data[] = [
                'idLanguage' => $l,
                'description' => $language[0]
            ];
        }
        return $data;
    }

}
