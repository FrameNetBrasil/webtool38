<input type="text" {{$attributes}} id="{{$id}}" name="{{$id}}">
@push('onload')
    $('#{{$id}}').textbox({
        icons: [{
            iconCls:'material-icons-outlined wt-input-icon wt-icon-clear',
            handler: function(e){
                $(e.data.target).textbox('clear');
            }
        }],
        prompt: '{{$placeholder}}',
        value: '{{$value}}',
    });
@endpush

