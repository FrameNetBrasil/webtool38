<div>
    @if(isset($data->user))
        <div class="grid grid-nogutter">
            <div class="col-8 title">
                <span>User: {{$data->user?->email}}</span>
            </div>
            <div class="col-4 text-right description">
                <span>[#{{$data->user->idUser}}]</span>
                <x-button
                    id="btnAuthorize"
                    label="Authorize"
                    color="secondary"
                    hx-post="/users/{{$data->user->idUser}}/authorize"
                ></x-button>
                <x-button
                    id="btnDelete"
                    label="Delete"
                    color="danger"
                    onclick="manager.confirmDelete(`Removing user {{$data->user->email}}. Confirm?`, '/users/{{$data->user->idUser}}/delete')"
                ></x-button>
            </div>
        </div>
    @else
        <span>User</span>
    @endif
</div>
