<table id="framesGridTable" >
</table>
<script>
    $(function () {
        $('#framesGridTable').treegrid({
            fit: true,
            url: "/report/frames/listForTree",
            queryParams: {{ Js::from($data->search) }},
            showHeader: false,
            rownumbers: false,
            idField: 'id',
            treeField: 'name',
            border: false,
            columns: [[
                {
                    field: 'name',
                    width: '100%',
                    formatter: (value, rowData) => {
                        if (rowData.type === 'frame') {
                            return `<div><div class='color-frame'>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === 'fe') {
                            return `<div>
<div>
<span class='fe-name color_${rowData.idColor}'>${value[0]}</span>
</div>
<div class='definition'>${value[1]}</div>
</div>`;
                        }
                        if (rowData.type === 'feFrame') {
                            return `<div>
<div>
<span class='fe-name color_${rowData.idColor}'>${value[0]}</span>
<i class='material-icons-outlined wt-icon wt-icon-frame'></i><span>${value[2]}</span>
</div>
<div class='definition'>${value[1]}</div>
</div>`;
                        }
                        if (rowData.type === 'lu') {
                            return `<div><div class='color-lu'>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === 'luFrame') {
                            return `<div>
<div>
<span class='color_lu'>${value[0]}</span>
<i class='material-icons-outlined wt-icon wt-icon-frame'></i><span>${value[2]}</span>
</div>
<div class='definition'>${value[1]}</div>
</div>`;
                        }
                    }
                },
            ]],
            onClickRow: (row) => {
                if ((row.type === 'frame') || (row.type === 'feFrame')) {
                    let idFrame = row.id.substring(1);
                    window.location.href = `/report/frames/${idFrame}`;
                }
                if (row.type === 'luFrame') {
                    let idLU = row.id.substring(1);
                    window.location.href = `/report/lus/${idLU}`;
                }
            },
        });
    });
</script>
<style>
    .definition {
        display: inline-block;
        font-size: 12px;
    }

    .fe-name {
        display: inline-block;
        font-size: 12px;
    }

    .datagrid-body table tbody tr td div.datagrid-cell {
        height: 40px !important;
        padding-top: 4px;
    }
</style>