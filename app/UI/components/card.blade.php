<div {{ $attributes->merge(['class' => 'wt-card']) }}>
    <div class="header">{!! $title !!}</div>
    <div class="body">
        {{$slot}}
    </div>
</div>