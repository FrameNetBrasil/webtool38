<div>
    @if(isset($data->lemma))
        <div class="grid grid-nogutter">
            <div class="col-8 title">
                <span>Lemma: {{$data->lemma?->name}}</span>
            </div>
            <div class="col-4 text-right description">
                <span>[#{{$data->lemma->idLemma}}]</span>
                <x-button
                    id="btnDelete"
                    label="Delete"
                    color="danger"
                    onclick="manager.confirmDelete(`Removing lemma '{{$data->lemma?->name}}'. Confirm?`, '/lemma/{{$data->lemma->idLemma}}/delete')"
                ></x-button>
            </div>
        </div>
    @else
    <span>Lexicon</span>
    @endif
</div>
