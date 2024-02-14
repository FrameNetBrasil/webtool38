<script type="text/javascript">
    // layers/save.js
    $(function () {
        @if ($data->canSave)

        // annotation.save = function () {
        //     var panel = $('#dataGridLayers').datagrid("getPanel");
        //     annotation.showMessage(panel, "Saving AnnotationSets...");
        //     $('#type').val(annotation.type);
        //     var data = annotation.getDataToPost();
        //     $('#dataLayers').val(data);
        //     manager.doPostBack('formLayers');
        //     annotation.cleanData();
        //     annotation.hideMessage(panel);
        // }

        annotationMethods.saveLabel = function (labelData) {
            $.ajax({
                type: "POST",
                url: "/annotation/corpus/saveLabel",
                data: {labelData: labelData},
                dataType: "json",
                async: false
            }).done(function (data) {
                console.log(data);
            }).fail(function (jqXHR, data) {
                console.log(data)
            });
        }

        annotationMethods.deleteLabel = function (labelData) {
            $.ajax({
                type: "POST",
                url: "/annotation/corpus/deleteLabel",
                data: {labelData: labelData},
                dataType: "json",
                async: false
            }).done(function (data) {
                console.log(data);
            }).fail(function (jqXHR, data) {
                console.log(data)
            });
        }

        annotationMethods.deleteAS = function (idAnnotationSet) {
            $.ajax({
                type: "POST",
                url: "annotation/corpus/deleteAS",
                data: {AStoDelete: [idAnnotationSet]},
                dataType: "json",
                async: false
            }).done(function (data) {
                annotation.refresh();
            }).fail(function (jqXHR, data) {
                console.log(data)
            });
        }

        annotationMethods.saveNI = function (niData) {
            $.ajax({
                type: "POST",
                url: "annotation/corpus/saveNI",
                data: {niData: niData},
                dataType: "json",
                async: false
            }).done(function (data) {
                console.log(data);
            }).fail(function (jqXHR, data) {
                console.log(data)
            });
        }

        @endif

        annotationMethods.addFELayer = function (idAnnotationSet) {
            var data = {idAnnotationSet: idAnnotationSet};
            $.ajax({
                type: "POST",
                url: "annotation/corpus/addFELayer",
                data: data,
                dataType: "json",
                success: function (data) {
                    for (item in data) {
                        if (annotation.layers[item] === undefined) {
                            annotation.layers[item] = data[item];
                        }
                    }
                }
            }).done(function () {
                annotation.refresh();
            }).fail(function (jqXHR, data) {
                console.log(data)
            });
        }

        annotationMethods.deleteLastFELayer = function (idAnnotationSet) {
            var data = {idAnnotationSet: idAnnotationSet};
            $.ajax({
                type: "POST",
                url: "annotation/corpus/delFELayer",
                data: data,
                dataType: "json"
            }).done(function () {
                annotation.refresh();
            }).fail(function (jqXHR, data) {
                console.log(data)
            });
        }

        annotationMethods.refresh = function () {
            console.log('refresh 1');
            $('#dlgValidation').dialog('destroy');
            console.log('refresh 2');
            $('#dlgNI').dialog('destroy');
            console.log('refresh 3');
            $('#dlgMWE').dialog('destroy');
            console.log('refresh 4');
            $('#dlgASComments').dialog('destroy');
            console.log('refresh 5');
            $('#dlgASInfo').dialog('destroy');
            console.log('refresh 6');
            $('#dlgAS').dialog('destroy');
            console.log('refresh 7');
            $('#dlgASRemove').dialog('destroy');
            console.log('refresh 8');
            $('#dlgCxn').dialog('destroy');
            console.log('refresh 9');
            //$('#dataGridLayers').datagrid('destroy');
            //$('#dataGridLayers').panel('refresh');
            location.reload(true);
        }

        // annotation.getDataToPost = function () {
        //     var data = [];
        //     var i = 0;
        //     var rows = $('#dataGridLayers').datagrid('getRows');
        //     for (r in rows) {
        //         var row = rows[r];
        //         var line = {};
        //         line['ni'] = {};
        //         for (field in row) {
        //             if (field === 'ni') {
        //                 if (annotation.nis[row['idLayer']]) {
        //                     line['ni'][row['idLayer']] = {};
        //                     for (idLabel in annotation.nis[row['idLayer']]) {
        //                         line['ni'][row['idLayer']][idLabel] = {
        //                             idInstantiationType: annotation.nis[row['idLayer']][idLabel]['idInstantiationType'],
        //                             idSentenceWord: annotation.nis[row['idLayer']][idLabel]['idSentenceWord']
        //                         };
        //                     }
        //                 }
        //             } else {
        //                 line[field] = row[field];
        //             }
        //         }
        //         data[i++] = line;
        //     }
        //     //console.log(data);
        //     return JSON.stringify(data);
        // }

        // annotation.checkSavedData = function () {
        //     console.log('checkSavedData: ' + (annotation.dataIsSaved ? 'true' : 'false'));
        //     if (!annotation.dataIsSaved) {
        //         $.messager.alert('Warning', 'Save your data before this operation!', 'warning');
        //         return false;
        //     }
        //     return true;
        // }

        // annotation.dirtyData = function () {
        //     console.log('dirtyData');
        //     annotation.dataIsSaved = false;
        //     $('#layersPane .datagrid-header-inner').css('background-color', '#ffcccc');
        // }
        //
        // annotation.cleanData = function () {
        //     console.log('cleanData');
        //     annotation.dataIsSaved = true;
        //     $('#layersPane .datagrid-header-inner').css('background-color', '#efefef');
        // }
    });
</script>