<div {{$attributes->class(["wt-form","wt-container-center-content" => $center])}} >
    <div class="form-header">
        <div class="form-title">{{$title}}</div>
    </div>
    <form id="{{$id}}" name="{{$id}}" {{$attributes}}>
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
