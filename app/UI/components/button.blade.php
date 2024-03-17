@if($href == '')
    <button class="hxBtn hx{{$color}}">
        {{$label}}
    </button>
@else
    <a href="{{$href}}" class="hxBtn hx{{$color}}">>
        {{$label}}
    </a>
@endif
