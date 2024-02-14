<?php

class GenreController extends MController
{

    public function lookupData($rowsOnly)
    {
        $model = new fnbr\models\Genre();
        $criteria = $model->listAll();
        return $this->renderJSON($model->gridDataAsJSON($criteria, $rowsOnly));
    }


}