<div class="form-field">
    <label for="{{$id}}">{{$label}}</label>
    <input {{$attributes}} id="{{$id}}" name="{{$id}}">
</div>
@push('onload')
    $('#{{$id}}').combobox({
        valueField: 'idRelationGroup',
        textField: 'name',
        mode: 'remote',
        method: 'GET',
        url: "/relationgroup/listForSelect"
    });
@endpush
