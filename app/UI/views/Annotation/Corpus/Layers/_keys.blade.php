<script type="text/javascript">
    // layers/keys.js

    annotation.key = {
        backspace: 8,
        tab: 9,
        enter: 13,
        shift: 16,
        ctrl: 17,
        alt: 18,
        escape: 27,
        pageUp: 33,
        pageDown: 34,
        space: 32,
        end: 35,
        home: 36,
        arrowLeft: 37,
        arrowUp: 38,
        arrowRight: 39,
        arrowDown: 40,
        insert: 45,
        delete: 46
    }

    $(function () {

        // $(document).bind('keydown', 'ctrl+s', function () {
        //     window.event.preventDefault();
        //     annotation.save();
        // });

        $("body").keypress(function (event) {
            event = event || window.event // IE support
            var c = event.which;
            var ctrlDown = event.ctrlKey || event.metaKey; // Mac support
            // Check for Alt+Gr (http://en.wikipedia.org/wiki/AltGr_key)
            if (ctrlDown && event.altKey) {
            } else if (ctrlDown && c === 115) { // ctrl-s
                event.preventDefault();
                annotation.save();
            }
        });


        $("body").keydown(function (event) {
            //console.log('keydown');
            var prev, next, field, rowIndex;
            var c = event.which;
            console.log(event.which);
            if (event.shiftKey) {

                switch (c) {
                    case annotation.key.arrowRight: {
                        console.log('====== shift-right')
                        if (annotation.currentSelection.rowIndex >= 0) {
                            console.log('current selection (1)', annotation.currentSelection)
                            next = annotation.currentSelection.end + 1;
                            field = 'wf' + next;
                            if (typeof annotation.chars[field] != 'undefined') {
                                if (annotation.chars[field]['char'] === ' ') {
                                    next = annotation.currentSelection.end + 1;
                                    field = 'wf' + next;
                                }
                                if (typeof annotation.chars[field] != 'undefined') {
                                    console.log('current selection (2)', annotation.currentSelection, field)
                                    annotation.markSelection(annotation.currentSelection.rowIndex, field);
                                    console.log('current selection (3)', annotation.currentSelection, field)
                                    document.getSelection().removeAllRanges();
                                }
                            }
                        }
                        event.stopPropagation();
                        break;
                    }
                    case annotation.key.arrowLeft: {
                        console.log('====== shift-left')
                        if (annotation.currentSelection.rowIndex >= 0) {
                            console.log('current cursor', annotation.cursor)
                            console.log('current selection (1)', annotation.currentSelection)
                            prev = annotation.currentSelection.start - 1;
                            field = 'wf' + prev;
                            console.log('prev field', field)
                            if (typeof annotation.chars[field] != 'undefined') {
                                if (annotation.chars[field]['char'] === ' ') {
                                    prev = annotation.currentSelection.start - 1;
                                    field = 'wf' + prev;
                                }
                                console.log('prev field', field)
                                if (typeof annotation.chars[field] != 'undefined') {
                                    annotation.markSelection(annotation.currentSelection.rowIndex, field);
                                    document.getSelection().removeAllRanges();
                                }
                            }
                        }
                        event.stopPropagation();
                        break;
                    }
                }

            } else {


                switch (c) {
                    case annotation.key.enter: {
                        if (annotation.getStatus() === 'edit') {
                            var rows = $('#dataGridLayers').datagrid("getRows");
                            var row = rows[annotation.currentSelection.rowIndex];
                            annotation.createContextDialog(annotation.currentSelection.rowIndex, row);
                        }
                    }
                    case annotation.key.escape: {
                        if (annotation.topDialog !== '') {
                            $(annotation.topDialog).dialog('close');
                            annotation.popTopDialog();
                        } else {
                            annotation.initCursor();
                        }
                    }
                    case annotation.key.delete: {
                        if (annotation.currentSelection.rowIndex >= 0) {
                            annotation.setFields(annotation.currentSelection, '');
                            annotation.showCursor();
                        }
                        event.stopPropagation();
                        break;
                    }
                    case annotation.key.arrowLeft: {
                        if (annotation.currentSelection.rowIndex >= 0) {
                            prev = annotation.currentSelection.start - 1;
                            field = 'wf' + prev;
                            if (typeof annotation.chars[field] != 'undefined') {
                                if (annotation.chars[field]['char'] === ' ') {
                                    prev = annotation.currentSelection.start - 2;
                                    field = 'wf' + prev;
                                }
                                if (typeof annotation.chars[field] != 'undefined') {
                                    annotation.currentSelection.fields = {};
                                    annotation.markSelection(annotation.currentSelection.rowIndex, field);
                                    var $body = $('.datagrid-view2 div.datagrid-body');
                                    var bodyWidth = $($body).width();
                                    console.log('body width = ' + bodyWidth);
                                    var $header = $('.datagrid-view2 .datagrid-header-row');
                                    var headerWidth = $($header).width();
                                    console.log('header width = ' + headerWidth);
                                    var currentScrollLeft = $($body).scrollLeft();
                                    console.log('current scrollleft = ' + currentScrollLeft);
                                    console.log('current selection', annotation.currentSelection)
                                    var currentLength = headerWidth - ((annotation.currentSelection.start + 1) * 13);
                                    console.log('current length = ' + currentLength);
                                    if (currentLength > bodyWidth) {
                                        var rate = headerWidth / bodyWidth;
                                        console.log('rate = ' + rate);
                                        var offset = currentScrollLeft - (Math.floor((currentLength - bodyWidth) * rate));
                                        console.log('offset = ' + offset);
                                        $($body).scrollLeft(offset)
                                    }
                                }
                            }
                        }
                        event.stopPropagation();
                        break;
                    }
                    case annotation.key.arrowRight: {
                        console.log('====== right')
                        if (annotation.currentSelection.rowIndex >= 0) {
                            next = annotation.currentSelection.end + 1;
                            field = 'wf' + next;
                            if (typeof annotation.chars[field] != 'undefined') {
                                if (annotation.chars[field]['char'] === ' ') {
                                    next = annotation.currentSelection.end + 2;
                                    field = 'wf' + next;
                                }
                                if (typeof annotation.chars[field] != 'undefined') {
                                    annotation.currentSelection.fields = {};
                                    annotation.markSelection(annotation.currentSelection.rowIndex, field);
                                    var $body = $('.datagrid-view2 div.datagrid-body');
                                    var bodyWidth = $($body).width();
                                    console.log('body width = ' + bodyWidth);
                                    var $header = $('.datagrid-view2 .datagrid-header-row');
                                    var headerWidth = $($header).width();
                                    console.log('header width = ' + headerWidth);
                                    var currentScrollLeft = $($body).scrollLeft();
                                    console.log('current scrollleft = ' + currentScrollLeft);
                                    console.log('current selection', annotation.currentSelection)
                                    var currentLength = annotation.columns.ni.width + ((annotation.currentSelection.end + 1) * 13);
                                    console.log('current length = ' + currentLength);
                                    if (currentLength > bodyWidth) {
                                        var rate = headerWidth / bodyWidth;
                                        console.log('rate = ' + rate);
                                        var offset = currentScrollLeft + Math.floor((currentLength - bodyWidth) * rate);
                                        console.log('offset = ' + offset);
                                        $($body).scrollLeft(offset)
                                    }
                                }
                            }
                        }
                        event.stopPropagation();
                        break;
                    }
                    case annotation.key.arrowUp: {
                        if (annotation.currentSelection.rowIndex > 1) {
                            rowIndex = annotation.currentSelection.rowIndex - 1;
                            prev = annotation.cursor.field;
                            field = 'wf' + prev;
                            if (typeof annotation.chars[field] != 'undefined') {
                                annotation.markSelection(rowIndex, field);
                            }
                        }
                        event.stopPropagation();
                        break;
                    }
                    case annotation.key.arrowDown: {
                        var rows = $('#dataGridLayers').datagrid("getRows");
                        var lastRow = rows.length - 1;
                        if (annotation.currentSelection.rowIndex < lastRow) {
                            rowIndex = annotation.currentSelection.rowIndex + 1;
                            prev = annotation.cursor.field;
                            field = 'wf' + prev;
                            if (typeof annotation.chars[field] != 'undefined') {
                                annotation.markSelection(rowIndex, field);
                            }
                        }
                        event.stopPropagation();
                        break;
                    }
                }
            }
        });


    });
</script>