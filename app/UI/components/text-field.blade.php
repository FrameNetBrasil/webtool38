<div class="form-field">
    <label for="{{$id}}">{{$label}}</label>
    <input type="text" {{$attributes}} id="{{$id}}" name="{{$id}}">
</div>
@push('onload')
    $('#{{$id}}').textbox({});
    $('#{{$id}}').textbox('setValue','{{$value}}');
@endpush
