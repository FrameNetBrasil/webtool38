<x-layout.index>
    <div class="wt-messager-exception">
        <div class="wt-messager-warning flex flex-row">
            <div class="material-icons wt-messager-icon icon"></div>
            <div class="body flex flex-column">
                <div class="label">Information</div>
                <div>{{$message}}</div>
                <div class="button">
                    <a href="{{$goto}}"><button type="button" class="btn btn-confirm-ok">{{$gotoLabel}}</button></a>
                </div>
            </div>
        </div>
    </div>
</x-layout.index>
