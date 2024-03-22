<div {{$attributes->class(["wt-form","wt-container-center-content" => $center,"wt-form-noborder" => !$border])}} >
    <form id="{{$id}}" name="{{$id}}" {{$attributes}}>

        <div class="form-header">
            @if(isset($header))
                {{ $header }}
            @else
                <div class="form-title">{{$title}}</div>
            @endif
        </div>
        <div class="form-toolbar">
            {{$toolbar}}
        </div>
        <div id="{{$id}}_fields" class="form-fields">
            {{$fields}}
        </div>
        <div class="form-buttons">
            {{$buttons}}
        </div>
    </form>
</div>

