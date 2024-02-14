<x-link-button id="menuEdit" label="Edit" hx-get="/users/{{$data->user->idUser}}/formEdit" hx-target="#usersPane"></x-link-button>
<x-link-button id="menuGroups" label="Groups" hx-get="/users/{{$data->user->idUser}}/groups" hx-target="#usersPane"></x-link-button>
<x-link-button id="menuAuthorize" label="Authorize" hx-get="/users/{{$data->user->idUser}}/authorize" hx-target="#usersPane"></x-link-button>
