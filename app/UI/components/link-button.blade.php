<a href="{{$href}}" id="{{$id}}" {{$attributes}}>{{$label}}</a>
@push('onload')
    $('#{{$id}}').linkbutton({
        plain: {{$plain}},
        @if($icon != '')
            iconCls:"material-icons-outlined wt-button-icon wt-icon-{{$icon}}"
        @endif
    });
@endpush
