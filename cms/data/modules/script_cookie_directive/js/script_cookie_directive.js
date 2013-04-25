$(function() {

    /**
     * Open cookie note dialog when present.
     */
    $("#cookie_note").dialog({
        height : 300,
        width : 350,
        modal : true,
        closeOnEscape : false,
        open: function(event, ui) {
            // hide close icon in upper right corner
            $(this).closest('.ui-dialog').find('.ui-dialog-titlebar-close').hide();
        },
//        close: function(event, ui) {
//            // fade dialog out
//            $(this).fadeOut();
//        },
        buttons : [{
            text : $('#accept').val(),
            "class": "submit button red",
            click : function() {
                window.location.href = $('#page_url_accept').val();
                $(this).dialog("close");
            }
        },
        {
            text : $('#decline').val(),
            "class": "cancel button grey",
            click : function() {
                window.location.href = $('#page_url_deny').val();
                $(this).dialog("close");
            }
        }]
    });

});
