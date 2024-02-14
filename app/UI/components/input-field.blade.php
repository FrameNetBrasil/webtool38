<input type="text" {{$attributes}} id="{{$id}}" name="{{$id}}">
@push('onload')
    $('#{{$id}}').textbox({
        prompt: '{{$placeholder}}'
    });
    $('#{{$id}}').textbox('setValue','{{$value}}');
@endpush

