<!-- 1. Find Place -->
<div class="form-group">
    <div class="col-sm-12">
        <h4 class="text-left"><span class="badge">1</span> <?php echo total_reviews_text('Find the Place'); ?></h4>
        <a href="http://www.shounakgupte.com/find-google-places-id/?utm_source=total_reviews" target="_blank">How to Find Google Place ID?</a>
        <input type="text" class="total_reviews-place-search form-control" value="" placeholder="Enter name, address, or Google Place ID" />
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12">
        <button class="total_reviews-search-btn btn btn-block btn-primary"><?php echo total_reviews_text('Search Place'); ?></button>
    </div>
</div>
<!-- 2. Select Place -->
<div class="form-group">
    <div class="col-sm-12">
        <h4 class="text-left"><span class="badge">2</span> <?php echo total_reviews_text('Select the Place'); ?></h4>
        <div class="total-reviews-places"></div>
    </div>
</div>
<!-- 3. Save Reviews -->
<div class="form-group">
    <div class="col-sm-12">
        <!-- <h4 class="text-left"><span class="find-step">3</span><?php //echo total_reviews_text('Save Place and Reviews'); ?></h4> -->
        <div class="total-reviews-reviews"></div>
        <!-- <div class="total-reviews-five-reviews-note" style="display:none"><?php echo total_reviews_text('Google returns 5 reviews only'); ?></div> -->
        <div class="total-reviews-save-reviews-container"></div>
    </div>
</div>