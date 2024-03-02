let annotationGridObject = {
    columns: [
        // {
        //     field: 'chkObject',
        //     checkbox: true,
        // },
        // {
        //     field: 'hidden',
        //     width: 24,
        //     title: '<i class="fas fa-eye"></i>',
        //     formatter: function (value, row, index) {
        //         if (value) {
        //             return "<i class='material-outlined wt-icon-hide' style='cursor:pointer'></i>";
        //         } else {
        //             return "<i class='material-outlined wt-icon-show' style='cursor:pointer'></i>";
        //         }
        //     },
        // },
        // {
        //     field: 'idObjectClone',
        //     width: 24,
        //     title: '<i class="faTool material wt-icon-clone"></i>',
        //     formatter: function (value, row, index) {
        //         return "<i class='material-outlined wt-icon-clone' style='cursor:pointer'></i>";
        //     },
        // },
        // {
        //     field: 'idObject',
        //     title: '#',
        //     align: 'right',
        //     width: 56,
        // },
        {
            field: 'idFrame',
            title: 'idFrame',
            hidden: true,
        },
        {
            field: 'idFE',
            title: 'idFE',
            hidden: true,
        },
        // {
        //     field: 'tag',
        //     width: 24,
        //     title: '<i class="fas fa-tag"></i>',
        //     formatter: function (value, row, index) {
        //         return "<i style='color:" + row.color + "' class='fas fa-tag'></i>";
        //     },
        // },
        {
            field: 'startFrame',
            title: 'Start Frame [Time]',
            align: 'right',
            sortable: true,
            //width: '25%',
            width: '150px',
            resizable:false,
            formatter: function (value, row, index) {
                return "<span  class='gridPaneFrame'>" + row.startFrame + " [" + row.startTime + "s]" + "</span>";
            },
        },
        {
            field: 'endFrame',
            title: 'End Frame [Time]',
            align: 'right',
            //width: '25%',
            width: '150px',
            resizable:false,
            formatter: function (value, row, index) {
                return "<span  class='gridPaneFrame'>" + row.endFrame + " [" + row.endTime + "s]" + "</span>";
            },
        },
        // {
        //     field: 'frameFe',
        //     title: 'FrameNet Frame.FE',
        //     width: 185,
        //     formatter: function (value, row, index) {
        //         return (row.frame !== '') ? "<span  class='gridPaneFrameFE'>" + row.frame + "." + row.fe + "</span>" : '';
        //     },
        // },
        {
            field: 'lu',
            title: 'CV_Name (LU)',
            resizable:false,
            //width: '50%',
            width: '300px',
        },
        // {
        //     field: 'origin',
        //     title: 'Origin',
        //     width: 100,
        //     formatter: function (value, row, index) {
        //         if (row.origin === '1') {
        //             return "yolo";
        //         }
        //         if (row.origin === '2') {
        //             return "manual";
        //         }
        //     },
        // },
        // {
        //     field: 'status',
        //     width: 24,
        //     title: "<i class='material-outlined wt-icon-status'></i>",
        //     formatter: function (value, row, index) {
        //         if (row.idFE !== "") {
        //             return "<i style='color:green' class='fas fa-check'></i>";
        //         } else {
        //             return "<i style='color:gold' class='fas fa-exclamation-triangle'></i>";
        //         }
        //     },
        // },
        // {
        //     field: 'idDynamicObjectMM',
        //     title: 'idObjectMM',
        //     sortable: true,
        // },
    ],
    toolbar: [
        {
            text: 'Hide All',
            iconCls: 'faTool material-outlined wt-icon-hide',
            handler: function () {
                // var rows = $('#gridObjects').datagrid('getRows');
                // $.each(rows, function (index, row) {
                //     that.$store.dispatch('hideObject', row.idObject);
                //     row.hidden = true;
                //     $('#gridObjects').datagrid('refreshRow', index);
                // });
            }
        },
        {
            text: 'Show All',
            iconCls: 'faTool material-outlined wt-icon-show',
            handler: function () {
                // var rows = $('#gridObjects').datagrid('getRows');
                // $.each(rows, function (index, row) {
                //     that.$store.dispatch('showObject', row.idObject);
                //     row.hidden = false;
                //     $('#gridObjects').datagrid('refreshRow', index);
                // });
            }
        },
        {
            text: 'Delete checked',
            iconCls: 'faTool material wt-icon-delete',
//            handler: async function () {
                // var toDelete = [];
                // var checked = $('#gridObjects').datagrid('getChecked');
                // $.each(checked, function (index, row) {
                //     toDelete.push(row.idObjectMM);
                // });
                // await dynamicAPI.deleteObjects(toDelete);
                // annotationVideoModel.currentIdObjectMM = -1;
                // that.$store.commit('updateGridPane', true)
                // that.$store.commit('currentObject', null)
                // that.$store.commit('currentState', 'videoPaused')
                // $.messager.alert('Ok', 'Objects deleted.', 'info');
  //          }
        },
    ]
};

document.addEventListener('doObjects:ready', () => {
    console.log(window.annotation.objects);
    $('#gridObjects').datagrid({
        data: window.annotation.objects,
        border: 1,
        width: '100%',
        // height: 544,
        fit: true,
        idField: 'idObjectMM',
        //title: 'Objects',
        showHeader: true,
        singleSelect: false,
        //toolbar: annotationGridObject.toolbar,
        columns: [
            annotationGridObject.columns
        ],
        rowStyler: function (index, row) {
            console.log(row);
            // let currentObject = that.$store.state.currentObject;
            // if (currentObject && (row)) {
            //     if (currentObject.idObject === row.idObject) {
            //         return 'background-color:#6293BB;color:#fff;'; // return inline style
            //     }
            // }
        },
        onClickRow: function (index, row) {
            // let currentState = that.$store.state.currentState;
            // if (currentState === 'videoPaused') {
            //     if (that.fieldClicked === 'locked') {
            //         that.$store.dispatch('lockObject', row.idObject);
            //     } else if (that.fieldClicked === 'hidden') {
            //         if (row.hidden) {
            //             that.$store.dispatch('showObject', row.idObject);
            //             row.hidden = false;
            //         } else {
            //             that.$store.dispatch('hideObject', row.idObject);
            //             row.hidden = true;
            //         }
            //         $('#gridObjects').datagrid('refreshRow', index);
            //         that.$store.commit('redrawFrame', true);
            //     } else if (that.fieldClicked === 'idObjectClone') {
            //         that.duplicateObjects(that, [row.idObject])
            //     } else if (that.fieldClicked === 'start') {
            //         that.$store.commit('currentFrame', row.startFrame);
            //         that.$store.dispatch('selectObject', row.idObject);
            //     } else if (that.fieldClicked === 'end') {
            //         that.$store.commit('currentFrame', row.endFrame);
            //         that.$store.dispatch('selectObject', row.idObject);
            //     } else {
            //         that.$store.commit('currentFrame', row.startFrame);
            //         that.$store.dispatch('selectObject', row.idObject);
            //     }
            // }
        },
        onClickCell: function (index, field, value) {
            // let currentState = that.$store.state.currentState;
            // if (currentState === 'videoPaused') {
            //     that.fieldClicked = field;
            // }
        },
        onBeforeSelect: function () {
            return false;
        },
    });
});
