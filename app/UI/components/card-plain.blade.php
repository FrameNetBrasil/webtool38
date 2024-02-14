<div {{ $attributes->merge(['class' => 'wt-card-plain']) }}>
    <div class="header">{!! $title !!}</div>
    <div class="body">
        {{$slot}}
    </div>
</div>