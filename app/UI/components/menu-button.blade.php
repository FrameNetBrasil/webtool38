<a href="#" id="{{$id}}">{{$label}}</a>
@push('onload')
    $('#{{$id}}').menubutton({
        plain: {{$plain}},
        iconCls:"material-icons-outlined wt-button-icon wt-icon-{{$icon}}",
        menu: "{{$menu}}"
    });
@endpush
