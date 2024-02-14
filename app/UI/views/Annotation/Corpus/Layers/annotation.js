let UDTree = {
    element: 'UDTreeCanvas',
    UDTreeCurrent: null
};

// $(function () {
// Alpine.start();
//

// window.ky.get(`/annotation/corpus/sentence/${idSentence}/object`, {}).json().then((data) => {
window.annotation = {
    data: annotationData,
    startChar: 0,
    endChar: 0,
    $dg: null,
    selection: {
        rowIndex: -1,
        startChar: -1,
        endChar: -1,
        status: 0
    },
    setSelection(rowIndex, startChar, endChar, status) {
        console.log(rowIndex, startChar, endChar, status);
        console.log('current Selection', this.selection);
        // if (this.selection.rowIndex !== -1) {
        //     annotation.markSelection(this.selection, 0);
        // }
        if ((this.selection.rowIndex === -1) || (this.selection.rowIndex !== rowIndex)) {
            this.selection = {
                rowIndex: rowIndex,
                startChar: startChar,
                endChar: endChar,
                status: status
            };
        } else {
            if ((startChar >= this.selection.startChar) && (endChar <= this.selection.endChar)) {
                // same selection
            } else {
                if (endChar < this.selection.startChar) {
                    this.selection.startChar = startChar;
                    this.selection.endChar = endChar;
                    this.selection.status = status;
                } else {
                    if (this.selection.status === 2) {
                        // new selection
                        this.selection = {
                            rowIndex: rowIndex,
                            startChar: startChar,
                            endChar: endChar,
                            status: status
                        };
                    } else {
                        //if status != 2, check if it is possible to extend the current selection
                        let field;
                        let canExtend = true;
                        let row = annotation.getRow(rowIndex);
                        for (var i = this.selection.startChar; i <= endChar; i++) {
                            field = 'c' + i;
                            if (row[field].status > 1) {
                                canExtend = false;
                            }
                        }
                        if (canExtend) {
                            this.selection.endChar = endChar;
                            this.selection.status = status;
                        } else {
                            manager.messager('error', "Selection can not be extended.");
                        }
                    }
                }
            }
        }
        console.log('new Selection', this.selection);
        annotation.markSelection(this.selection);
    },
    refresh: () => {
        window.location.reload();
    },
    labelHelp: () => {
        window.open("/annotation/corpus/labelhelp", "_blank");
    },
    manageLayers: () => {

    },
    getAnnotationByLayer: (idAnnotationSet, layerTypeEntry) => {
        var labels = {};
        var rows = $('#dataGridLayers').datagrid("getRows");
        var fields = $('#dataGridLayers').datagrid('getColumnFields');
        console.log(fields);
        jQuery.each(rows, function (index, row) {
            if (row.idAnnotationSet === idAnnotationSet) {
                if (row.layerTypeEntry === layerTypeEntry) {
                    jQuery.each(fields, function (f, field) {
                        if (field.startsWith('wf')) {
                            if (typeof row[field] !== 'undefined') {
                                labels[row[field]] = 1;
                            }
                        }
                    });
                }
            }
        });
        return labels;
    },
    getRow: (index) => {
        let rows = annotation.$dg.datagrid("getRows");
        return rows[index];
    },
    //
    // Formatter
    //
    styleSelected: (jq) => {
        jq.css('background-color', '#CCC').css('color', 'black');
    },
    rowStyler: (index, row) => {
//            console.log('refreshing row ' + index);
        if (!row.show) {
            return 'display:none';
        }
        if (row.idLayerType === 0) {
            return {class: 'layerTarget'};
        } else {
            return {class: 'layerSelected'};
        }
    },
    cellActionsFormatter: (value, row, index) => {
        var text = "";
        if (row.idLayerType === 0) {
            text = text + `<div class="material-icons wt-icon-comment wt-anno-icon" onClick="annotation.ASComments(${row.idAnnotationSet})"></div>`;
            text = text + `<div class="material-icons wt-icon-delete wt-anno-icon" onClick="annotation.onDeleteAS(${row.idAnnotationSet})"></div>`;
            text = text + `<div class="material-icons wt-icon-collapse wt-anno-icon" onClick="annotation.collapseAS(${row.idAnnotationSet})"></div>`;
        }
        if (row.layerTypeEntry === 'lty_fe') {
            if (!row.extraFELayer) {
                text = text + `<span class="material-icons-outlined wt-anno-icon wt-icon-fe-ni" onClick="annotation.onNullInstantiationFE(${index})"></span>`;
                text = text + `<div class="material-icons wt-icon-add wt-anno-icon" onClick="annotation.onAddFELayer(${row.idAnnotationSet})"></div>`;
            } else {
                text = text + `<div class="material-icons wt-icon-delete wt-anno-icon" onClick="annotation.onDeleteLastFELayer(${row.idAnnotationSet})"></div>`;
            }
        }
        return text;
    },
    cellFormatter: (value, row, index) => {
        if ((row.layerTypeEntry === 'lty_target') || (row.layerTypeEntry === 'lty_all_targets')) {
            return value.char;
        } else {
            if ((value.idLabelType !== 0) || (value.status !== 0)) {
                return value.char;
            }
        }
    },
    cellLayerFormatter: (value, row, index) => {
        var text = value;
        if (row.layerTypeEntry === 'lty_target') {
            text = annotation.data.annotationSets[row.idAnnotationSet].name;
        }
        if (row.layerTypeEntry === 'lty_all_targets') {
            text = 'Targets';
        }
        if (row.layerTypeEntry === 'lty_fe') {
            var textNis = "";
            var nis = annotation.data.nis[row.idLayer];
            if (nis) {
                var i = 0;
                jQuery.each(nis, function (idLabel, ni) {
                    var classColor = "color_" + ni.idColor;
                    textNis = textNis + `<span class="easyui-tooltip divNI ${classColor}" title="${ni.fe}">${ni.label}</span>`;
                    i++;
                });
                var width = i * 30;
                text = text + "<span style='width:" + width + "px'>" + textNis + "</span>";
            }
        }
        if (row.layerTypeEntry === 'lty_udrelation') {
            text = text + "&nbsp<a class='fa fa-sitemap fa16px' onclick='annotation.UDTree(" + index + ")'>&nbsp</a>";
        }
        return text;
    },

    cellStyler: (value, row, index) => {
        if (value.idLabelType !== 0) {
            if (row.layerTypeEntry === 'lty_fe') {
                if (annotation.data.annotationSets[row.idAnnotationSet].annotatedFEs[value.idLabelType] === undefined) {
                    annotation.data.annotationSets[row.idAnnotationSet].annotatedFEs[value.idLabelType] = value.idLabelType;
                    //$(window).trigger( "addx", 2 );
                    // Alpine.$(window).trigger( "addx", 2 );
                    window.dispatchEvent(new CustomEvent('add-fe-annotated-' + row.idAnnotationSet, {
                        detail: value.idLabelType
                    }));
                }
            }
            let idColor = annotation.data.labelTypes[value.idLabelType].idColor;
            return {class: 'color_' + idColor};
        } else {
            if (row.layerTypeEntry === 'lty_target') {
                return {class: 'layerTarget'};
            } else if (row.layerTypeEntry === 'lty_all_targets') {
                let cssClass = 'layerAllTargets';
                if (value.status === 2) {
                    cssClass = cssClass + ' color_17';
                } else if (value.status === 1) {
                    return {class: 'cellSelected'};
                } else {
                    let column = annotation.$dg.datagrid('getColumnOption', 'c' + value.order);
                    if (column.hasLU) {
                        cssClass = cssClass + ' hasLU';
                    }
                }
                return {class: cssClass};
            } else if (value.status === 1) {
                return {class: 'cellSelected'};
            }
            return null;
        }
    },
    headerStyler: (title, col) => {
        let style = '';
        if (col.hasLU) {
            style = {class: 'headerLU'};
        }
        return style;
    },
    //
    // Selection
    //
    onSelect: (rowIndex, rowData) => {
    },

    onClickCell: (rowIndex, field, value) => {
        console.log(rowIndex, field, value);
        if (field === 'actions') {
            return;
        }
        let row = annotation.getRow(rowIndex);
        let column = annotation.$dg.datagrid('getColumnOption', field);
        if (value.status === 0) { // empty
            if (value.char === ' ') {
                return;
            }
            if (row.layerTypeEntry === 'lty_gf') {
                return;
            }
            if (row.layerTypeEntry === 'lty_pt') {
                return;
            }
            let word = annotation.data.words[column.word];
            let startChar = word.startChar;
            let endChar = word.endChar;
            annotation.setSelection(rowIndex, startChar, endChar, 1);
        } else if ((value.status === 1) || (value.status === 2)) { // selected
            if ((value.status === 2) && (row.layerTypeEntry === 'lty_all_targets')) {
                return;
            }
            // update the selection to the current click and open menu
            let startChar, endChar;
            let currentStatus = value.status;
            let start = column.index;
            while ((start >= annotation.startChar) && (row['c' + start].status === currentStatus)) {
                startChar = start;
                --start;
            }
            let end = column.index;
            while ((end <= annotation.endChar) && (row['c' + end].status === currentStatus)) {
                endChar = end;
                ++end;
            }
            annotation.setSelection(rowIndex, startChar, endChar, currentStatus);
            // show menu
            let position = $("tr[datagrid-row-index|='" + rowIndex + "'] > td[field=" + field + "]").offset();
            console.log(row);
            let div = '#menu_' + row.layerTypeEntry;
            if (row.layerTypeEntry === 'lty_fe') {
                div = div + '_' + row.idAnnotationSet;
            }
            if (row.layerTypeEntry === 'lty_all_targets') {
                console.log('lus', annotation.data.lus[column.word]);
                div = div + '_' + column.word;
            }
            console.log('div = ', div);
            $(div).menu('show', {
                left: position.left + 5,
                top: position.top + 5
            });
        }
    },
    markSelection: (selection) => {
        let row = annotation.getRow(selection.rowIndex);
        let field;
        for (var i = selection.startChar; i <= selection.endChar; i++) {
            field = 'c' + i;
            row[field].status = selection.status;
        }
        annotation.$dg.datagrid('beginEdit', selection.rowIndex);
        annotation.$dg.datagrid('updateRow', {
            index: selection.rowIndex,
            row: row
        });
        annotation.$dg.datagrid('endEdit', selection.rowIndex);
    },
    onLabelClick: (idLabelType) => {
        console.log(idLabelType, annotation.selection);
        let newStatus = 2;
        if (idLabelType === 0) { // clear
            newStatus = 0;
        }
        let row = annotation.getRow(annotation.selection.rowIndex);
        let field;
        for (var i = annotation.selection.startChar; i <= annotation.selection.endChar; i++) {
            field = 'c' + i;
            row[field].idLabelType = idLabelType;
            row[field].status = newStatus;
        }
        annotation.$dg.datagrid('beginEdit', annotation.selection.rowIndex);
        annotation.$dg.datagrid('updateRow', {
            index: annotation.selection.rowIndex,
            row: row
        });
        annotation.$dg.datagrid('endEdit', annotation.selection.rowIndex);
        if (idLabelType === 0) { // clear
            if (annotation.selection.status === 2) {
                window.ky.delete(`/annotation/corpus/label`, {
                    json: {
                        _token: csrf,
                        idLayer: row.idLayer,
                        startChar: annotation.selection.startChar,
                    }
                }).json().then((data) => {
                    if (data.notify.type === 'error') {
                        manager.messager('error', data.notify.message);
                    }
                });
            }
        } else {
            window.ky.put(`/annotation/corpus/label`, {
                json: {
                    _token: csrf,
                    idLayer: row.idLayer,
                    idLabelType: idLabelType,
                    idInstantiationType: annotation.data.idInstantiationType.Normal,
                    startChar: annotation.selection.startChar,
                    endChar: annotation.selection.endChar,
                }
            }).json().then((data) => {
                if (data.notify.type === 'error') {
                    manager.messager('error', data.notify.message);
                }
            });
        }
        annotation.selection = {
            rowIndex: -1,
            startChar: -1,
            endChar: -1,
            status: 0
        };
        let div = '#menu_' + row.layerTypeEntry;
        if (row.layerTypeEntry === 'lty_fe') {
            div = div + '_' + row.idAnnotationSet;
        }
        $(div).menu('hide');
    },
    onLUClick: (idLU) => {
        console.log(idLU, annotation.selection);
        if (idLU === 0) { // clear
            if (annotation.selection.status === 1) {
                let row = annotation.getRow(annotation.selection.rowIndex);
                let field;
                for (var i = annotation.selection.startChar; i <= annotation.selection.endChar; i++) {
                    field = 'c' + i;
                    row[field].idLabelType = 0;
                    row[field].status = 0;
                }
                annotation.$dg.datagrid('beginEdit', annotation.selection.rowIndex);
                annotation.$dg.datagrid('updateRow', {
                    index: annotation.selection.rowIndex,
                    row: row
                });
                annotation.$dg.datagrid('endEdit', annotation.selection.rowIndex);
                annotation.selection = {
                    rowIndex: -1,
                    startChar: -1,
                    endChar: -1,
                    status: 0
                };
            }
        } else {
            window.ky.put(`/annotation/corpus/annotationSet`, {
                json: {
                    _token: csrf,
                    idLU: idLU,
                    idSentence: annotation.data.idSentence,
                    startChar: annotation.selection.startChar,
                    endChar: annotation.selection.endChar,
                }
            }).json().then((data) => {
                if (data.notify.type === 'error') {
                    manager.messager('error', data.notify.message);
                } else {
                    window.location = "/annotation/corpus/sentence/" + annotation.data.idSentence;
                }
            });
        }
    },
    onDeleteAS: (idAnnotationSet) => {
        window.ky.delete(`/annotation/corpus/annotationSet`, {
            json: {
                _token: csrf,
                idAnnotationSet: idAnnotationSet,
            }
        }).json().then((data) => {
            if (data.notify.type === 'error') {
                manager.messager('error', data.notify.message);
            } else {
                window.location = "/annotation/corpus/sentence/" + annotation.data.idSentence;
            }
        });
    },
    onAddFELayer: (idAnnotationSet) => {
        window.ky.put(`/annotation/corpus/annotationSet/feLayer`, {
            json: {
                _token: csrf,
                idAnnotationSet: idAnnotationSet,
            }
        }).json().then((data) => {
            if (data.notify.type === 'error') {
                manager.messager('error', data.notify.message);
            } else {
                window.location = "/annotation/corpus/sentence/" + annotation.data.idSentence;
            }
        });
    },
    onDeleteLastFELayer: (idAnnotationSet) => {
        window.ky.delete(`/annotation/corpus/annotationSet/lastFELayer`, {
            json: {
                _token: csrf,
                idAnnotationSet: idAnnotationSet,
            }
        }).json().then((data) => {
            if (data.notify.type === 'error') {
                manager.messager('error', data.notify.message);
            } else {
                window.location = "/annotation/corpus/sentence/" + annotation.data.idSentence;
            }
        });
    },
    onNullInstantiationFE: (rowIndex) => {
        let row = annotation.getRow(rowIndex);
        let position = $("tr[datagrid-row-index|='" + rowIndex + "'] > td[field=actions]").offset();
        let div = '#menu_lty_fe_' + row.idAnnotationSet + '_ni';
        $(div).menu('show', {
            left: position.left + 5,
            top: position.top + 5
        });
    },
    //
    // Datagrid
    //
    collapseAS: (idAnnotationSet) => {
        let show = annotation.data.annotationSets[idAnnotationSet].show;
        annotationd.data.annotationSets[idAnnotationSet].show = !show;
        let rows = $('#dataGridLayers').datagrid('getRows');
        for (var rowIndex in rows) {
            let row = rows[rowIndex];
            if (row.idLayerType !== 0) {
                rows[rowIndex].show = annotation.data.annotationSets[rows[rowIndex].idAnnotationSet].show;
                $('#dataGridLayers').datagrid('refreshRow', rowIndex);
            }
        }
    },
    onResizeColumn: function (field, width) {
    },
    setFields: (rowIndex, idLabel) => {
        let row = annotation.getRow(rowIndex);
        let rowIndexGF = null;
        let rowIndexPT = null;
        if ((idLabel === 0) && (row.idLayerType === 1)) {
            for (let r in rows) {
                let tempRow = rows[r];
                if ((tempRow.layerTypeEntry === 'lty_gf') && (tempRow.idAnnotationSet === row.idAnnotationSet)) {
                    rowIndexGF = r;
                }
                if ((tempRow.layerTypeEntry === 'lty_pt') && (tempRow.idAnnotationSet === row.idAnnotationSet)) {
                    rowIndexPT = r;
                }
            }
        }
        let field;
        let value = (idLabel === 0) ? '' : idLabel;
        let selection = annotation.selection;
        annotation.$dg.datagrid('beginEdit', rowIndex);
        for (field in selection.fields) {
            row[field] = value;
        }
        annotation.$dg.datagrid('endEdit', rowIndex);
        if ((idLabel === 0) && rowIndexGF) {
            annotation.$dg.datagrid('beginEdit', rowIndexGF);
            for (field in selection.fields) {
                rows[rowIndexGF][field] = value;
            }
            annotation.$dg.datagrid('endEdit', rowIndexGF);
        }
        if ((idLabel === 0) && rowIndexPT) {
            annotation.$dg.datagrid('beginEdit', rowIndexPT);
            for (field in selection.fields) {
                rows[rowIndexPT][field] = value;
            }
            annotation.$dg.datagrid('endEdit', rowIndexPT);
        }
    },
    initDatagrid: () => {
        let frozenColumns = [
            {
                field: "actions",
                title: "Actions",
                width: 72,
                formatter: annotation.cellActionsFormatter
            }
        ];
        for (let c of annotation.data.frozenColumns) {
            frozenColumns.push({
                field: c.field,
                title: c.title,
                //width: 200,
                //formatter: c.formatter
                formatter: annotation.cellLayerFormatter
            });
        }
        let columns = [];
        for (let c of annotation.data.columns) {
            if (c.type === 'data') {
                columns.push({
                    field: c.field,
                    hidden: true
                });
            }
            if (c.type === 'char') {
                annotation.endChar = c.index;
                columns.push({
                    field: c.field,
                    title: c.title,
                    char: c.char,
                    index: c.index,
                    order: c.order,
                    word: c.word,
                    hasLU: c.hasLU,
                    hidden: false,
                    formatter: annotation.cellFormatter,
                    hstyler: annotation.headerStyler,
                    styler: annotation.cellStyler,
                    resizable: false,
                    width: c.width
                });
            }
        }

        $('#dataGridLayers').datagrid({
            title: `Corpus Annotation [Corpus: ${annotation.data.metadata.corpus}][Document: ${annotation.data.metadata.document}][Sentence: ${annotation.data.metadata.sentence}] `,
            cls: "dataGridLayers",
            url: `/annotation/corpus/sentence/${annotation.data.idSentence}/data`,
            //data: [],//annotationData.layersData,
            // height: "100%",
            // width:"100%",
            fit: true,
            method: 'get',
            collapsible: false,
            fitColumns: false,
            autoRowHeight: false,
            nowrap: true,
            rowStyler: annotation.rowStyler,
            showHeader: false,
            onBeforeSelect: function () {
                return false;
            },
            onSelect: annotation.onSelect,
            onClickCell: annotation.onClickCell,
            //onHeaderContextMenu: annotation.onHeaderContextMenu,
            //onResizeColumn: annotation.onResizeColumn,
            //toolbar: tb,
            toolbar: "#toolbarLayers",
            frozenColumns: [frozenColumns],
            columns: [columns],
            emptyMsg: "<span style='color:red'>There is no AnnotationSet for this sentence yet.</span>"
            // onLoadSuccess: () => {
            //     annotation.cursor.init();
            // }
        });
    }
};




