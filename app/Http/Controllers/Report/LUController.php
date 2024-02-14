<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Services\FrameService;
use App\Services\ReportFrameService;
use App\Services\ReportLUService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Illuminate\Http\Request;

#[Middleware(name: 'web')]
class LUController extends Controller
{
    #[Get(path: '/report/lus/{idLU}')]
    public function report(int|string $idLU)
    {
        $this->data->report = ReportLUService::report($idLU);
        return $this->render('report');
    }

}
