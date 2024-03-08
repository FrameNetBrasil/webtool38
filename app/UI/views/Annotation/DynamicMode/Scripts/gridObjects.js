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
        {
            field: 'delete',
            width: '28px',
            title: '',
            formatter: function (value, row, index) {
                return `<div class="wt-datagrid-action" style="width:24px">
                                    <span
                                        class="action material-icons-outlined wt-datagrid-icon wt-icon-delete cursor-pointer"
                                        title="delete Object"
                                        hx-delete="/annotation/dynamicMode/object/${row.idObjectMM}"
                                    ></span></div>`
            },
        },
        {
            field: 'clone',
            width: '28px',
            title: '',
            formatter: function (value, row, index) {
                return `<div class="wt-datagrid-action" style="width:24px">
                                    <span
                                        class="action material-icons-outlined wt-datagrid-icon wt-icon-clone cursor-pointer"
                                        title="clone Object"
                                    ></span></div>`;
            },
        },
        {
            field: 'order',
            width: '40px',
            title: '#',
            align: 'right',
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
            width: '120px',
            resizable: false,
            formatter: function (value, row, index) {
                return "<span  class='gridPaneFrame'>" + row.startFrame + " [" + row.startTime + "s]" + "</span>";
            },
        },
        {
            field: 'endFrame',
            title: 'End Frame [Time]',
            align: 'right',
            //width: '25%',
            width: '120px',
            resizable: false,
            formatter: function (value, row, index) {
                return "<span  class='gridPaneFrame'>" + row.endFrame + " [" + row.endTime + "s]" + "</span>";
            },
        },
        {
            field: 'frameFe',
            title: 'FrameNet Frame.FE',
            width: '300px',
            formatter: function (value, row, index) {
                return (row.frame !== '') ? "<span  class='gridPaneFrameFE'>" + row.frame + "." + row.fe + "</span>" : '';
            },
        },
        {
            field: 'lu',
            title: 'CV_Name (LU)',
            resizable: false,
            //width: '50%',
            width: '300px',
        },
        {
            field: 'origin',
            title: 'Origin',
            width: '100px',
            formatter: function (value, row, index) {
                if (row.origin === 1) {
                    return "yolo";
                }
                if (row.origin === 2) {
                    return "manual";
                }
            },
        },
        {
            field: 'status',
            width: '32px',
            title: "<span class='material-icons-outlined wt-datagrid-icon wt-icon-annotation-success'></span>",
            formatter: function (value, row, index) {
                if (row.idFE !== "") {
                    return "<span class='material-icons-outlined wt-datagrid-icon wt-icon-annotation-success'></span>";
                } else {
                    return "<span class='material-icons-outlined wt-datagrid-icon wt-icon-annotation-warning'></span>";
                }
            },
        },
        {
            field: 'idObjectMM',
            title: 'id',
            formatter: function (value, row, index) {
                return `<span class="wt-tag">#${row.idObjectMM}</span>`;
            },
        },
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
    ],
    fieldClicked: null,
    selectedObject: null,
    selectRowByObject: (idObject) => {
        if (annotationGridObject.selectedObject) {
            let idObjectPrevious = annotationGridObject.selectedObject;
            annotationGridObject.selectedObject = null;
            $('#gridObjects').datagrid('refreshRow', idObjectPrevious - 1);
        }
        console.log('selectRowByObject', idObject);
        //let rowIndex = $('#gridObjects').datagrid('getRowIndex', idObject);
        annotationGridObject.selectedObject = idObject;
        //console.log('rowIndex = ', rowIndex);
        $('#gridObjects').datagrid('scrollTo', idObject - 1);
        $('#gridObjects').datagrid('refreshRow', idObject - 1);
    }
};

$('#gridObjects').datagrid({
    data: [],
    border: 1,
    width: '100%',
    fit: true,
    idField: 'order',
    showHeader: true,
    singleSelect: false,
    columns: [
        annotationGridObject.columns
    ],
    rowStyler: function (index, row) {
        if (annotationGridObject.selectedObject && (annotationGridObject.selectedObject === row.order)) {
            return 'background-color:#6293BB;color:#fff;'; // return inline style
        }
    },
    onClickRow: function (index, row) {
        let currentVideoState = Alpine.store('doStore').currentVideoState;
        let newObjectState = Alpine.store('doStore').newObjectState;
        if ((currentVideoState === 'paused') && (newObjectState !== 'tracking')) {
            let currentObject = Alpine.store('doStore').currentObject;
            if (currentObject && (currentObject.object.order === row.order)) {
                Alpine.store('doStore').selectObject(null);
            } else {
                if (annotationGridObject.fieldClicked === 'startFrame') {
                    Alpine.store('doStore').selectObjectFrame(row.order, row.startFrame);
                } else if (annotationGridObject.fieldClicked === 'endFrame') {
                    Alpine.store('doStore').selectObjectFrame(row.order, row.endFrame);
                } else if (annotationGridObject.fieldClicked === 'delete') {
                    Alpine.store('doStore').deleteObject(row.idObjectMM);
                } else if (annotationGridObject.fieldClicked === 'clone') {
                    annotation.objects.cloneObject(row.order);
                } else {
                    Alpine.store('doStore').selectObject(row.order);
                }
            }
        }

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
        let currentVideoState = Alpine.store('doStore').currentVideoState;
        if (currentVideoState === 'paused') {
            annotationGridObject.fieldClicked = field;
        }
        // let currentState = that.$store.state.currentState;
        // if (currentState === 'videoPaused') {
        //     that.fieldClicked = field;
        // }
    },
    onBeforeSelect: function () {
        return false;
    },
});
$('#gridObjects').datagrid('loading');
