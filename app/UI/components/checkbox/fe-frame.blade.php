<div class="form-field">
    <label for="{{$id}}">{{$label}}</label>
    <div {{$attributes}} id="{{$id}}">
        @foreach($options as $i => $option)
            <div>
                <input type="checkbox" name="{{$id}}_{{$i}}" value="{{$option['value']}}"><span style="padding-top:7px" class="{{$option['icon']}}"></span><span class="{{$option['color']}}">{{$option['name']}}</span>
            </div>
        @endforeach
    </div>
</div>
