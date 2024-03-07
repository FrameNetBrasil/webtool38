<?php

namespace App\Services;

use App\Repositories\AnnotationSet;
use App\Repositories\Base;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\DynamicSentenceMM;
use App\Repositories\Label;
use App\Repositories\LayerType;
use App\Repositories\Sentence;
use App\Repositories\UserAnnotation;
use App\Repositories\ViewAnnotationSet;
use App\Repositories\WordForm;
use Orkester\Manager;


class AnnotationDynamicService
{

    public static function listSentencesByDocument($idDocument)
    {
        $result = [];
        $dynamicSentenceMM = new DynamicSentenceMM();
        $sentences = $dynamicSentenceMM->listByDocument($idDocument);
        $as = new ViewAnnotationSet();
        foreach ($sentences as $sentence) {
            $targets = $as->listTargetBySentence($sentence['idSentence']);
            $sentence['decorated'] = self::decorateSentence($sentence['text'], $targets);
            $result[] = $sentence;
        }
        debug($result);
        return $result;
    }


    public static function decorateSentence($sentence, $labels)
    {
        $decorated = "";
        $ni = "";
        $i = 0;
        foreach ($labels as $label) {
            //$style = 'background-color:#' . $label['rgbBg'] . ';color:#' . $label['rgbFg'] . ';';
            if ($label['startChar'] >= 0) {
                $decorated .= mb_substr($sentence, $i, $label['startChar'] - $i);
                //$decorated .= "<span style='{$style}'>" . mb_substr($sentence, $label['startChar'], $label['endChar'] - $label['startChar'] + 1) . "</span>";
                $decorated .= "<span class='color_target'>" . mb_substr($sentence, $label['startChar'], $label['endChar'] - $label['startChar'] + 1) . "</span>";
                $i = $label['endChar'] + 1;
            } else { // null instantiation
                $ni .= "<span class='color_target'>" . $label['instantiationType'] . "</span> " . $decorated;
            }
        }
        $decorated = $ni . $decorated . mb_substr($sentence, $i);
        return $decorated;
    }

}
