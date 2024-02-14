<div>
    <div class="flex justify-content-between flex-wrap">
        <div>
            @if(isset($data->user))
                <span>User: {{$data->user?->email}}</span>
            @else
                <span>Users</span>
            @endif
        </div>
        <div class="text-right">
            @if(isset($data->user))
            <x-button id="btnAuthorize" label="Authorize" color="secondary" hx-post="/users/{{$data->user->idUser}}/authorize"></x-button>
            <x-button id="btnDelete" label="Delete" color="danger" hx-delete="/users/{{$data->user->idUser}}/delete" hx-swap-oob="body"></x-button>
            @endif
        </div>
    </div>
</div>
