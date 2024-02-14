<?php

class CorpusController extends MController {

    public function lookupData(){
        $model = new fnbr\models\Corpus();
        $criteria = $model->listAll();
        return $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    /*

    public function main() {
        return $this->render("formBase");
    }
    


    public function formFind() {
        $Corpus= new fnbr\models\Corpus($this->data->id);
        $filter->idCorpus = $this->data->idCorpus;
        $this->data->query = $Corpus->listByFilter($filter)->asQuery();
        return $this->render();
    }

    public function formNew() {
        $this->data->action = '@Corpus/save';
        return $this->render();
    }

    public function formObject() {
        $this->data->Corpus = Corpus::create($this->data->id)->getData();
        return $this->render();
    }

    public function formUpdate() {
        $Corpus= new fnbr\models\Corpus($this->data->id);
        $this->data->Corpus = $Corpus->getData();
        
        $this->data->action = '@Corpus/save/' .  $this->data->id;
        return $this->render();
    }

    public function formDelete() {
        $Corpus = new fnbr\models\Corpus($this->data->id);
        $ok = '>Corpus/delete/' . $Corpus->getId();
        $cancelar = '>Corpus/formObject/' . $Corpus->getId();
        return $this->renderPrompt('confirmation', "Confirma remoção do Corpus [{$model->getDescription()}] ?", $ok, $cancelar);
    }

    public function lookup() {
        $model = new fnbr\models\Corpus();
        $filter->idCorpus = $this->data->idCorpus;
        $this->data->query = $model->listByFilter($filter)->asQuery();
        return $this->render();
    }

    public function save() {
            $Corpus = new fnbr\models\Corpus($this->data->Corpus);
            $Corpus->save();
            $go = '>Corpus/formObject/' . $Corpus->getId();
            return $this->renderPrompt('information','OK',$go);
    }

    public function delete() {
            $Corpus = new fnbr\models\Corpus($this->data->id);
            $Corpus->delete();
            $go = '>Corpus/formFind';
            return $this->renderPrompt('information',"Corpus [{$this->data->idCorpus}] removido.", $go);
    }
    */

}