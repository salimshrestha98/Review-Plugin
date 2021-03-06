<div class="wrap mt-5 p-5" style="background: #cccccc">
	<div id="alerts-box"></div>
	
	<form action="<?php echo esc_url( RP_AJAX_POST_URL ); ?>" id="rp-review-form" method="POST">
	
	<input type="hidden" name="rp-nonce" value="<?php esc_html( wp_create_nonce( 'rp-nonce' ) ); ?>" >
	<div class="form-row">
	  <div class="form-group col-md-6">
		<label for="inputFName"><?php esc_html_e( 'First Name', RP_TEXT_DOMAIN ); ?></label>
		  <input type="text" class="form-control" id="inputFName" name="rp-fname">
	  </div>
	
	  <div class="form-group col-md-6">
		<label for="inputLName"><?php esc_html_e( 'Last Name', RP_TEXT_DOMAIN ); ?></label>
		  <input type="text" class="form-control" id="inputLName" name="rp-lname">
	  </div>
	</div>
	
	<div class="form-row">
		<div class="form-group col-md-6">
			<label for="inputEmail"><?php esc_html_e( 'Email', RP_TEXT_DOMAIN ); ?></label>
			<input type="email" class="form-control" id="inputEmail" name="rp-email">
		</div>
		<div class="form-group col-md-6">
			<label for="inputPassword"><?php esc_html_e( 'Password', RP_TEXT_DOMAIN ); ?></label>
			<input type="password" class="form-control" id="inputPassword" name="rp-pass">
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputReviewText"><?php esc_html_e( 'Product Review', RP_TEXT_DOMAIN ); ?></label>
		<textarea name="rp-review-text" id="inputReviewText" cols="30" rows="10" maxlength="300" placeholder="<?php esc_attr_e( 'Enter your review here ..' ); ?>"></textarea>
	</div>
	
	<div class="form-group">
		<div class="row">
			<label for="inputRatings" class="col-md-3"><?php esc_html_e( 'Ratings', RP_TEXT_DOMAIN ); ?> (1 - 5)</label>
			<input type="range" class="col-md-3 form-range" name="rp-rating" id="inputRatings" min="1" max="5" step="1">
		</div>
	
	</div>
	
	  <div class="form-group mt-5 mb-0 pb-0">
		  <button type="submit" class="btn btn-primary" name="rp-submit" id="rp-submit-btn"><?php esc_html_e( 'Send Review', RP_TEXT_DOMAIN ); ?></button>
	  </div>
	</form>  
</div>
