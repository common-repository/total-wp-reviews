<button class="total-reviews-fbconnect"><?php echo total_reviews_text('Connect to Facebook'); ?></button>

<div class="total-reviews-fbpages"></div>

<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('page_name'); ?>" name="<?php echo $this->get_field_name('page_name'); ?>" value="<?php echo $page_name; ?>" class="form-control total-reviews-fbpage-name" placeholder="<?php echo total_reviews_text('Page Name'); ?>" readonly />
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('page_id'); ?>" name="<?php echo $this->get_field_name('page_id'); ?>" value="<?php echo $page_id; ?>" class="form-control total-reviews-fbpage-id" placeholder="<?php echo total_reviews_text('Page ID'); ?>" readonly />
    </div>
</div>

<input type="hidden" id="<?php echo $this->get_field_id('page_access_token'); ?>" name="<?php echo $this->get_field_name('page_access_token'); ?>" value="<?php echo $page_access_token; ?>" class="form-control total-reviews-fbpage-token" placeholder="<?php echo total_reviews_text('Access token'); ?>" readonly />

<?php if (isset($title)) { ?>
<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo total_reviews_text('Widget title'); ?>" />
    </div>
</div>
<?php } ?>


<!-- Display Options -->
<h4 class="rplg-options-toggle"><?php echo total_reviews_text('Display Options'); ?></h4>
<div class="rplg-options" style="display:none">
    
    <div class="form-group">
        <div class="col-sm-12">
            <?php echo total_reviews_text('Widget theme'); ?>
            <select id="<?php echo $this->get_field_id('view_mode'); ?>" name="<?php echo $this->get_field_name('view_mode'); ?>" class="form-control"> 
                <option value="badge_right" <?php selected('badge_right', $view_mode); ?> ><?php echo total_reviews_text('Right'); ?></option>
                <option value="badge_left" <?php selected('badge_left', $view_mode); ?> ><?php echo total_reviews_text('Left'); ?></option>
            </select>
        </div>
    </div>
	<div class="form-group">
        <div class="col-sm-12">
            <label>
                <input id="<?php echo $this->get_field_id('display_reviews'); ?>" name="<?php echo $this->get_field_name('display_reviews'); ?>" type="checkbox" value="1" <?php checked('1', $display_reviews); ?> class="form-control" />
                <?php echo total_reviews_text('Display the total reviews'); ?>
            </label>
        </div>
    </div>
	   <div class="form-group">
        <div class="col-sm-12">
            <label>
                <input id="<?php echo $this->get_field_id('open_link'); ?>" name="<?php echo $this->get_field_name('open_link'); ?>" type="checkbox" value="1" <?php checked('1', $open_link); ?> class="form-control" />
                <?php echo total_reviews_text('Open links in new Window'); ?>
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label>
                <input id="<?php echo $this->get_field_id('nofollow_link'); ?>" name="<?php echo $this->get_field_name('nofollow_link'); ?>" type="checkbox" value="1" <?php checked('1', $nofollow_link); ?> class="form-control" />
                <?php echo total_reviews_text('User no follow links'); ?>
            </label>
        </div>
    </div>
	<div class="form-group">
        <div class="col-sm-12">
            <label>
                <input id="<?php echo $this->get_field_id('display_fixed'); ?>" name="<?php echo $this->get_field_name('display_fixed'); ?>" type="checkbox" value="1" <?php checked('1', $display_fixed); ?> class="form-control" />
                <?php echo total_reviews_text('Display fixed'); ?>
            </label>
        </div>
    </div>
    <?php if (isset($max_width)) { ?>
    <div class="form-group">
        <div class="col-sm-12">
            <label for="<?php echo $this->get_field_id('max_width'); ?>"><?php echo total_reviews_text('Maximum width'); ?></label>
            <input id="<?php echo $this->get_field_id('max_width'); ?>" name="<?php echo $this->get_field_name('max_width'); ?>" class="form-control" type="text" placeholder="for instance: 300px" />
        </div>
    </div>
    <?php } ?>
    <?php if (isset($max_height)) { ?>
    <div class="form-group">
        <div class="col-sm-12">
            <label for="<?php echo $this->get_field_id('max_height'); ?>"><?php echo total_reviews_text('Maximum height'); ?></label>
            <input id="<?php echo $this->get_field_id('max_height'); ?>" name="<?php echo $this->get_field_name('max_height'); ?>" class="form-control" type="text" placeholder="for instance: 500px" />
        </div>
    </div>
    <?php } ?>
</div>


<!-- Advance Options -->
<h4 class="rplg-options-toggle"><?php echo total_reviews_text('Advance Options'); ?></h4>
<div class="rplg-options" style="display:none">
 
    <div class="form-group">
        <div class="col-sm-12">
            <?php echo total_reviews_text('Cache data'); ?>
            <select id="<?php echo $this->get_field_id('cache'); ?>" name="<?php echo $this->get_field_name('cache'); ?>" class="form-control">
                <option value="1" <?php selected('1', $cache); ?>><?php echo total_reviews_text('1 Hour'); ?></option>
                <option value="3" <?php selected('3', $cache); ?>><?php echo total_reviews_text('3 Hours'); ?></option>
                <option value="6" <?php selected('6', $cache); ?>><?php echo total_reviews_text('6 Hours'); ?></option>
                <option value="12" <?php selected('12', $cache); ?>><?php echo total_reviews_text('12 Hours'); ?></option>
                <option value="24" <?php selected('24', $cache); ?>><?php echo total_reviews_text('1 Day'); ?></option>
                <option value="48" <?php selected('48', $cache); ?>><?php echo total_reviews_text('2 Days'); ?></option>
                <option value="168" <?php selected('168', $cache); ?>><?php echo total_reviews_text('1 Week'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label><?php echo total_reviews_text('Facebook Page Ratings API limit'); ?></label>
            <input id="<?php echo $this->get_field_id('api_ratings_limit'); ?>" name="<?php echo $this->get_field_name('api_ratings_limit'); ?>" value="<?php echo $api_ratings_limit; ?>" type="text" placeholder="By default: <?php echo TOTAL_REVIEWS_API_RATINGS_LIMIT; ?>" class="form-control"/>
        </div>
    </div>
</div>
