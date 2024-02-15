<div>
    @if(isset($data->corpus))
        <div class="grid grid-nogutter">
            <div class="col-8 title">
                <span>Corpus: {{$data->corpus?->name}}</span>
            </div>
            <div class="col-4 text-right description">
                <span>[#{{$data->corpus->idCorpus}}]</span>
                <x-button
                    id="btnDelete"
                    label="Delete"
                    color="danger"
                    onclick="manager.confirmDelete(`Removing corpus {{$data->corpus?->name}}. Confirm?`, '/corpus/{{$data->corpus->idCorpus}}/delete')"
                ></x-button>
            </div>
        </div>
    @else
        <span>Corpus</span>
    @endif
</div>
