<div {{$attributes->class(["panel","panel-htop","wt-container-center-content" => $center])}} >
    <div class="panel-header">
        <div class="panel-title">{{$title}}{!! $extraTitle !!}</div>
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
