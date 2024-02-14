<?php

class LUController extends MController {

    public function lookupData(){
        $model = new fnbr\models\LU();
        $filter = (object)[
            'fullname' => $this->data->q
        ];
        $criteria = $model->listForLookup($filter);
        return $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function lookupEquivalent(){
        $model = new fnbr\models\LU();
        $filter = (object)[
            'fullname' => $this->data->q
        ];
        $criteria = $model->listForLookupEquivalent($filter);
        return $this->renderJSON($model->gridDataAsJSON($criteria));
    }

}