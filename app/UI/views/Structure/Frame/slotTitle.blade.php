<div>
    @if(isset($data->frame))
    <span>Frame: {{$data->frame?->name}}</span>
        <div class="description">{{$data->frame?->description}}</div>
    @else
    <span>Frames</span>
    @endif
</div>
