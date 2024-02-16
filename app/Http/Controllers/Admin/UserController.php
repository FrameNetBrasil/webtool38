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

    #[Get(path: '/user')]
    public function browse()
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('pageBrowse');
    }

    #[Post(path: '/user/grid')]
    public function grid()
    {
        $this->data->search->_token = csrf_token();
        $response = $this->render("slotGrid");
        $query = [
            'search_login' => $this->data->search->login,
            'search_email' => $this->data->search->email,
        ];
        return $response->header('HX-Replace-Url', '/user?' . http_build_query($query));
    }

    #[Post(path: '/user/listForGrid')]
    public function listForGrid()
    {
        $filter = (object)[
            'login' => $this->data->login ?? false,
            'email' => $this->data->email ?? false,
        ];
        return UserService::listForTree($filter);
    }

    #[Get(path: '/user/{id}/edit')]
    public function edit(string $id)
    {
        $this->data->user = new User($id);
        return $this->render("pageEdit");
    }

    #[Get(path: '/user/{id}/formEdit')]
    public function formEdit(string $id)
    {
        $this->data->user = new User($id);
        return $this->render("formEdit");
    }

    #[Put(path: '/user/{id}')]
    public function update(int $id)
    {
        try {
            $user = new User($id);
            $user->saveData($this->data);
            return $this->renderNotify("success", "User updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/user/{id}/groups')]
    public function groups(string $id)
    {
        $this->data->user = new User($id);
        return $this->render("groups");
    }

    #[Get(path: '/user/{id}/groups/formNew')]
    public function formNewGroup(string $id)
    {
        $this->data->idUser = $id;
        return $this->render("Admin.User.Group.formNew");
    }

    #[Get(path: '/user/{id}/groups/grid')]
    public function gridGroup(string $id)
    {
        $this->data->idUser = $id;
        $this->data->groups = UserService::listGroupsForGrid($id);
        return $this->render("Admin.User.Group.grid");
    }

    #[Post(path: '/user/{id}/group')]
    public function addGroup(string $id)
    {
        try {
            $user = new User($id);
            $user->addToGroup($this->data->idGroup);
            $this->trigger('reload-gridGroup');
            return $this->renderNotify("success", "User updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/user/{id}/groups/{idGroup}')]
    public function deleteGroup(int $id, int $idGroup)
    {
        try {
            $user = new User($id);
            $user->deleteFromGroup($idGroup);
            $this->trigger('reload-gridGroup');
            return $this->renderNotify("success", "User updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/user/{id}/delete')]
    public function delete(string $id)
    {
        try {
            $user = new User($id);
            $user->delete();
            return $this->clientRedirect("/user");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/user/{id}/authorize')]
    public function authorized(string $id)
    {
        try {
            $user = new User($id);
            $user->status = 1;
            $user->save();
            return $this->renderNotify("success", "User authorized.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
