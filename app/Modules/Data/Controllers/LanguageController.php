<?php

class LanguageController extends MController
{

    public function main()
    {
        $this->data->query = Manager::getAppURL('', 'language/gridData');
        return $this->render();
    }

    public function gridData()
    {
        $model = new fnbr\models\Language($this->data->id);
        $criteria = $model->listByFilter($this->data->filter);
        return $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function comboData()
    {
        $model = new fnbr\models\Language($this->data->id);
        $criteria = $model->listForCombo();
        return $this->renderJSON($model->gridDataAsJSON($criteria, true));
    }

}
