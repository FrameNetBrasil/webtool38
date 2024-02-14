<div class="form-field">
    <label for="{{$id}}">{{$label}}</label>
    <select {{$attributes}} id="{{$id}}" name="{{$id}}">
    </select>
</div>
@push('onload')
    $('#{{$id}}').combobox({
    valueField: 'id',
    textField: 'name',
    editable:false,
    data: {{ Js::from($options) }},
    @if($value != '')
        value: '{{$value}}'
    @endif
    });
@endpush