var manager = {
    messager(type, message) {
        console.log('hhhhhhh');

        $.notify.show({
            cls: 'wt-messager wt-messager-' + type,
            title: null,//type.charAt(0).toUpperCase() + type.slice(1),
            msg: message,
            timeout: 0,
            showType: 'show',
            style: {
                right: '',
                top: document.body.scrollTop + document.documentElement.scrollTop,
                bottom: ''
            }
        });
    },
    confirmPost(type, message, action) {
        $.messager.confirm({
            cls: 'wt-messager wt-messager-' + type,
            title: type.charAt(0).toUpperCase() + type.slice(1),
            msg: message,
            fn: function(r){
                if (r){
                    console.log('confirmed: '+r);
                }
            }
        });
    },
    confirmDelete(message, action, event) {
        $.messager.confirm({
            cls: 'wt-messager-confirm wt-messager-warning',
            title: 'Warning',
            msg: message,
            fn: function(r){
                if (r){
                    htmx.ajax('DELETE', action);
                    if (event) {
                        $("#" + event[0]).trigger(event[1]);
                    }
                }
            }
        });
    }
}
