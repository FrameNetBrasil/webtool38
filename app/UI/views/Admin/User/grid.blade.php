<table id="userGridTable">
</table>
<script>
    $(function () {
        $('#userGridTable').treegrid({
            fit: true,
            url: "/users/listForGrid",
            queryParams: {{ Js::from($data->search) }},
            showHeader: false,
            rownumbers: false,
            idField: 'idUser',
            treeField: 'login',
            border: false,
            columns: [[
                {
                    field: 'login',
                    formatter: (value, rowData) => {
                        return `<div><div class='color-user'>${value}</div></div>`;
                    }
                },
                {
                    field: 'email',
                },
                {
                    field: 'status',
                    formatter: (value, rowData) => {
                        return (value === '1') ? 'Ok' : 'Pending';
                    }
                },
                {
                    field: 'lastLogin',
                },
            ]],
            onClickRow: (row) => {
                window.location.href = `/users/${row.idUser}/edit`;
            },
        });
    });
</script>
<style>
    .datagrid-body table tbody tr td div.datagrid-cell {
        height: 24px !important;
        padding-top: 4px;
    }
</style>
