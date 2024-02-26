<div class="form-field">
    <label for="{{$id}}">{{$label}}</label>
    <div {{$attributes}} id="{{$id}}">
        @foreach($options as $i => $option)
            <div>
                <input type="checkbox" name="{{$id}}_{{$i}}" value="{{$option['value']}}" {{$option['checked']}}><span>{{$option['name']}}</span>
            </div>
        @endforeach
    </div>
</div>
