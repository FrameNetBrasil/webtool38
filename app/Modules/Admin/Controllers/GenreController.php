<?php





class GenreController extends MController
{
    public function main()
    {
        $this->data->query = Manager::getAppURL('', 'admin/genre/gridData');
        return $this->render();
    }
    
    public function gridData()
    {
        $model = new fnbr\models\Genre();
        $criteria = $model->listByFilter($this->data->filter);
        return $this->renderJSON($model->gridDataAsJSON($criteria));
    }
    
    public function formObject()
    {
        $model = new fnbr\models\Genre($this->data->id);
        $this->data->forUpdate = ($this->data->id != '');
        $this->data->object = $model->getData();
        $this->data->title = $this->data->forUpdate ? $model->getDescription() : _M("new fnbr\models\Genre");
        $this->data->save = "@admin/genre/save/" . $model->getId() . '|formObject';
        $this->data->delete = "@admin/genre/delete/" . $model->getId() . '|formObject';
        return $this->render();
    }

    public function save()
    {
        try {
            $model = new fnbr\models\Genre();
            $this->data->genre->entry = 'gen_' . $this->data->genre->entry;
            $model->setData($this->data->genre);
            $model->save();
            return $this->renderPrompt('information', 'OK', "editEntry('{$this->data->genre->entry}');");
        } catch (\Exception $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }
    
}
