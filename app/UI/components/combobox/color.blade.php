<div class="grid">
    <div class="col-8">
        <div class="form-field">
            <label for="{{$id}}">{{$label}}</label>
            <input {{$attributes}} id="{{$id}}" name="{{$id}}">
        </div>
    </div>
    <div class="col-4">
        <div class="form-field">
            <label>Sample</label>
            <div id="{{$id}}Sample"></div>
        </div>
    </div>
</div>
@push('onload')
    $('#{{$id}}').combobox({
        valueField: 'id',
        textField: 'text',
        data: {{ Js::from($options) }},
        editable:false,
        @if($value != '')
        value: '{{$value}}',
        @endif
        formatter: function(row){
            return `<span class="${row.color}">${row.text}</span>`;
        },
        onSelect(row) {
            $('#{{$id}}Sample').html(`<span class="${row.color}">FrameElement</span>`);
        }
    });
@endpush
