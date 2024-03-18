<div class="form-field">
    <hx-search-control style="width:{{$width}}">
        <input
            id="{{$id}}"
            name="{{$id}}"
            type="search"
            placeholder="{{$placeholder}}"
        />
        <button
            type="button"
            class="hxClear"
            aria-label="Clear search"
            hidden>
            <hx-icon type="times"></hx-icon>
        </button>
        <hx-search></hx-search>
        <label
            for="{{$id}}">
            {{$label}}
        </label>
    </hx-search-control>
</div>
