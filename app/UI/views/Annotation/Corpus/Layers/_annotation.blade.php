<script type="text/javascript">
    // layers/annotation.js
    let UDTree = {
        element: 'UDTreeCanvas',
        UDTreeCurrent: null
    }

    // $(function () {
    // Alpine.start();
    //
    document.addEventListener('alpine:init', () => {
        Alpine.store('annotation', {
            status: 'browse',
            cssClass: '',
            cursor: {
                rowIndex: 0,
                word: 1
            },
            selection: {
                rowIndex: -1,
                idAnnotationSet: -1,
                fields: {},
                startChar: -1,
                endChar: -1
            },
            setStatus: (status) => {
                this.status = status
                if (status === 'edit') {
                    this.cssClass = 'cellSelectedEdit'
                }
                if (status === 'browse') {
                    this.cssClass = ''
                }
            },
            setCursor(cursor) {
                this.cursor = cursor;
            },
            setSelection(selection) {
                this.selection = selection;
            },
        })

        $(function () {
            window.ky.get('/annotation/corpus/sentence/{{$data->idSentence}}/object', {}).json().then((data) => {
                console.log(data);
                window.annotation = {
                    data: data,
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
                                    })
                                }
                            }
                        })
                        return labels;
                    },
                    cursor: {
                        init: () => {
                            let cursor = annotation.cursor.reset()
                            let row = annotation.getRow(cursor.rowIndex)
                            if (typeof row != 'undefined') {
                                if (Alpine.store('annotation').selection.rowIndex >= 0) {
                                    annotation.selection.clear();
                                }
                                if (row.idAnnotationSet > -1) {
                                    Alpine.store('annotation').setStatus('browse')
                                    annotation.cursor.show();
                                }
                            }
                        },
                        reset: () => {
                            Alpine.store('annotation').cursor.rowIndex = 1
                            Alpine.store('annotation').cursor.word = 1
                            return Alpine.store('annotation').cursor;
                        },
                        show: () => {
                            let cursor = Alpine.store('annotation').cursor;
                            if (cursor.rowIndex >= 0) {
                                // get the fields for the word
                                let startChar = annotation.data.words[cursor.word].startChar;
                                let endChar = annotation.data.words[cursor.word].endChar;
                                // console.log('show cursor', cursor, startChar, endChar);
                                //annotationMethods.markSelection(cursor.row, cursor.word);
                                for (let i = startChar; i <= endChar; i++) {
                                    let f = 'wf' + i;
                                    $("tr[datagrid-row-index|='" + cursor.rowIndex + "'] > td[field=" + f + "]").addClass("cellSelectedCursor");
                                }
                            }
                        },
                        clear: () => {
                            let cursor = Alpine.store('annotation').cursor;
                            if (cursor.rowIndex >= 0) {
                                let selection = Alpine.store('annotation').selection;
                                $selector = $("tr[datagrid-row-index|='" + selection.rowIndex + "'] > td");
                                $selector.removeClass("cellSelectedCursor");
                            }
                        },
                        moveTo: (cursor) => {
                            annotation.cursor.clear();
                            Alpine.store('annotation').setCursor(cursor)
                            annotation.cursor.show();
                        }
                    },
                    selection: {
                        init: () => {
                            Alpine.store('annotation').selection.rowIndex = -1
                            Alpine.store('annotation').selection.idAnnotationSet = -1
                            Alpine.store('annotation').selection.fields = {};
                            Alpine.store('annotation').selection.startChar = -1;
                            Alpine.store('annotation').selection.endChar = -1;
                            Alpine.store('annotation').status = 'browse';
                        },
                        clear: () => {
                            annotation.cursor.clear();
                            let selection = Alpine.store('annotation').selection;
                            $selector = $("tr[datagrid-row-index|='" + selection.rowIndex + "'] > td");
                            $selector.removeClass("cellSelectedCursor");
                            $selector.removeClass("cellSelectedEdit");
                            annotation.selection.init();
                        }
                    },
                    getRow: (index) => {
                        let rows = $('#dataGridLayers').datagrid("getRows");
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
                            text = text + `<div class="material-icons-outlined wt-icon-comment wt-anno-icon" onClick="annotation.ASComments(${row.idAnnotationSet})"></div>`;
                            text = text + `<div class="material-icons-outlined wt-icon-delete wt-anno-icon" onClick="annotation.deleteAS(${row.idAnnotationSet})"></div>`;
                            text = text + `<div class="material-icons-outlined wt-icon-collapse wt-anno-icon" onClick="annotation.collapseAS(${row.idAnnotationSet})"></div>`;
                        }
                        if (row.layerTypeEntry === 'lty_fe') {
                            if (!row.extraFELayer) {
                                text = text + `<div class="material-icons-outlined wt-icon-ni wt-anno-icon" onClick="annotation.dlgNI(${index})"></div>`;
                                text = text + `<div class="material-icons-outlined wt-icon-add wt-anno-icon" onClick="annotation.addFELayer(${row.idAnnotationSet})"></div>`;
                            } else {
                                text = text + `<div class="material-icons-outlined wt-icon-delete wt-anno-icon" onClick="annotation.deleteLastFELayer(${row.idAnnotationSet})"></div>`;
                            }
                        }
                        return text;
                    },
                    cellFormatter: (value, row, index) => {
                        console.log('cellFormmater', row, index);
                        if (this.field === 'name') {
                            if ((typeof annotation.data.layers[row.idLayer] != 'undefined')) {
                                return annotation.data.labelTypes[value]['label'];
                            }
                        }
                        if (this.field === 'wf0') {
                            if ((typeof annotation.data.layers[row.idLayer] != 'undefined')) {
                                annotation.data.layers[row.idLayer]['currentLabel'] = 0;
                            }
                        }
                        var text = '';
                        var idLabelType = (typeof value != 'undefined') ? value : '';
                        if (idLabelType !== '') {
                            if (row.idLayerType > 0) {
                                var pos = 0;
                                var label = annotation.data.labelTypes[idLabelType]['label'];
                                if (idLabelType !== annotation.data.layers[row.idLayer]['currentLabel']) {
                                    annotation.data.layers[row.idLayer]['currentLabel'] = idLabelType;
                                    pos = annotation.data.layers[row.idLayer]['currentLabelPos'] = 0;
                                } else {
                                    pos = ++annotation.data.layers[row.idLayer]['currentLabelPos'];
                                }
                                text = label.charAt(pos);
                            } else {
                                text = idLabelType;
                            }
                        } else {
                            if (row.idLayerType === 0) {
                                console.log(annotation.data.chars);
                                text = this.data.chars[this.field].char;
                            }
                            if ((typeof annotation.data.layers[row.idLayer] != 'undefined')) {
                                if (annotation.data.layers[row.idLayer]['currentLabelPos'] > 0) {
                                    annotation.data.layers[row.idLayer]['currentLabel'] = 0;
                                    annotation.data.layers[row.idLayer]['currentLabelPos'] = 0;
                                }
                            }
                        }
                        return text;
                    },

                    cellLayerFormatter: (value, row, index) => {
                        var text = value;
                        if (row.idLayerType === 0) {
                            text = annotation.data.annotationSets[row.idAnnotationSet]['name'];
                        }
                        if (row.layerTypeEntry === 'lty_fe') {
                            var textNis = "";
                            var nis = annotation.data.nis[row.idLayer];
                            if (nis) {
                                var i = 0;
                                jQuery.each(nis, function (idLabel, ni) {
                                    var classColor = "color_" + ni.idColor
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
                        var style = '';
                        if (row.idLayerType === 0) {
                            if (typeof value === 'undefined') {
                                style = 'color:#AAA;';
                            }
                        }
                        if ((typeof value != 'undefined') && (value !== '')) {
                            var idLabelType;
                            var idColor;
                            if (row.idLayerType === -1) {
                                idColor = 1;
                            } else {
                                if (row.idLayerType === 0) { // Target
                                    idLabelType = annotation.data.layerLabels[row.idLayer][0];
                                } else {
                                    idLabelType = value;
                                }
                                idColor = annotation.data.labelTypes[idLabelType]['idColor'];
                            }
                            return {class: 'color_' + idColor}
                        }
                        return style;
                    },
                    headerStyler: (title, col) => {
                        let style = '';
                        if (annotation.data.words[annotation.data.chars[col.field]['word']]['hasLU']) {
                            style = {class: 'headerLU'}
                        }
                        return style;
                    },
                    //
                    // Selection
                    //
                    onSelect: (rowIndex, rowData) => {
                    },

                    onClickCell: (rowIndex, field, value) => {
                        if (field === 'actions') {
                            return;
                        }
                        let cursor = Alpine.store('annotation').cursor;
                        let selection = Alpine.store('annotation').selection;
                        let row = annotation.getRow(rowIndex);
                        console.log('clicked cell', rowIndex, field, value);
                        if (value === 'undefined') {
                            value = ''
                        }
                        if (value === '') {
                            if (row.idLayerType === 0) {
                                return;
                            }
                            if ((row.idLayerType === 25) && (field === 'layer')) {
                                return;
                            }
                            if (row.layerTypeEntry.substring(0, 8) === 'lty_cefe') {
                                return;
                            }
                            if (annotationData.chars[field]['char'] === ' ') {
                                return;
                            }
                        }
                        let isEdit = false;
                        let alreadyEdit = false;
                        let sameSelection = false;
                        let wordIndex = annotation.data.chars[field]['word'];
                        console.log('click cell - currentSelection', selection, wordIndex)

                        if (Alpine.store('annotation').status === 'browse') {
                            // se é a mesma palavra do cursor, entra em edit
                            if (wordIndex === cursor.word) {
                                Alpine.store('annotation').setStatus('edit');
                                annotation.markSelection(rowIndex, wordIndex);
                            } else { // se não é, apenas move o cursor
                                annotation.cursor.moveTo({
                                    row: rowIndex, word: wordIndex
                                })
                            }
                        } else if (Alpine.store('annotation').status === 'edit') { // editing
                            // se mudou de row, remove a seleção, move o cursor e volta para browse
                            if (selection.rowIndex !== rowIndex) {
                                annotation.selection.clear();
                                annotation.cursor.moveTo({
                                    row: rowIndex, word: wordIndex
                                })
                                Alpine.store('annotation').setStatus('browse');
                            } else { // está na mesma row
                                if (selection.fields[field]) { // field já está na selecão - mostra o context
                                    annotation.createContextDialog(rowIndex, row);
                                } else {
                                    // se é uma palavra na frente da atual, estende a seleção
                                    let charIndex = annotation.data.chars[field]['order'];
                                    if (charIndex >= selection.endChar) {
                                        annotation.markSelection(rowIndex, wordIndex);
                                    } else { // remove a seleção, move o cursor e volta para browse
                                        annotation.selection.clear();
                                        annotation.cursor.moveTo({
                                            row: rowIndex, word: wordIndex
                                        })
                                        Alpine.store('annotation').setStatus('browse');
                                    }
                                }
                            }
                        }
                        document.getSelection().removeAllRanges();
                    },
                    markSelection: (rowIndex, wordIndex) => {
                        let selection = Alpine.store('annotation').selection;
                        if (selection.rowIndex > 0) {
                            if (rowIndex !== selection.rowIndex) {
                                annotation.selection.clear();
                            }
                        }
                        console.log('markSelection', rowIndex, wordIndex);
                        let cursorRowIndex = rowIndex;
                        let row = annotation.getRow(rowIndex)
                        //console.log(row);
                        let columns = $('#dataGridLayers').datagrid('getColumnFields');
                        let start = end = -1;
                        let startChar = endChar = -1;
                        // get field from the first char of word
                        let fieldNum = annotation.data.words[wordIndex].startChar;
                        let field = 'wf' + fieldNum;
                        // se já tiver anotação nesta celula (camada x coluna), usa os limites da anotação
                        var idLabel = row[field];
                        if (idLabel) {
                            pstart = pend = fieldNum;
                            while (row['wf' + pstart] === idLabel) {
                                start = pstart--;
                            }
                            while (row['wf' + pend] === idLabel) {
                                end = pend++;
                            }
                        }
                        if (startChar === -1) { // não tem anotação nesta celula
                            if ((row.layerTypeEntry === 'lty_gf') || (row.layerTypeEntry === 'lty_pt')) {
                                // se já tiver anotação na camada FE na coluna escolhida, usa os limites do FE
                                let tempCursorRowIndex = rowIndex;
                                if (row.idLayerType !== 1) {
                                    for (let r in rows) {
                                        let tempRow = rows[r];
                                        if ((tempRow.idLayerType === 1) && (tempRow.idAnnotationSet === row.idAnnotationSet)) {
                                            tempCursorRowIndex = r;
                                            idLabel = tempRow[field];
                                            console.log('idLabel = ' + idLabel);
                                            if (idLabel) {
                                                //console.log(tempRow);
                                                pstart = pend = parseInt(field.substring(2, 5));
                                                while (tempRow['wf' + pstart] === idLabel) {
                                                    start = pstart--;
                                                }
                                                while (tempRow['wf' + pend] === idLabel) {
                                                    end = pend++;
                                                }
                                            }
                                        }
                                    }
                                }
                                if (startChar === -1) {
                                    cursorRowIndex = tempCursorRowIndex;
                                }
                            }
                        }

                        if (startChar === -1) {
                            // se não existe anotação nesta camada na coluna escolhida, usa os limites da palavra correspondente à coluna
                            for (let column = 0; column < columns.length; column++) {
                                let f = columns[column];
                                if ((selection.fields[f]) || (f === field)) {
                                    var word = annotation.data.words[annotation.data.chars[f]['word']];
                                    console.log('word', word);
                                    if (startChar === -1) {
                                        startChar = word['startChar'];
                                        endChar = word['endChar'];
                                    }
                                    if (f === field) {
                                        endChar = word['endChar'];
                                    }
                                }
                            }
                        }
                        if (startChar > -1) {
                            if (Alpine.store('annotation').status === 'browse') {
                                annotation.selection.clear();
                            } else if (Alpine.store('annotation').status === 'edit') { // está editando - mantem o start atual
                                startChar = selection.startChar;
                            }
                            let statusClass = Alpine.store('annotation').cssClass;
                            for (let i = startChar; i <= endChar; i++) {
                                let f = 'wf' + i;//columns[i];
                                $("tr[datagrid-row-index|='" + cursorRowIndex + "'] > td[field=" + f + "]").addClass("cellSelected" + " " + statusClass);
                                selection.fields[f] = true;
                            }
                            selection.startChar = start;
                            selection.endChar = end;
                        }
                        selection.rowIndex = cursorRowIndex;
                        selection.idAnnotationSet = row.idAnnotationSet;
                        Alpine.store('annotation').setSelection(selection);
                        Alpine.store('annotation').setCursor({
                            row: cursorRowIndex, word: row.word
                        })
                        console.log('end markselection', selection);
                    },
                    //
                    // Datagrid
                    //
                    collapseAS: (idAnnotationSet) => {
                        let show = annotation.data.annotationSets[idAnnotationSet].show;
                        annotationd.data.annotationSets[idAnnotationSet].show = !show;
                        let rows = $('#dataGridLayers').datagrid('getRows');
                        for (rowIndex in rows) {
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
                        let row = Alpine.store('annotation').getRow(rowIndex);
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
                        let selection = Alpine.store('annotation').currentSelection;
                        $('#dataGridLayers').datagrid('beginEdit', rowIndex);
                        for (field in selection.fields) {
                            row[field] = value;
                        }
                        $('#dataGridLayers').datagrid('endEdit', rowIndex);
                        if ((idLabel === 0) && rowIndexGF) {
                            $('#dataGridLayers').datagrid('beginEdit', rowIndexGF);
                            for (field in selection.fields) {
                                rows[rowIndexGF][field] = value;
                            }
                            $('#dataGridLayers').datagrid('endEdit', rowIndexGF);
                        }
                        if ((idLabel === 0) && rowIndexPT) {
                            $('#dataGridLayers').datagrid('beginEdit', rowIndexPT);
                            for (field in selection.fields) {
                                rows[rowIndexPT][field] = value;
                            }
                            $('#dataGridLayers').datagrid('endEdit', rowIndexPT);
                        }
                        // console.log(row);
                        // annotation.clearSelection(rowIndex);
                        //annotation.dirtyData();
                        Alpine.store('annotation').saveSelection({
                                operation: ((value !== '') ? 'save' : 'delete'),
                                startChar: selection.start,
                                endChar: selection.end,
                                idLabelType: value,
                                idLayer: row.idLayer,
                                idInstantiationType: 12,
                                multi: 0
                            }
                        )
                    },
                    initDatagrid: () => {
                        let frozenColumns = [
                            {
                                field: "actions",
                                title: "Actions",
                                width: 60,
                                formatter: annotation.cellActionsFormatter
                            }
                        ]
                        for (let c of annotation.data.frozenColumns) {
                            frozenColumns.push({
                                field: c.field,
                                title: c.title,
                                //formatter: c.formatter
                                formatter: annotation.cellLayerFormatter
                            })

                            let columns = [];
                            for (let c of annotation.data.columns) {
                                if (c.type === 'data') {
                                    columns.push({
                                        field: c.field,
                                        hidden: true
                                    });
                                }
                                if (c.type === 'char') {
                                    columns.push({
                                        field: c.field,
                                        title: c.title,
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
                                showHeader: true,
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
                                onLoadSuccess: function () {
                                    annotation.cursor.init();
                                }
                            });
                        }
                    }
                }
                annotation.initDatagrid();
            })
        })
    })
</script>