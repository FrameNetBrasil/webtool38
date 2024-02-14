<table id="tt">
</table>
<script>
    $(function () {
        $('#tt').treegrid({
            fit: true,
{{--            data: {{ Js::from($data->frames) }},--}}
            url: "/api/frame/listForTree",
            queryParams: {
                frame: "{{$data->frame}}",
            },
            showHeader: false,
            rownumbers: false,
            idField: 'id',
            treeField: 'text',
            border:false,
            columns:[[
                {field:'text'}
            ]],
            onClickRow:(row) => {
                console.log(row)
                window.location.href = "/structure/frame/edit/" + row.id;
            }

        });
    });
</script>
<style>
    .datagrid-header {
        background-color: transparent;
        border: 0;
    }
    .datagrid-header td {
        border:0;
    }
    .datagrid-header-row, .datagrid-row {
    }
    .definition {
        display: inline-block;
        font-size: 12px;
    }
    .fe-name {
        display: inline-block;
        font-size: 12px;
    }
    .datagrid-body table tbody tr td div.datagrid-cell {
        height:40px !important;
        padding-top: 4px;
    }
</style>