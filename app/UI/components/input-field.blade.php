<input type="text" {{$attributes}} id="{{$id}}" name="{{$id}}">
@push('onload')
    $('#{{$id}}').textbox({
        prompt: '{{$placeholder}}',
        value: '{{$value}}',
    });
@endpush

