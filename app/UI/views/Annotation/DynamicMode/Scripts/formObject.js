annotation.formObject = {
    onChangeFrame(record) {
        console.log('change frame', record)
        htmx.ajax('GET', '/annotation/dynamicMode/objectFE/' + record.idFrame, '#feContainer')
    }

}
