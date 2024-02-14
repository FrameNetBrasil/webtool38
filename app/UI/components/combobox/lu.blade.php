<div {{ $attributes->merge(['class' => 'form-field']) }}>
    <label for="{{$id}}">{{$label}}</label>
    <input {{$attributes}} id="{{$id}}" name="{{$id}}">
</div>
@push('onload')
    $('#{{$id}}').combobox({
        valueField: 'idLU',
        textField: 'name',
        mode: 'remote',
        method: 'get',
        @if($placeholder != '')
            prompt: '{{$placeholder}}',
        @endif
        @if($pos != '')
            queryParams: {
                pos: '{{$pos}}'
            },
        @endif
        url: "/lus/listForSelect",
    });
@endpush