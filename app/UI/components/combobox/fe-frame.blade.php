<div {{ $attributes->merge(['class' => 'form-field']) }}>
    <label for="{{$id}}">{{$label}}</label>
    <input {{$attributes}} id="{{$id}}" name="{{$id}}">
</div>
@push('onload')
    $('#{{$id}}').combobox({
        valueField: 'idFrameElement',
        textField: 'name',
        editable:false,
        data: {{ Js::from($options) }},
        @if($value != '')
            value: '{{$value}}',
       @endif
        formatter: function(row){
            return `<span class="${row.icon}"></span><span class="${row.color}">${row.name}</span>`;
        }
    });
@endpush