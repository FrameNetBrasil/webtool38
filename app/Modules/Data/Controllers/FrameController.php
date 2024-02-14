<?php

class FrameController extends MController {

    public function lookupData(){
        $model = new fnbr\models\Frame();
        $criteria = $model->listForLookupName($this->data->q);
        return $this->renderJSON($model->gridDataAsJSON($criteria));
    }

/*
    public function main() {
        return $this->render("formBase");
    }

    public function formFind() {
        $Frame= new fnbr\models\Frame($this->data->id);
        $filter->idFrame = $this->data->idFrame;
        $this->data->query = $Frame->listByFilter($filter)->asQuery();
        return $this->render();
    }

    public function formNew() {
        $this->data->action = '@Frame/save';
        return $this->render();
    }

    public function formObject() {
        $this->data->Frame = Frame::create($this->data->id)->getData();
        return $this->render();
    }

    public function formUpdate() {
        $Frame= new fnbr\models\Frame($this->data->id);
        $this->data->Frame = $Frame->getData();
        
        $this->data->action = '@Frame/save/' .  $this->data->id;
        return $this->render();
    }

    public function formDelete() {
        $Frame = new fnbr\models\Frame($this->data->id);
        $ok = '>Frame/delete/' . $Frame->getId();
        $cancelar = '>Frame/formObject/' . $Frame->getId();
        return $this->renderPrompt('confirmation', "Confirma remoção do Frame [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new fnbr\models\Frame();
        $filter->idFrame = $this->data->idFrame;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        return $this->render();
    }

    public function save() {
            $Frame = new fnbr\models\Frame($this->data->Frame);
            $Frame->save();
            $go = '>Frame/formObject/' . $Frame->getId();
            return $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Frame = new fnbr\models\Frame($this->data->id);
            $Frame->delete();
            $go = '>Frame/formFind';
            return $this->renderPrompt('information',"Frame [{$this->data->idFrame}] removido.", $go);
    }
*/
}