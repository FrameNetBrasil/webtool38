<?php

class LexemeController extends MController {

    public function gridLemmaData(){
        $model = new fnbr\models\Lexeme();
        $filter = (object)['lexeme' => $this->data->id, 'language' => Manager::getContext()->get(1)];
        ddump($filter);
        $criteria = $model->listForGridLemma($filter);
        return $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function lookupData(){
        if (strlen($this->data->q) < 3) {
            $json = json_encode([]);
        } else {
            $model = new fnbr\models\Lexeme();
            $criteria = $model->listForLookup($this->data->q);
            $json = $model->gridDataAsJSON($criteria);
        }
        return $this->renderJSON($json);
    }


}