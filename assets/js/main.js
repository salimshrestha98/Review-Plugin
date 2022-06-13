$ = jQuery;

$(document). ready( function() {
    $('#rp-review-form').submit( (e) => {
        e.preventDefault();
        e.stopPropagation();

        var formData = $('#rp-review-form').serialize();
        console.log( formData );
        $.post( ajaxVars.ajaxPostUrl, {'action': 'rp_form_response', 'data': formData}, function(response, status) {
            console.log( response );
            msg = response.messages;
            $('#alerts-box').html("");
            if ( msg ) {
                for (var i=0; i<msg.length; i++) {
                    $('#alerts-box').html($('#alerts-box').html() + "<p class='alert alert-success'>" + msg[i] + "</p>");
                    console.log(msg[i]);
                }
            }
            errors = response.errorMsgs;
            console.log(errors);
            if (errors) {
                for(var i=0; i<errors.length; i++) {
                    $('#alerts-box').html($('#alerts-box').html() + "<p class='alert alert-danger'>" + errors[i] + "</p>");
                    console.log(errors[i]);
                }
            }
        });
    });

    console.log( ajaxVars );
});