<?php

class ImportController extends MController
{

    private $idLanguage;

    public function init()
    {
        //parent::init();
        $this->idLanguage = Manager::getConf('fnbr.lang');
    }

    public function formImportWSDoc()
    {
        $language = new fnbr\models\Language();
        $this->data->languages = $language->listAll()->asQuery()->chunkResult('idLanguage', 'language');
        $this->data->tags = array('N' => 'Não', 'S' => 'Sim');
        $this->data->action = '@utils/import/importWSDoc';
        return $this->render();
    }

    public function importWSDoc()
    {
        try {
            if ($this->data->idDocument != '') {
                $files = Mutil::parseFiles('uploadFile');
                $model = new fnbr\models\Corpus($this->data->idCorpus);
                if ($this->data->tags == 'N') {
                    $model->uploadSentences($this->data, $files[0]);
                } else {
                    $model->uploadSentencesPenn($this->data, $files[0]);
                }
                return $this->renderPrompt('information', 'Sentences loaded successfully.');
            } else {
                throw new \Exception("No Document");
            }
        } catch (\Exception $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formImportLexWf()
    {
        $language = new fnbr\models\Language();
        $this->data->languages = $language->listForCombo()->asQuery()->chunkResult('idLanguage', 'language');
        $this->data->action = '@utils/import/importLexWf';
        return $this->render();
    }

    public function importLexWf()
    {
        try {
            $files = Mutil::parseFiles('uploadFile');
            $model = new fnbr\models\Lexeme();
            $model->uploadLexemeWordform($this->data, $files[0]);
            return $this->renderPrompt('information', 'Wordforms loaded successfully.');
        } catch (EMException $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function importLexWfOffline()
    {
        try {
            $model = new fnbr\models\Lexeme();
            $model->uploadLexemeWordformOffline($this->data);
            //return $this->renderJSON(json_encode('ok'));
        } catch (EMException $e) {
            return $this->renderJSON(json_encode('error'));
        }
    }

    public function importLUOffline()
    {
        try {
            $model = new fnbr\models\LU();
            print_r($this->data);
            $model->uploadLUOffline($this->data);
        } catch (EMException $e) {
            return $this->renderJSON(json_encode('error'));
        }
    }

    public function formImportFullText()
    {
        $language = new fnbr\models\Language();
        $this->data->languages = $language->listAll()->asQuery()->chunkResult('idLanguage', 'language');
        $this->data->action = '@utils/import/importFullText';
        return $this->render();
    }

    public function importFullText()
    {
        try {
            $files = Mutil::parseFiles('uploadFile');
            $model = new fnbr\models\Document($this->data->idDocument);
            $model->uploadFullText($this->data, $files[0]);
            return $this->renderPrompt('information', 'Fulltext loaded successfully.');
        } catch (EMException $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function importFullTextOffline()
    {
        try {
            $model = new fnbr\models\Document();
            $model->getByEntry($this->data->documentEntry);
            $model->uploadFullText($this->data, $this->data->filename);
            return $this->renderPrompt('information', 'Fulltext loaded successfully.');
        } catch (EMException $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formImportFrames()
    {
        $this->data->action = '@utils/import/importFrames';
        return $this->render();
    }

    public function importFrames()
    {
        try {
            $service = Manager::getAppService('data');
            $files = Mutil::parseFiles('uploadFile');
            $json = file_get_contents($files[0]->getTmpName());
            $service->importFramesFromJSON($json);
            return $this->renderPrompt('information', 'OK');
        } catch (EMException $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formImportMWE()
    {
        $language = new fnbr\models\Language();
        $this->data->languages = $language->listAll()->asQuery()->chunkResult('idLanguage', 'language');
        $this->data->action = '@utils/import/importMWE';
        return $this->render();
    }

    public function importMWE()
    {
        try {
            $files = Mutil::parseFiles('uploadFile');
            $model = new fnbr\models\Lemma();
            $mfile = $model->uploadMWE($this->data, $files[0]);
            return $this->renderFile($mfile);
            //return $this->renderPrompt('information','OK');
        } catch (EMException $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formImportXMLDoc()
    {
        $language = new fnbr\models\Language();
        $this->data->languages = $language->listAll()->asQuery()->chunkResult('idLanguage', 'language');
        $this->data->action = '@utils/import/importXMLDoc';
        return $this->render();
    }

    public function importXMLDoc()
    {
        try {
            $files = Mutil::parseFiles('uploadFile');
            $model = new fnbr\models\Document($this->data->idDocument);
            $model->uploadXML($this->data, $files[0]);
            return $this->renderPrompt('information', 'Fulltext loaded successfully.');
        } catch (EMException $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formImportCxn()
    {
        $this->data->action = '@utils/import/importCxn';
        return $this->render();
    }

    public function importCxn()
    {
        try {
            $service = Manager::getAppService('data');
            $files = Mutil::parseFiles('uploadFile');
            $json = file_get_contents($files[0]->getTmpName());
            $service->importCxnFromJSON($json);
            return $this->renderPrompt('information', 'OK');
        } catch (EMException $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formImportMultimodalText()
    {
        $language = new fnbr\models\Language();
        $this->data->languages = $language->listAll()->asQuery()->chunkResult('idLanguage', 'language');
        $this->data->action = '@utils/import/importMultimodalText';
        return $this->render();
    }

    public function importMultimodalText()
    {
        try {
            $files = Mutil::parseFiles('uploadFile');
            $model = new fnbr\models\Document($this->data->idDocument);
            $model->uploadMultimodalText($this->data, $files[0]);
            return $this->renderPrompt('information', 'Multimodal text loaded successfully.');
        } catch (EMException $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }

    public function formImportMultimodalVideo()
    {
        $language = new fnbr\models\Language();
        $this->data->languages = $language->listAll()->asQuery()->chunkResult('idLanguage', 'language');
        $this->data->action = '@utils/import/importMultimodalVideo';
        return $this->render();
    }

    public function importMultimodalVideo()
    {
        try {
            $files = Mutil::parseFiles('uploadFile');
            $model = new fnbr\models\Document($this->data->idDocument);
            $model->uploadMultimodalVideo($this->data, $files[0]);
            return $this->renderPrompt('information', 'Multimodal video loaded successfully.');
        } catch (EMException $e) {
            return $this->renderPrompt('error', $e->getMessage());
        }
    }


}
