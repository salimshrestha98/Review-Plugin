<div class="wrap mt-5 p-5" style="background: #cccccc">

<form action="<?= esc_url( admin_url( 'admin-post.php' ) ); ?>" id="rp-review-form">

<input type="hidden" name="action" value="rp_form_response">
<input type="hidden" name="rp-nonce" value="<?= wp_create_nonce( 'rp-nonce' ); ?>" >
<div class="form-row">
  <div class="form-group col-md-6">
    <label for="inputFName">First Name</label>
      <input type="text" class="form-control" id="inputFName" name="rp-fname">
  </div>

  <div class="form-group col-md-6">
    <label for="inputLName">Last Name</label>
      <input type="text" class="form-control" id="inputLName" name="rp-lname">
  </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="inputEmail">Email</label>
        <input type="email" class="form-control" id="inputEmail" name="rp-email">
    </div>
    <div class="form-group col-md-6">
        <label for="inputPassword">Password</label>
        <input type="password" class="form-control" id="inputPassword" name="rp-pass">
    </div>
</div>

<div class="form-group">
    <label for="inputReviewText">Product Review</label>
    <textarea name="rp-review-text" id="inputReviewText" cols="30" rows="10" placeholder="Enter your review here .."></textarea>
</div>

<div class="form-group">
    <div class="row">
        <label for="inputRatings" class="col-md-3">Ratings (0 - 5)</label>
        <input type="range" class="col-md-3 form-range" name="rp-rating" id="inputRatings" min="0" max="5" step="1">
    </div>

</div>

  <div class="form-group mt-5 mb-0 pb-0">
      <button type="submit" class="btn btn-primary" name="rp-submit" id="rp-submit-btn">Send Review</button>
  </div>
</form>

</div>