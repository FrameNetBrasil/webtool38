@if($placeholder == '')
    <div class="form-field">
        <label for="{{$id}}">{{$label}}</label>
        <input {{$attributes}} id="{{$id}}" name="{{$id}}">
    </div>
@else
    <input {{$attributes}} id="{{$id}}" name="{{$id}}">
@endif
@push('onload')
    $('#{{$id}}').combobox({
        data: {{ Js::from($options) }},
        editable:false,
        prompt: '{{$placeholder}}',
        valueField: 'idSemanticType',
        textField: 'name',
    });
@endpush
