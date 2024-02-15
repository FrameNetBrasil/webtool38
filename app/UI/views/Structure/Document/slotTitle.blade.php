<div>
    @if(isset($data->document))
        <div class="grid grid-nogutter">
            <div class="col-8 title">
                <span>Document: {{$data->document?->name}}</span>
            </div>
            <div class="col-4 text-right description">
                [
                #{{$data->document->idDocument}}
                ]
            </div>
        </div>
    @endif
</div>
