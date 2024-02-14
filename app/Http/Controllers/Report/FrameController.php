<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Services\FrameService;
use App\Services\ReportFrameService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Illuminate\Http\Request;

#[Middleware(name: 'web')]
class FrameController extends Controller
{
    #[Post(path: '/report/grid/frames')]
    public function grid()
    {
        $this->data->search->_token = csrf_token();
        $response = $this->render("grid");
        $query = [
            'search_frame' => $this->data->search->frame,
            'search_fe' => $this->data->search->fe,
            'search_lu' => $this->data->search->lu,
        ];
        $response->header('HX-Replace-Url', '/report/frames?' . http_build_query($query));
        return $response;
    }

    #[Get(path: '/report/frames')]
    public function browse(int $idFrame = null)
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('browse');
    }

    #[Post(path: '/report/frames/listForTree')]
    public function listForTree() {
        return FrameService::listForTree();
    }

    #[Get(path: '/report/frames/listForSelect')]
    public function listForSelect()
    {
        return FrameService::listForSelect();
    }

    #[Get(path: '/report/frames/{idFrame}/{lang?}')]
    public function report(int|string $idFrame, string $lang = '')
    {
        $this->data->report = ReportFrameService::report($idFrame, $lang);
        return $this->render('report');
    }

}
