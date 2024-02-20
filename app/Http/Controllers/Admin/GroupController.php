<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User;
use App\Services\GroupService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use App\Services\UserService;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'admin')]
class GroupController extends Controller
{

    #[Get(path: '/group')]
    public function browse()
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('browse');
    }

    #[Post(path: '/group/grid')]
    public function grid()
    {
        $this->data->search->_token = csrf_token();
        $response = $this->render("grid");
        $query = [
            'search_login' => $this->data->search->login,
            'search_email' => $this->data->search->email,
        ];
        return $response->header('HX-Replace-Url', '/user?' . http_build_query($query));
    }

    #[Post(path: '/group/listForGrid')]
    public function listForGrid()
    {
        $filter = (object)[
            'login' => $this->data->login ?? false,
            'email' => $this->data->email ?? false,
        ];
        return UserService::listByFilter($filter);
    }

    #[Get(path: '/group/listForSelect')]
    public function listForSelect()
    {
        return GroupService::listForSelect();
    }
    #[Get(path: '/group/{id}/edit')]
    public function edit(string $id)
    {
        $this->data->user = new User($id);
        return $this->render("edit");
    }

    #[Get(path: '/group/{id}/formEdit')]
    public function formEdit(string $idUser)
    {
        $this->data->user = new User($idUser);
        return $this->render("formEdit");
    }

    #[Put(path: '/group/{id}')]
    public function update(int $idUser)
    {
        try {
            $user = new User($idUser);
            $user->saveData($this->data);
            return $this->renderNotify("success", "User updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/group/{id}/groups')]
    public function groups(string $idUser)
    {
        $this->data->user = new User($idUser);
        return $this->render("groups");
    }

    #[Get(path: '/group/{id}/groups/formNew')]
    public function formNewGroup(string $idUser)
    {
        $this->data->idUser = $idUser;
        return $this->render("Admin.User.Group.formNew");
    }

    #[Get(path: '/group/{id}/groups/grid')]
    public function gridGroup(string $idUser)
    {
        $this->data->idUser = $idUser;
        $this->data->groups = UserService::listGroupsForGrid($idUser);
        return $this->render("Admin.User.Group.grid");
    }
}
