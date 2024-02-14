<?php

class TypeInstanceController extends MController
{
    public function lookupCoreType()
    {
        $model = new fnbr\models\TypeInstance();
        $result = $model->listCoreType()->asQuery()->getResult(\FETCH_ASSOC);
        return $this->renderJSON($model->gridDataAsJSON($result));
    }

    public function lookupStatusType()
    {
        $model = new fnbr\models\TypeInstance();
        $result = $model->listStatusType()->asQuery()->getResult(\FETCH_ASSOC);
        ddump($result);
        return $this->renderJSON($model->gridDataAsJSON($result));
    }

    public function lookupBFF()
    {
        $model = new fnbr\models\TypeInstance();
        $result = $model->listBFF()->asQuery()->getResult(\FETCH_ASSOC);
        return $this->renderJSON($model->gridDataAsJSON($result));
    }

    public function lookupQualiaType()
    {
        $model = new fnbr\models\TypeInstance();
        $result = $model->listQualiaType()->asQuery()->getResult(\FETCH_ASSOC);
        return $this->renderJSON($model->gridDataAsJSON($result));
    }

    public function lookupConstraintType()
    {
        $model = new fnbr\models\TypeInstance();
        $result = $model->listConstraintType()->asQuery()->getResult(\FETCH_ASSOC);
        return $this->renderJSON($model->gridDataAsJSON($result));
    }

    public function lookupConceptType()
    {
        $model = new fnbr\models\TypeInstance();
        $result = $model->listConceptType()->asQuery()->getResult(\FETCH_ASSOC);
        return $this->renderJSON($model->gridDataAsJSON($result));
    }

}