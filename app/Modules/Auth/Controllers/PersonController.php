<?php

use App\Repositories\Base;

class PersonController extends MController {

    public function main()
    {
        $this->data->query = Manager::getAppURL('', 'auth/person/gridData');
        return $this->render();
    }
    
    public function lookupData(){
        $model = new fnbr\auth\models\Person();
        $criteria = $model->listForLookup();
        return $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function gridData()
    {
        $model = new fnbr\auth\models\Person();
        $criteria = $model->listByFilter($this->data->filter);
        return $this->renderJSON($model->gridDataAsJSON($criteria));
    }

    public function formObject()
    {
        $model = new fnbr\auth\models\Person($this->data->id);
        $this->data->forUpdate = ($this->data->id != '');
        $this->data->object = $model->getData();
        $this->data->title = $this->data->forUpdate ? $model->getDescription() : _M("new fnbr\models\Person");
        $this->data->save = "@auth/person/save/" . $model->getId() . '|formObject';
        $this->data->delete = "@auth/person/delete/" . $model->getId() . '|formObject';
        return $this->render();
    }

    public function save()
    {
        try {
            $model = new fnbr\auth\models\Person($this->data->id);
            $model->setData($this->data->person);
            $model->save();
            return $this->renderPrompt('information', 'OK');
        } catch (\Exception $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $model = new fnbr\auth\models\Person($this->data->id);
            $model->delete();
            $go = "!$('#formObject_dialog').dialog('close');";
            return $this->renderPrompt('information', _M("Record [%s] removed.", $model->getDescription()), $go);
        } catch (\Exception $e) {
            return $this->renderPrompt('error', _M("Deletion denied."));
        }
    }

}