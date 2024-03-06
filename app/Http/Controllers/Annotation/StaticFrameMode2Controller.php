<?php

namespace App\Http\Controllers\Annotation;

use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Frame;
use App\Repositories\ImageMM;
use App\Repositories\LU;
use App\Repositories\Sentence;
use App\Repositories\StaticSentenceMM;
use App\Services\AnnotationStaticFrameMode2Service;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class StaticFrameMode2Controller extends Controller
{
    #[Get(path: '/annotation/staticFrameMode2')]
    public function browse()
    {
        $search ??= (object)[];
        $search->_token = csrf_token();
        data('search', $search);
        return $this->render('browse');
    }

    #[Post(path: '/annotation/grid/staticFrameMode2')]
    public function grid()
    {
        $this->data->search->_token = csrf_token();
        $response = $this->render("grid");
        $query = [
            'search_corpus' => $this->data->search->corpus,
            'search_document' => $this->data->search->document,
            'search_image' => $this->data->search->image,
        ];
        $response->header('HX-Replace-Url', '/annotation/staticFrameMode2?' . http_build_query($query));
        return $response;
    }

    #[Get(path: '/annotation/staticFrameMode2/listForTree')]
    public function listForTree()
    {
        return AnnotationStaticFrameMode2Service::listForTree();
    }

    private function setData(int $idStaticSentenceMM)
    {
        data('idStaticSentenceMM', $idStaticSentenceMM);
        data('idStaticSentenceMMPrevious',AnnotationStaticFrameMode2Service::getPrevious($idStaticSentenceMM) ?? '');
        data('idStaticSentenceMMNext', AnnotationStaticFrameMode2Service::getNext($idStaticSentenceMM) ?? '');
        $staticSentenceMM = new StaticSentenceMM($idStaticSentenceMM);
        data('document',new Document($staticSentenceMM->idDocument));
        data('sentence', new Sentence($staticSentenceMM->idSentence));
        data('corpus', new Corpus($this->data->document->idCorpus));
        $imageMM = new ImageMM($staticSentenceMM->idImageMM);
        data('imageMM', $imageMM->getData());
        $annotation = AnnotationStaticFrameMode2Service::getObjectsForAnnotationImage($idStaticSentenceMM);
        data('objects', $annotation['objects']);
        data('frames', $annotation['frames']);
        //debug($this->data);
    }

    #[Get(path: '/annotation/staticFrameMode2/sentence/{idStaticSentenceMM}')]
    public function annotationSentence(int $idStaticSentenceMM)
    {
        $this->setData($idStaticSentenceMM);
        return $this->render('annotationSentence');
    }

    #[Get(path: '/annotation/staticFrameMode2/sentence/{idSentenceMM}/object')]
    public function annotationSentenceObject(int $idSentenceMM)
    {
        $this->setData($idSentenceMM);
        return $this->data;
    }

    #[Post(path: '/annotation/staticFrameMode2/fes')]
    public function annotationSentenceFes()
    {
        $this->setData($this->data->idStaticSentenceMM);
//        debug($this->data);
        $idFrame = '';
        if (is_numeric($this->data->idLU)) {
            $lu = new LU($this->data->idLU);
            $idFrame = $lu->idFrame;
        } else if (is_numeric($this->data->idFrame)) {
            $idFrame = $this->data->idFrame;
        }
//        debug($idFrame);
        if ($idFrame != '') {
            if (!AnnotationStaticFrameMode2Service::hasFrame($this->data->idStaticSentenceMM, $idFrame)) {
                data('idFrame', $idFrame);
                $frame = new Frame($idFrame);
                $frames[$idFrame] = [
                    'name' => $frame->name,
                    'idFrame' => $idFrame,
                    'objects' => []
                ];
                data('frames', $frames);
            }
            return $this->render('fes');
        } else {
            return $this->renderNotify("error", "Frame not found!");
        }
    }

    #[Put(path: '/annotation/staticFrameMode2/fes/{idStaticSentenceMM}/{idFrame}')]
    public function annotationSentenceFesSubmit(int $idStaticSentenceMM, int $idFrame)
    {
        try {
            foreach ((array)$this->data->idStaticObjectSentenceMM as $objects) {
                foreach ($objects as $idStaticObjectSentenceMM => $idFrameElement) {
                    if ($idFrameElement == '') {
                        throw new \Exception("FrameElement must be informed.");
                    }
                }
            }
            AnnotationStaticFrameMode2Service::updateObjectSentenceFE($idStaticSentenceMM, $idFrame, (array)$this->data->idStaticObjectSentenceMM);
            return $this->renderNotify("success", "FrameElement updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/staticFrameMode2/fes/{idStaticSentenceMM}/{idFrame}')]
    public function annotationSentenceFesDelete(int $idStaticSentenceMM, int $idFrame)
    {
        try {
            debug($this->data);
            AnnotationStaticFrameMode2Service::deleteAnnotationByFrame($idStaticSentenceMM, $idFrame);
            $this->setData($idStaticSentenceMM);
            $this->renderNotify("success", "Frame deleted.");
            return $this->render('annotationSentence');
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
