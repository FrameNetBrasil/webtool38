<?php

class LemmaController extends MController
{

    private $idLanguage;

    public function init()
    {
        parent::init();
        $this->idLanguage = Manager::getSession()->idLanguage;
    }

    public function main()
    {
        $this->data->isMaster = Manager::checkAccess('MASTER', A_EXECUTE) ? 'true' : 'false';
        $this->data->isAnno = 'false';
        return $this->render();
    }

    public function lemmaTree()
    {
        $structure = Manager::getAppService('structurelemma');
        if ($this->data->id == '') {
            $children = $structure->listLemmas($this->data, $this->idLanguage);
            $data = (object)[
                'id' => 'root',
                'state' => 'open',
                'iconCls' => 'icon-blank fa fa-sitemap fa16px entity_lemma',
                'text' => 'Lemmas',
                'children' => $children
            ];
            $json = json_encode([$data]);
        } elseif ($this->data->id{0} == 'l') {
            $json = $structure->listLemmaLexeme(substr($this->data->id, 1), $this->idLanguage);
        }
        return $this->renderJson($json);
    }

    public function formNewLemma()
    {
        $this->data->idLanguage = $this->idLanguage;
        $this->data->save = "@structure/lemma/newLemma|formNewLemma";
        $this->data->close = "!$('#formNewLemma_dialog').dialog('close');";
        return $this->render();
    }

    public function newLemma()
    {
        try {
            $model = new fnbr\models\Lemma();
            $model->saveData($this->data->lemma);
            return $this->renderPrompt('ok', 'Lemma created.',"!$('#formNewLemma_dialog').dialog('close');");
        } catch (\Exception $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function updateLemma()
    {
        try {
            $model = new fnbr\models\Lemma($this->data->id);
            $model->updateEntity();
            return $this->renderPrompt('ok', 'Lemma updated.',"!$('#formNewLemma_dialog').dialog('close');");
        } catch (\Exception $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formDeleteLemma()
    {
        $ok = "^structure/lemma/deleteLemma/" . $this->data->id;
        return $this->renderPrompt('confirmation', 'Atenção: O Lemma será removido! Continua?', $ok);
    }

    public function deleteLemma()
    {
        try {
            $structure = Manager::getAppService('structurelemma');
            $structure->deleteLemma($this->data->id);
            return $this->renderPrompt('information', 'OK', "!structure.reloadRoot();");
        } catch (\Exception $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }

    }

    public function formNewLexeme()
    {
        $this->data->idLemma = $this->data->id;
        $model = new fnbr\models\Lemma($this->data->idLemma);
        $this->data->name = $model->getName();
        $this->data->language = $model->getLanguage()->getLanguage();
        $this->data->save = "@structure/lemma/newLexeme|formNewLexeme";
        $this->data->close = "!$('#formNewLexeme_dialog').dialog('close');";
        $this->data->title = _M('new Lexeme');
        return $this->render();
    }

    public function newLexeme()
    {
        try {
            if (trim($this->data->lexeme->lexemeOrder) == '') {
                throw new \Exception("Order is required.");
            }
            $structure = Manager::getAppService('structurelemma');
            $structure->addLexemeEntry($this->data);
            //$model->save($this->data->lemmaelement);
            return $this->renderPrompt('information', 'OK', "!$('#formNewLexeme_dialog').dialog('close');");
        } catch (\Exception $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formDeleteLexeme()
    {
        $ok = "^structure/lemma/deleteLexeme/" . $this->data->id;
        return $this->renderPrompt('confirmation', 'Warning: Lexeme will be removed from Lemma! Continue?', $ok);
    }

    public function deleteLexeme()
    {
        try {
            $model = new fnbr\models\LexemeEntry($this->data->id);
            $model->delete();
            return $this->renderPrompt('information', 'Lexeme removed.', "!structure.reloadParent();");
        } catch (\Exception $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formNewWordform()
    {
        $this->data->idLemma = $this->data->id;
        $model = new fnbr\models\Lemma($this->data->idLemma);
        $this->data->name = $model->getName();
        $this->data->language = $model->getLanguage()->getLanguage();
        $this->data->save = "@structure/lemma/newWordform|formNewWordform";
        $this->data->close = "!$('#formNewWordform_dialog').dialog('close');";
        $this->data->title = _M('New Wordform');
        return $this->render();
    }

    public function newWordform()
    {
        try {
            if (trim($this->data->wordform->lexemeOrder) == '') {
                throw new \Exception("Order is required.");
            }
            $structure = Manager::getAppService('structurelemma');
            $structure->addLexemeEntryWordform($this->data);
            return $this->renderPrompt('information', 'OK', "!$('#formNewWordform_dialog').dialog('close');");
        } catch (\Exception $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formDeleteWordform()
    {
        $ok = "^structure/lemma/deleteWordform/" . $this->data->id;
        return $this->renderPrompt('confirmation', 'Warning: Wordform will be removed from Lemma! Continue?', $ok);
    }

    public function deleteWordform()
    {
        try {
            $model = new fnbr\models\LexemeEntry($this->data->id);
            $model->delete();
            return $this->renderPrompt('information', 'Wordform removed.', "!structure.reloadParent();");
        } catch (\Exception $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }


}
