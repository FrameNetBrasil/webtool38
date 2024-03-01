<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;



#[Middleware(name: 'admin')]
class GenreController extends Controller
{

    #[Get(path: '/genre')]
    public function browse()
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('browse');
    }


}