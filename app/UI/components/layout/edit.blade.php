<div class="flex-none">
    <div class="edit">
        {{$edit}}
    </div>
</div>
<div class="flex-none">
    <div class="nav">
        {{$nav}}
    </div>
</div>
<section class="flex-grow-1">
    <div id="layoutEditMain" class="main">
        {{$main}}
    </div>
</section>
<script>
    $(function () {
        $('#layoutEditMain').panel({
            fit: true,
            border:false,
        })
    })
</script>
