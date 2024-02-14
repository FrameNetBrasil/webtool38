<div>
    @if(isset($data->frame))
    <span>Frame: {{$data->frame?->name}}</span>
    @else
    <span>Frames</span>
    @endif
</div>
