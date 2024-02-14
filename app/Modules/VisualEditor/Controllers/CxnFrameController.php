<?php





class CxnFrameController extends MController
{

    private $idLanguage;

    public function init()
    {
        Manager::checkLogin(false);
        $this->idLanguage = Manager::getConf('fnbr.lang');
        $msgDir = Manager::getAppPath('conf/report');
        Manager::$msg->file = 'messages.' . $this->idLanguage . '.php';
        Manager::$msg->addMessages($msgDir);
    }

    public function main()
    {
        $this->data->isMaster = Manager::checkAccess('MASTER', A_EXECUTE) ? 'true' : 'false';
        return $this->render();
    }

    public function frameTree()
    {
        $editor = Manager::getAppService('visualeditor');
        if ($this->data->id == '') {
            $children = $editor->listFrames($this->data, $this->idLanguage);
            $data = (object)[
                'id' => 'root',
                'state' => 'open',
                'text' => 'Frames',
                'children' => $children
            ];
            $json = json_encode([$data]);
        }
        return $this->renderJson($json);
    }
    
    public function cxnTree()
    {
        $editor = Manager::getAppService('visualeditor');
        if ($this->data->id == '') {
            $children = $editor->listCxns($this->data, $this->idLanguage);
            $data = (object)[
                'id' => 'root',
                'state' => 'open',
                'text' => 'Constructions',
                'children' => $children
            ];
            $json = json_encode([$data]);
        }
        return $this->renderJson($json);
    }

    public function workingArea() {
        $this->data->isMaster = Manager::checkAccess('MASTER', A_EXECUTE);
        $editor = Manager::getAppService('visualeditor');
        $this->data->entities = $editor->getCxnFrames();
        $this->data->relationEntry = $editor->getCxnFrameRelationEntry();
        return $this->render();
    }
    
    public function getCxnFrameRelations(){
        $editor = Manager::getAppService('visualeditor');
        $this->data->relations = $editor->getCxnFrameRelations($this->data->id);
        return $this->renderJSON($this->data->relations);
    }

    public function getCE(){
        $editor = Manager::getAppService('visualeditor');
        $this->data->ces = $editor->getCEs($this->data->id);
        return $this->renderJSON($this->data->ces);
    }

    public function getFE(){
        $editor = Manager::getAppService('visualeditor');
        $this->data->fes = $editor->getFEs($this->data->id);
        return $this->renderJSON($this->data->fes);
    }

    public function getCEFERelations(){
        $editor = Manager::getAppService('visualeditor');
        $this->data->relations = $editor->getCEFERelations($this->data->idEntity1,$this->data->idEntity2,$this->data->idType);
        return $this->renderJSON($this->data->relations);
    }
    
    public function test() {
        return $this->render();
    }

    public function saveCxnFrameRelation(){
        $isMaster = Manager::checkAccess('MASTER', A_EXECUTE);
        $editor = Manager::getAppService('visualeditor');
        if ($isMaster) {
            $editor->updateCxnFrameRelation($this->data->graphJson);
            $editor->deleteCxnFrameRelation($this->data->linksRemoved);
            return $this->renderPrompt('info','Ok');
        } else {
            return $this->renderPrompt('error','Error');
        }
    }

    public function saveCEFERelation(){
        $isMaster = Manager::checkAccess('MASTER', A_EXECUTE);
        $editor = Manager::getAppService('visualeditor');
        if ($isMaster) {
            $editor->updateCEFERelation($this->data->graphcefeJson);
            $editor->deleteCEFERelation($this->data->linkscefeRemoved);
            return $this->renderPrompt('info','Ok');
        } else {
            return $this->renderPrompt('error','Error');
        }
    }

    public function saveFECoreRelation(){
        return $this->renderPrompt('info','Ok');
    }
    
}
