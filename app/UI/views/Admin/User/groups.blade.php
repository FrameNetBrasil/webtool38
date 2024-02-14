<x-layout.content>
    <div class="grid">
        <div class="col">
            <div hx-trigger="load" hx-target="this"  hx-swap="outerHTML" hx-get="/users/{{$data->user->idUser}}/groups/formNew"></div>
        </div>
        <div class="col">
            <div hx-trigger="load" hx-target="this"  hx-swap="outerHTML" hx-get="/users/{{$data->user->idUser}}/groups/grid"></div>
        </div>
    </div>
    <div id="usersGroupPane">
    </div>
</x-layout.content>
