<script type="text/javascript">
    // layers/dialog.js
    $(function () {

        annotationMethods.dlgNI = function (rowIndex) {
            var rows = $('#dataGridLayers').datagrid("getRows");
            var row = rows[rowIndex];
            annotationMethods.clearSelection(annotationMethods.currentSelection.rowIndex);
            annotationMethods.currentSelection.rowIndex = rowIndex;
            annotationMethods.currentSelection.idAnnotationSet = row.idAnnotationSet;
            var comboEditor = {
                type: 'combobox',
                options: {
                    valueField: 'idInstantiationType',
                    textField: 'instantiationType',
                    data: annotationMethods.instantiationType
                }
            };
            var annotatedLabels = annotationMethods.getAnnotationByLayer(row.idAnnotationSet, 'lty_fe');
            console.log(annotatedLabels)
            var labels = annotationMethods.layerLabels[row.idLayer];
            console.log(labels);
            var fes = [];
            var i = 0;
            var j = 0;
            jQuery.each(labels, function (i, idLabel) {
                if (typeof annotatedLabels[idLabel] === 'undefined') {
                    var label = annotationMethods.labelTypes[idLabel];
                    console.log(label);
                    if ((label['coreType'] === 'cty_core') || (label['coreType'] === 'cty_core-unexpressed')) {
                        var value = '';
                        if (typeof annotationMethods.nis[row.idLayer] != 'undefined') {
                            if (typeof annotationMethods.nis[row.idLayer][idLabel] != 'undefined') {
                                value = annotationMethods.nis[row.idLayer][idLabel]['idInstantiationType'];
                            }
                        }
                        fes[j++] = {
                            idLayer: row.idLayer,
                            idLayerType: row.idLayerType,
                            name: idLabel,
                            value: value,
                            editor: comboEditor
                        };
                    }
                }
            });
            console.log(fes);
            $('#pg').propertygrid({data: fes});
            $('#pg').propertygrid({idLayer: row.idLayer});
            $('#pg').propertygrid({
                columns: [[
                    {
                        field: 'name', width: 200, title: 'Frame Element',
                        styler: annotationMethods.cellStyler,
                        formatter: annotationMethods.cellFormatter
                    },
                    {
                        field: 'value', width: 70, title: 'IT',
                        formatter: function (value, row, index) {
                            var r = '';
                            jQuery.each(annotationMethods.instantiationType, function (i, it) {
                                if (parseInt(value) === it.idInstantiationType) {
                                    r = it.instantiationType;
                                }
                            });
                            return r;
                        }
                    }
                ]]
            });
            $('#dlgNI').dialog('doLayout');
            $('#dlgNI').dialog('open');
            annotationMethods.pushTopDialog('#dlgNI');
        }

        annotationMethods.dlgNISave = function () {
            var nis = annotationMethods.nis;
            var rows = $('#pg').propertygrid("getRows");
            var idLayer = $('#pg').propertygrid("options").idLayer;
            nis[idLayer] = {};
            for (index in rows) {
                var row = rows[index];
                idLayer = row.idLayer;
                if ((row.value !== '') && (row.value !== '0')) {
                    var idLabel = row.name;
                    nis[row.idLayer] = nis[row.idLayer] || {};
                    nis[row.idLayer][idLabel] = {
                        fe: annotationMethods.labelTypes[idLabel]['label'],
                        idInstantiationType: row.value,
                        label: annotationMethods.instantiationTypeObj[row.value],
                        idColor: annotationMethods.labelTypes[idLabel]['idColor']
                    };
                }
            }
            var rowIndex = annotationMethods.currentSelection.rowIndex;
            console.log(annotationMethods.currentSelection)
            $('#dataGridLayers').datagrid('beginEdit', rowIndex);
            annotationMethods.nis = nis;
            //$('#dataGridLayers').datagrid('getRows')[rowIndex]['ni'] = '';
            $('#dataGridLayers').datagrid('endEdit', rowIndex);
            annotationMethods.saveNI(annotationMethods.nis)
            //$('#dataGridLayers').datagrid('autoSizeColumn', 'ni');
//            annotationMethods.dirtyData();
        }

        annotationMethods.dlgASOpen = function () {
            var data = [];
            var i = 0;
            for (as in annotationMethods.annotationSets) {
                data[i++] = annotationMethods.annotationSets[as];
            }
            $('#asGrid').datagrid({data: data});
            var rows = $('#asGrid').datagrid('getRows');
            for (r in rows) {
                if (!annotationMethods.annotationSets[rows[r].idAnnotationSet].show) {
                    $('#asGrid').datagrid('checkRow', r);
                }
            }
            $('#dlgAS').dialog('doLayout');
            $('#dlgAS').dialog('open');
            annotationMethods.pushTopDialog('#dlgAS');
        }

        annotationMethods.dlgASSave = function () {
            for (as in annotationMethods.annotationSets) {
                annotationMethods.annotationSets[as].show = true;
            }
            var rowsChecked = $('#asGrid').datagrid('getChecked');
            for (c in rowsChecked) {
                var idAnnotationSet = rowsChecked[c].idAnnotationSet;
                annotationMethods.annotationSets[idAnnotationSet].show = false;
            }
            var rows = $('#dataGridLayers').datagrid('getRows');
            for (r in rows) {
                rows[r].show = annotationMethods.annotationSets[rows[r].idAnnotationSet].show;
                $('#dataGridLayers').datagrid('refreshRow', r);
            }
            $('#dlgAS').dialog('close');
        }

        annotationMethods.dlgASOpenRemove = function () {
            if (!annotationMethods.checkSavedData()) {
                return;
            }
            var data = [];
            var i = 0;
            console.log('as', annotationMethods.annotationSets);
            for (as in annotationMethods.annotationSets) {
                data[i++] = annotationMethods.annotationSets[as];
            }
            $('#asGridRemove').datagrid({data: data});
            var rows = $('#asGridRemove').datagrid('getRows');
            for (r in rows) {
                if (!annotationMethods.annotationSets[rows[r].idAnnotationSet].show) {
                    $('#asGridRemove').datagrid('checkRow', r);
                }
            }
            $('#dlgASRemove').dialog('doLayout');
            $('#dlgASRemove').dialog('open');
            annotationMethods.pushTopDialog('#dlgASRemove');
        }

        annotationMethods.dlgASSaveRemove = function () {
            var AStoRemove = {};
            var rowsChecked = $('#asGridRemove').datagrid('getChecked');
            for (c in rowsChecked) {
                var idAnnotationSet = rowsChecked[c].idAnnotationSet;
                console.log(idAnnotationSet);
                AStoRemove[idAnnotationSet] = idAnnotationSet;
            }
            $.ajax({
                type: "POST",
                url: "{{$manager->getURL('annotation/main/deleteAS')}}",
                data: {AStoDelete: 'json:' + JSON.stringify(AStoRemove)},
                dataType: "json",
                async: false
            });
            $('#dlgASRemove').dialog('close');
            annotationMethods.refresh();
        }

        annotationMethods.dlgASCommentsSave = function () {
            manager.doPost('', "{{$manager->getURL('annotation/main/saveASComments')}}", 'formASComments');
            $('#dlgASComments').dialog('close');
        }

        annotationMethods.dlgLUSave = function () {
            var lu = $('#dlgLUList').datalist('getSelected');
            if (lu.idLU > 0) {
                console.log(lu);
                var field = $('#dlgLUField').attr('value');
                var wf = annotationMethods.words[annotationMethods.chars[field]['word']];
                console.log(wf);
                $('#dlgLU').dialog('close');
                $('#dlgLU').dialog('destroy', true);
                if (lu.mwe != '0') {
                    annotationMethods.addMWELU(wf, lu.idLU, annotationMethods.idSentence);
                } else {
                    annotationMethods.addLU(lu.idLU, annotationMethods.idSentence, wf.startChar, wf.endChar);
                }
            }
        }

        annotationMethods.dlgCxnOpen = function () {
            $('#cxnGrid').datagrid({singleSelect: true, url: "{{$manager->getURL('annotation/main/cxnGridData')}}"});
            $('#dlgCxn').dialog('doLayout');
            $('#dlgCxn').dialog('open');
        }

        annotationMethods.dlgCxnSave = function () {
            if (!annotationMethods.checkSavedData()) {
                return;
            }
            var selected = $('#cxnGrid').datagrid('getSelected');
            //console.log(selected);
            $.ajax({
                type: "POST",
                url: "{{$manager->getURL('annotation/main/addCxn')}}",
                data: {idConstruction: selected.idConstruction, idSentence: annotationMethods.idSentence},
                dataType: "json",
                async: false,
            });
            $('#dlgCxn').dialog('close');
            annotationMethods.refresh();
        }

        annotationMethods.dlgValidationOpen = function () {
            $('#dlgValidation').dialog('doLayout');
            $('#dlgValidation').dialog('open');
        }

        annotationMethods.ASComments = function (idAnnotationSet) {
            annotationMethods.idASComments = idAnnotationSet;
            $('#dlgASComments').dialog({href: "{{$manager->getURL('annotation/main/formASComments')}}" + "/" + annotationMethods.idASComments});
            $('#dlgASComments').dialog('doLayout');
            $('#dlgASComments').dialog('open');
        }

        annotationMethods.ASInfo = function (idAnnotationSet) {
            var idASInfo = annotationMethods.annotationSets[idAnnotationSet];
            //$('#dlgASInfo').dialog({href: "{{$manager->getURL('annotation/main/formASComments')}}" + "/" + annotationMethods.idASComments });
            $('#dlgASInfo_idAnnotationSet').html(idAnnotationSet);
            if (idASInfo['type'] == 'lu') {
                $('#dlgASInfo_type').html('Frame.LU');
            }
            if (idASInfo['type'] == 'cxn') {
                $('#dlgASInfo_type').html('Construction');
            }
            $('#dlgASInfo_name').html(idASInfo['name']);
            $('#dlgASInfo').dialog('doLayout');
            $('#dlgASInfo').dialog('open');
        }

        annotationMethods.showMessage = function (element, msg) {
            if (!element.children("div.datagrid-mask").length) {
                $("<div class=\"datagrid-mask\" style=\"display:block\"></div>").appendTo(element);
                var msg = $("<div class=\"datagrid-mask-msg\" style=\"display:block;left:50%\"></div>").html(msg).appendTo(element);
                msg._outerHeight(40);
                msg.css({marginLeft: (-msg.outerWidth() / 2), lineHeight: (msg.height() + "px")});
            }
        }

        annotationMethods.hideMessage = function (element) {
            element.children("div.datagrid-mask-msg").remove();
            element.children("div.datagrid-mask").remove();
        }

        annotationMethods.pushTopDialog = function (element) {
            annotationMethods.topDialog = element;
        }

        annotationMethods.popTopDialog = function () {
            annotationMethods.topDialog = '';
        }


        annotationMethods.UDTree = function (rowIndex) {
            if (!annotationMethods.checkSavedData()) {
                return;
            }
            //console.log(annotationMethods.layerLabels[idLayer]);
            var rows = $('#dataGridLayers').datagrid('getRows');
            var row = rows[rowIndex];
            var idLayer = row.idLayer;
            console.log(row);
            var words = {};
            var idLabelPrev = -1;
            for (field in row) {
                if (field.substring(0, 2) === 'wf') {
                    idLabel = row[field];
                    pchar = field.substr(2, 5);
                    char = annotationMethods.chars[field].char;
                    console.log(pchar + ' ' + idLabel);
                    if (idLabel !== idLabelPrev) {
                        words[idLabel] = {};
                        words[idLabel]['start'] = pchar;
                        words[idLabel]['name'] = annotationMethods.labelTypes[idLabel].label;
                        words[idLabel]['word'] = '';
                        idLabelPrev = idLabel;
                    }
                    words[idLabel]['end'] = pchar;
                    words[idLabel]['word'] = words[idLabel]['word'] + char;
                }
            }
            console.log(words);
            var UDTreeCurrent = {};
            if (annotationMethods.UDTreeCurrent === undefined) {
                if (annotationMethods.UDTreeLayer[idLayer] !== undefined) {
                    UDTreeCurrent = annotationMethods.UDTreeLayer[idLayer];
                }
            }
            annotationMethods.UDTreeCurrent = {};
            for (idLabel in words) {
                var word = words[idLabel];
                annotationMethods.UDTreeCurrent[idLabel] = {
                    id: idLabel,
                    start: word.start,
                    length: word.end - word.start + 1,
                    ud: word.name,
                    name: word.word,
                    parent: UDTreeCurrent[idLabel] ? UDTreeCurrent[idLabel] : null
                };
            }
            console.log(annotationMethods.UDTreeCurrent);
            UDTree.UDTreeCurrent = annotationMethods.UDTreeCurrent;
            $('#dlgUDTree').dialog('doLayout');
            $('#dlgUDTree').dialog('open');
        }

        $('#dlgContext').dialog({
            title: 'Label',
            width: 420,
            height: 250,
            closed: true,
            cache: false,
            modal: true,
            onClose: function () {
                annotationMethods.clearSelection(annotationMethods.currentSelection.rowIndex);
            }
        });

        annotationMethods.manageLayers = function () {
            $('#dlgManageLayers').dialog({
                closed: true,
                toolbar: '#dlgManageLayers_tools',
            });
            // $('#dlgManageLayers').dialog('resize',{width:'auto',height:'auto'});
            // $('#lbSave').linkbutton({iconCls:'material wt-icon-save',plain:true,size:null});
            // $('#formManagerDialog').dialog({modal:true,doSize:true,onClose:function() {location.reload(true); $('#formManagerDialog').dialog('destroy', true);},toolbar:'#formManagerDialog_tools'});
            $('#dlgManageLayersGrid').datagrid({
                checkOnSelect: true,
                url: "/annotation/layer/gridData",
                pagination: false,
                idField: "idLayerType",
                border: true,
                columns: [
                    {field: "gridManagerCheck", title: null, hidden: false, type: "check", checkbox: true},
                    {field: "idLayerType", title: null, hidden: true, type: "label"},
                    {field: "name", title: "Name", hidden: false, width: "100px"}
                ],
                onLoadSuccess: function () {
                    var layersToShow = annotationData.layersToShow;
                    $.each(layersToShow, function (index, element) {
                        console.log(element);
                        $('#dlgManageLayersGrid').datagrid('selectRecord', element);
                    })
                }
            })
        }

    });
</script>