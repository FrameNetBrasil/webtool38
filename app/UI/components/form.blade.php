<div {{$attributes->class(["panel","panel-htop","wt-container-center-content" => $center])}} >
    <div class="panel-header">
        <div class="panel-title">{{$title}}</div>
    </div>
    <form id="{{$id}}" name="{{$id}}" {{$attributes}} class="wt-form">
        <div class="form-toolbar">
            {{$toolbar}}
        </div>
        <div id="{{$id}}" class="form-fields">
            {{$fields}}
        </div>
        <div class="form-buttons">
            {{$buttons}}
        </div>
    </form>
</div>
