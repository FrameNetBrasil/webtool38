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
        limitToList: true,
        editable: false,
        required: true,
        url: "/relationgroup/listForSelect"
    });
@endpush
