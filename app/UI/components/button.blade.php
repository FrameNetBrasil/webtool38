@if($href == '')
    <button type="button" {{ $attributes->merge(['class' => 'btn btn-'.$color]) }}>
        {{$label}}
    </button>
@else
    <a href="{{$href}}" style="display:inline-block">
        <button type="button" {{ $attributes->merge(['class' => 'btn btn-'.$color]) }}>
            {{$label}}
        </button>
    </a>
@endif
