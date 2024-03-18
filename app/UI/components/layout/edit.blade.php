<div class="edit">
    {{$edit}}
</div>
<div class="nav">
    {{$nav}}
</div>
<div id="layoutEditMain" class="main">
    {{$main}}
</div>
<script>
    $(function () {
        $('#layoutEditMain').panel({
            fit: true,
            border: false,
        })
    })
</script>
