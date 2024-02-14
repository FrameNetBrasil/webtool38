<button type="button" {{ $attributes->merge(['class' => 'btn btn-'.$color]) }}>
    @if($href == '')
    {{$label}}
    @else
        <a href="{{$href}}">{{$label}}</a>
    @endif
</button>