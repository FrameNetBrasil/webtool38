<script type="text/javascript">
    $(function () {
        let annotationGrid = {
        }

        $('#gridPanel').panel({
            border:false,
            fit:true
        });

        $('#gridTabs').tabs({
            border:true,
        });
    })
</script>

<div id="gridPanel">
<div
    id="gridTabs"
    style="width:100% !important"
>

    <div title="Objects" >
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/annotation/dynamicMode/gridObjects/{{$document->idDocument}}"
        ></div>
    </div>
    <div title="Sentences">
        tab2
    </div>

</div>
</div>
