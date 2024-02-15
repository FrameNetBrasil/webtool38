<div>
    @if(isset($data->frame))
        <div class="grid grid-nogutter">
            <div class="col-8 title">
                <span>Frame: {{$data->frame?->name}}</span>
            </div>
            <div class="col-4 text-right description">
                @foreach ($data->classification as $name => $values)
                    [
                    @foreach ($values as $value)
                        {{$value}}
                    @endforeach
                    ]
                @endforeach
            </div>
        </div>
        <div class="description">{{$data->frame?->description}}</div>
    @else
    <span>Frames</span>
    @endif
</div>
