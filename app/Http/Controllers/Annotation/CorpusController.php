<?php

namespace App\Http\Controllers\Annotation;

use App\Http\Controllers\Controller;
use App\Repositories\Sentence;
use App\Services\AnnotationCorpusService;
use App\Services\AnnotationService;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;
use Illuminate\Http\Request;

#[Middleware(name: 'auth')]
class CorpusController extends Controller
{
    #[Get(path: '/annotation/corpus')]
    public function browse()
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('browse');
    }

    #[Post(path: '/annotation/grid/corpus')]
    public function grid()
    {
        $this->data->search->_token = csrf_token();
        $response = $this->render("grid");
        $query = [
            'search_corpus' => $this->data->search->corpus,
            'search_document' => $this->data->search->document,
        ];
        $response->header('HX-Replace-Url', '/annotation/corpus?' . http_build_query($query));
        return $response;
    }

    #[Post(path: '/annotation/corpus/listForTree')]
    public function listForTree() {
        return AnnotationCorpusService::listForTree();
    }
    #[Get(path: '/annotation/corpus/sentence/{idSentence}')]
    public function annotationSentence(int $idSentence)
    {
        $this->data->idSentence = $idSentence;

        $this->data->sessionTimeout = 300;// Manager::getConf('session.timeout');
        $canSave = true;
        $this->data->canSave = true;//$canSave && Manager::checkAccess('BEGINNER', A_EXECUTE);
        $this->data->isSenior = $this->data->isMaster;//Manager::checkAccess('SENIOR', A_EXECUTE) ? 'true' : 'false';
//        $this->data->rgbColors = AnnotationService::getColor();
//        $this->data->colorsArray = AnnotationService::getColorArray();
        $this->data->layerType = AnnotationService::getLayerType();
        $it = AnnotationService::getInstantiationType();
        $this->data->instantiationType = $it['array'];
        $this->data->instantiationTypeObj = $it['obj'];
        $this->data->idInstantiationType = $it['id'];
        $this->data->coreIcon = config('webtool.fe.icon.grid');
        $this->data->idSentence = $idSentence;
        $sentence = new Sentence($this->data->idSentence);
        $this->data->idLanguage  = AppService::getCurrentIdLanguage();
        $layersData = AnnotationCorpusService::getLayers($this->data);
        $this->data->metadata = $layersData['metadata'];
        $this->data->words = $layersData['words'];
//        $this->data->chars = $layersData['chars'];
        $this->data->annotationSets = $layersData['annotationSets'];
        $this->data->layers = $layersData['layers'];
        $this->data->labelTypes = $layersData['labelTypes'];
        $this->data->layerLabels = $layersData['layerLabels'];
        $this->data->nis = $layersData['nis'];
        $this->data->lus = $layersData['lus'];
        $this->data->layersToShow = [];//MUtil::php2js(fnbr\models\Base::getCurrentUser()->getConfigObject('fnbrLayers'));
        $this->data->columns = $layersData['jsColumns'];
        $this->data->frozenColumns = $layersData['jsFrozenColumns'];
        $this->data->keyEvent = null;

        return $this->render('annotationSentence');
    }

//    #[Get(path: '/annotation/corpus/sentence/{idSentence}/object')]
//    public function annotationSentenceObject(int $idSentence)
//    {
//        $this->data->sessionTimeout = 300;// Manager::getConf('session.timeout');
//        $canSave = true;
//        $this->data->canSave = true;//$canSave && Manager::checkAccess('BEGINNER', A_EXECUTE);
//        $this->data->isSenior = $this->data->isMaster;//Manager::checkAccess('SENIOR', A_EXECUTE) ? 'true' : 'false';
////        $this->data->rgbColors = AnnotationService::getColor();
////        $this->data->colorsArray = AnnotationService::getColorArray();
//        $this->data->layerType = AnnotationService::getLayerType();
//        $it = AnnotationService::getInstantiationType();
//        $this->data->instantiationType = $it['array'];
//        $this->data->instantiationTypeObj = $it['obj'];
//        $this->data->coreIcon = config('webtool.fe.icon.grid');
//        $this->data->idSentence = $idSentence;
//        $sentence = new Sentence($this->data->idSentence);
//        $this->data->idLanguage  = AppService::getCurrentIdLanguage();
//        $layersData = AnnotationCorpusService::getLayers($this->data);
//        $this->data->metadata = $layersData['metadata'];
//        $this->data->words = $layersData['words'];
////        $this->data->chars = $layersData['chars'];
//        $this->data->annotationSets = $layersData['annotationSets'];
//        $this->data->layers = $layersData['layers'];
//        $this->data->labelTypes = $layersData['labelTypes'];
//        $this->data->layerLabels = $layersData['layerLabels'];
//        $this->data->nis = $layersData['nis'];
//        $this->data->lus = (object)[];
//        $this->data->layersToShow = [];//MUtil::php2js(fnbr\models\Base::getCurrentUser()->getConfigObject('fnbrLayers'));
//        $this->data->columns = $layersData['jsColumns'];
//        $this->data->frozenColumns = $layersData['jsFrozenColumns'];
//        $this->data->keyEvent = null;
//        return $this->data;
//    }

    #[Get(path: '/annotation/corpus/sentence/{idSentence}/data')]
    public function annotationSentenceData(int $idSentence)
    {
        $this->data->idSentence = $idSentence;
        return AnnotationCorpusService::getLayersData($this->data);
    }

    #[Put(path: '/annotation/corpus/label')]
    public function saveLabel()
    {
        try {
            AnnotationCorpusService::saveLabel($this->data);
            return $this->notify('success', 'Label updated.');
        } catch (\Exception $e) {
            return $this->notify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/corpus/label')]
    public function deleteLabel()
    {
        try {
            AnnotationCorpusService::deleteLabel($this->data);
            return $this->notify('success', 'Label deleted.');
        } catch (\Exception $e) {
            return $this->notify('error', $e->getMessage());
        }
    }

    #[Put(path: '/annotation/corpus/annotationSet')]
    public function createAnnotationSet()
    {
        try {
            AnnotationCorpusService::createAnnotationSet($this->data);
            return $this->notify('success', 'New AnnotationSet created.');
        } catch (\Exception $e) {
            return $this->notify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/corpus/annotationSet')]
    public function deleteAnnotationSet()
    {
        try {
            AnnotationCorpusService::deleteAnnotationSet($this->data->idAnnotationSet);
            return $this->notify('success', 'AnnotationSet deleted.');
        } catch (\Exception $e) {
            return $this->notify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/corpus/annotationSet/lastFELayer')]
    public function deleteLastFELayer()
    {
        try {
            AnnotationCorpusService::deleteLastFELayer($this->data->idAnnotationSet);
            return $this->notify('success', 'AnnotationSet deleted.');
        } catch (\Exception $e) {
            return $this->notify('error', $e->getMessage());
        }
    }

    #[Put(path: '/annotation/corpus/annotationSet/feLayer')]
    public function addFELayer()
    {
        try {
            AnnotationCorpusService::addFELayer($this->data->idAnnotationSet);
            return $this->notify('success', 'New AnnotationSet created.');
        } catch (\Exception $e) {
            return $this->notify('error', $e->getMessage());
        }
    }

}
