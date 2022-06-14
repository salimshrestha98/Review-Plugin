$ = jQuery;

$(document). ready( function() {
    $('#rp-review-form').submit( (e) => {
        e.preventDefault();
        e.stopPropagation();

        $('#alerts-box').html("");

        var formData = $('#rp-review-form').serialize();
        console.log( formData );
        $.post( ajaxVars.ajaxPostUrl, {'action': 'rp_form_response', 'data': formData}, function(response, status) {
            console.log( response );
            msg = response.messages;
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

    $(".user-ratings").each(function( index ) {
        var ratings = $(this).attr('data-ratings');
        for(var i=0; i<ratings; i++) {
            $(this).html( $(this).html() + '<span class="dashicons dashicons-star-filled text-warning"></span>')
        }
        for (var i=ratings; i<5; i++) {
            $(this).html( $(this).html() + '<span class="dashicons dashicons-star-empty text-warning"></span>')
        }
    });
});