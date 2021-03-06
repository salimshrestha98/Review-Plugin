jQuery(document). ready( function($) {

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

    // Adding Stars to User Reviews
    $(".user-ratings").each(function( index ) {
        var ratings = $(this).attr('data-ratings');
        for(var i=0; i<ratings; i++) {
            $(this).html( $(this).html() + '<span class="dashicons dashicons-star-filled text-warning"></span>')
        }
        for (var i=ratings; i<5; i++) {
            $(this).html( $(this).html() + '<span class="dashicons dashicons-star-empty text-warning"></span>')
        }
    });

    //  Fill first N stars
    function fillStars( num = currentStars ) {
        clearStarFills();

        for (var i=1; i<=num; i++) {
            $("#rp-star-"+i).addClass("dashicons-star-filled").removeClass("dashicons-star-empty");
        }
    }

    // Clear all Filled Stars 
    function clearStarFills() {
        $(".rp-filter-star").each( function() {
            $(this).removeClass("dashicons-star-filled");
            $(this).removeClass("dashicons-star-empty");
            $(this).addClass("dashicons-star-empty");
        });
    }

    //  Fetch reviews data via ajax and set the reviews grid
    function setReviews() {
        $.post( ajaxVars.ajaxPostUrl, {'action': 'rp_stars_filter', 'starsCount': currentStars, 'order': currentOrder, 'offset': currentOffset}, function(response,status) {
            rp_setReviewCards( response.reviews );
            rp_setPagination( response.totalReviews );
            console.log( response );
            fillStars();
        });
    }

    //  Build Pagination Buttons
    function rp_setPagination( totalReviews ) {
        var btnsContainer = $("#rp-page-btn-container").html("");
        while( totalReviews > 0) {
            btnsContainer.append($("#rp-page-btn-html").html());
            totalReviews -= 6;
        }
        var pageIndex = 0;
        $("#rp-page-btn-container button").each( function() {
            $(this).text(pageIndex+1);
            if ( pageIndex == currentOffset ) {
                $(this).addClass("active");
            }
            console.log( pageIndex, currentOffset );
            $(this).attr('data-index', pageIndex);
            pageIndex++;
        });

        $('.rp-page-btn').click( function() {
            currentOffset = $(this).attr('data-index');
            setReviews();
        });


        console.log( currentOffset );
    }

    //  Animate fill stars on hover
    $(".rp-filter-star").mouseenter( function() {
        var starIndex = $(this).attr('data-index');
        fillStars( starIndex );
    });

    $(".rp-filter-star").mouseleave( function() {
        fillStars();
    });

    //  Change reviews when clicked on a star filter
    $(".rp-filter-star").click( function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        currentStars = $(this).attr('data-index');
        currentOffset = 0;

        setReviews();
    });

    //  Change reviews when user selects different view order
    $("#rp-sort-select").change( function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        currentOrder = $("#rp-sort-select option:selected").val();
        currentOffset = 0;

        setReviews();
    });

    //  Add Card Grid to the DOM
    function rp_setReviewCards( response ) {
        var reviewCount = response.length;
        var baseEl = $('#rp-card-template');
        var cardTemplate = baseEl.html();

        $('#cards-container').html("");

        if (!reviewCount) {
            $('#cards-container').append("<h5 class='p-5'>Sorry! No reviews found.</h5>");
            return;
        }

        //  Adding card templates to the DOM
        for( var i=0; i<reviewCount; i++) {
            $('#cards-container').append(cardTemplate);
        }

        var cardsFullName = $('.rp-card-item .rp-user-full-name');
        var cardsReviewText = $('.rp-card-item .rp-user-review-text');
        var cardsRatings = $('.rp-card-item .rp-user-ratings');

        //  Filling card contents
        for( var i=0; i<reviewCount; i++) {
            var cardData = response[i];
            $(cardsFullName[i]).text(cardData.fullName);
            $(cardsReviewText[i]).text(cardData.reviewText);

            //  Creating stars
            var ratings = cardData.rating;
            for(var j=0; j<ratings; j++) {
                $(cardsRatings[i]).html( $(cardsRatings[i]).html() + '<span class="dashicons dashicons-star-filled text-warning"></span>')
            }
            for (var j=ratings; j<5; j++) {
                $(cardsRatings[i]).html( $(cardsRatings[i]).html() + '<span class="dashicons dashicons-star-empty text-warning"></span>')
            }
        }
    }

    // Initial States
    var currentStars = 0;
    var currentOrder = null;
    var currentOffset = 0;
    currentPage = 0;

    // Set Reviews Grid once when page loads
    setReviews();
});