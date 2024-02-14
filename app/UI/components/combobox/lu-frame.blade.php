<div class="form-field">
    <label for="{{$id}}">{{$label}}</label>
    <input {{$attributes}} id="{{$id}}" name="{{$id}}">
</div>
@push('onload')
    $('#{{$id}}').combobox({
        valueField: 'idLU',
        textField: 'name',
        editable:false,
        data: {{ Js::from($options) }},
        @if($value != '')
            value: '{{$value}}',
       @endif
    });
@endpush