<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use App\Services\UserService;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class UserController extends Controller
{

    #[Get(path: '/users')]
    public function browse()
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('browse');
    }

    #[Post(path: '/users/grid')]
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

    #[Post(path: '/users/listForGrid')]
    public function listForGrid()
    {
        $filter = (object)[
            'login' => $this->data->login ?? false,
            'email' => $this->data->email ?? false,
        ];
        return UserService::listByFilter($filter);
    }

    #[Get(path: '/users/{id}/edit')]
    public function edit(string $id)
    {
        $this->data->user = new User($id);
        return $this->render("edit");
    }

    #[Get(path: '/users/{idUser}/formEdit')]
    public function formEdit(string $idUser)
    {
        $this->data->user = new User($idUser);
        return $this->render("formEdit");
    }

    #[Put(path: '/users/{idUser}')]
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

    #[Get(path: '/users/{idUser}/groups')]
    public function groups(string $idUser)
    {
        $this->data->user = new User($idUser);
        return $this->render("groups");
    }

    #[Get(path: '/users/{idUser}/groups/formNew')]
    public function formNewGroup(string $idUser)
    {
        $this->data->idUser = $idUser;
        return $this->render("Admin.User.Group.formNew");
    }

    #[Get(path: '/users/{idUser}/groups/grid')]
    public function gridGroup(string $idUser)
    {
        $this->data->idUser = $idUser;
        $this->data->groups = UserService::listGroupsForGrid($idUser);
        return $this->render("Admin.User.Group.grid");
    }

    #[Post(path: '/users/{idUser}/group')]
    public function addGroup(string $idUser)
    {
        try {
            $user = new User($idUser);
            $user->addToGroup($this->data->idGroup);
            $this->trigger('reload-gridGroup');
            return $this->renderNotify("success", "User updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/users/{idUser}/groups/{idGroup}')]
    public function deleteGroupn(int $idUser, int $idGroup)
    {
        try {
            $user = new User($idUser);
            $user->deleteFromGroup($idGroup);
            $this->trigger('reload-gridGroup');
            return $this->renderNotify("success", "User updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/users/{id}/delete')]
    public function delete(string $id)
    {
        try {
            $user = new User($id);
            $user->delete();
            $this->data->deleteSuccess = true;
            return $this->browse();
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
