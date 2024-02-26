<div class="form-field">
    <label for="{{$id}}">{{$label}}</label>
    <input {{$attributes}} id="{{$id}}" name="{{$id}}">
</div>
@push('onload')
    $('#{{$id}}').combobox({
        valueField: 'idQualia',
        textField: 'name',
        mode: 'remote',
        method: 'get',
        url: "/qualia/listRelationsForSelect"
    });
@endpush
