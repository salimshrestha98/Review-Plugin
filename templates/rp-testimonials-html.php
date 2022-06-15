<div class="wrap">
<nav class="bg-light px-5 py-2 border">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            Filter: 
            <span class="dashicons dashicons-star-empty text-warning rp-filter-star" id="rp-star-1" data-index=1></span>
            <span class="dashicons dashicons-star-empty text-warning rp-filter-star" id="rp-star-2" data-index=2></span>
            <span class="dashicons dashicons-star-empty text-warning rp-filter-star" id="rp-star-3" data-index=3></span>
            <span class="dashicons dashicons-star-empty text-warning rp-filter-star" id="rp-star-4" data-index=4></span>
            <span class="dashicons dashicons-star-empty text-warning rp-filter-star" id="rp-star-5" data-index=5></span>
        </div>

        <div class="col-md-4">
            <select class="form-control" id="rp-sort-select">
                <option value="" disabled selected><?php echo esc_html( '-- Sort By --', RP_TEXT_DOMAIN ); ?></option>
                <option value="DESC" class="rp-sort-option" onhover="setOption"><?php echo esc_html( 'Newest First', RP_TEXT_DOMAIN ); ?></option>
                <option value="ASC" class="rp-sort-option" onhover="setOption"><?php echo esc_html( 'Oldest First', RP_TEXT_DOMAIN ); ?></option>
            </select>
        </div>
    </div>
</nav>
<div id="cards-container" class="row">
    <?php echo esc_html( 'Loading...', RP_TEXT_DOMAIN ); ?>
</div>

<div id="rp-page-btn-html"  class="d-none">
    <button type="button" class="btn btn-secondary rp-page-btn"></button>
</div>

<div class="d-none" id="rp-card-template">
    <div class="col-md-6 my-3 rp-card-item">
        <div class="card" style="height: 320px">
            <div class="card-header bg-dark text-white">
                <span class="rp-user-full-name"></span>
                <div class="rp-user-ratings float-right" data-ratings=""></div>
            </div>
            <div class="card-body">
                <strong><em><span class="rp-user-review-text"></span></em></strong>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <div class="btn-group" role="group" aria-label="Basic example" id="rp-page-btn-container">

    </div>
</div>

</div>