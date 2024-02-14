<div class="form-field">
    <label for="{{$id}}">Relation</label>
    <input {{$attributes}} id="{{$id}}" name="{{$id}}">
</div>
@push('onload')
    $('#{{$id}}').combobox({
        valueField: 'value',
        textField: 'name',
        editable:false,
        formatter: function(row){
            return `<span class="color_${row.entry}">${row.name}</span>`;
        },
        data: {{ Js::from($options) }},
    });
@endpush