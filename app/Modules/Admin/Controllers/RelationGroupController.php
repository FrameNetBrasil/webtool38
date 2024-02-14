<?php





class RelationGroupController extends MController
{
    public function main()
    {
        $this->data->query = Manager::getAppURL('', 'admin/relationgroup/gridData');
        return $this->render();
    }
    
    public function gridData()
    {
        $model = new fnbr\models\RelationGroup();
        $criteria = $model->listByFilter($this->data->filter);
        return $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function formObject()
    {
        $model = new fnbr\models\RelationGroup($this->data->id);
        $this->data->forUpdate = ($this->data->id != '');
        $this->data->object = $model->getData();
        $this->data->title = $this->data->forUpdate ? $model->getDescription() : _M("new fnbr\models\Relation Group");
        $this->data->save = "@admin/relationgroup/save/" . $model->getId() . '|formObject';
        $this->data->delete = "@admin/relationgroup/delete/" . $model->getId() . '|formObject';
        return $this->render();
    }

    public function save()
    {
        try {
            $model = new fnbr\models\RelationGroup();
            $this->data->relationgroup->entry = 'rgp_' . $this->data->relationgroup->entry;
            $model->setData($this->data->relationgroup);
            $model->save();
            return $this->renderPrompt('information', 'OK', "editEntry('{$this->data->relationgroup->entry}');");
        } catch (\Exception $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }
    
}
