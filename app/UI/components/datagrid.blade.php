<div {{$attributes->class(["wt-form","wt-container-center-content" => $center])}} >
    <div class="form-header">
        <div class="form-title">{{$title}}{!! $extraTitle !!}</div>
    </div>
    <div class="wt-datagrid"  style="max-height:{{$height}}">
        <table>
            {{$header}}
            <tbody>
            {{$slot}}
            </tbody>
        </table>
    </div>
</div>
