<div class="form-field">
    <label for="{{$id}}">{{$label}}</label>
    <input {{$attributes}} id="{{$id}}" name="{{$id}}">
</div>
@push('onload')
    $('#{{$id}}').combotreegrid({
        width:'100%',
        data: {{ Js::from($list) }},
        idField:'idSemanticType',
        treeField:'name',
        columns:[[{
            field:'name',
            title:'Name',
            width:'100%',
        }
        ]],
    });
@endpush
