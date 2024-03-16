<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Services\GenreService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;



#[Middleware(name: 'admin')]
class GenreController extends Controller
{

    #[Get(path: '/genre')]
    public function browse()
    {
        data('search', session('searchG'));
        return $this->render('Admin.Genre.browse');
    }

    #[Post(path: '/genre/grid')]
    public function grid()
    {
        $this->data->search->_token = csrf_token();
        return $this->render("grid");;
    }

    #[Post(path: '/genre/listForGrid')]
    public function listForGrid()
    {        
        return GenreService::listGenres();
    }

}