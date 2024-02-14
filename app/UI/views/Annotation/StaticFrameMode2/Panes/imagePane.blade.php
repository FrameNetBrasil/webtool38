@php
    $imageURL = "http://flickr30k.frame.net.br/flickr30k-images/" . $data->imageMM->name;
    $imageWidth = $data->imageMM->width;
    $imageHeight = $data->imageMM->height;

@endphp
    <div style="display:flex; flex-direction: column; width:auto">
        <div id="image" style="width: {{$imageWidth}}px;height: {{$imageHeight}}px;">
            <img src="{{$imageURL}}" width="{{$imageWidth}}" height="{{$imageHeight}}">
        </div>
    </div>

