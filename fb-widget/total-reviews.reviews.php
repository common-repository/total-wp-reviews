<?php
wp_register_script('rplg_js', plugin_dir_url( __FILE__ ) . '../assets/js/rplg.js');
wp_enqueue_script('rplg_js', plugin_dir_url( __FILE__ ) . '../assets/js/rplg.js');

include_once(dirname(__FILE__) . '/total-reviews.helper.php');

$rating = 0;
if (count($reviews) > 0) {
    foreach ($reviews as $review) {
		if( isset( $review->rating ) ) {
			$rating = $rating + $review->rating;
		}
    }
    $rating = round($rating / count($reviews), 1);
    $rating = number_format((float)$rating, 1, '.', '');
}
 $rating = $overall_star_rating;
?>


<?php if ($view_mode != 'list') { ?>

    <div class="tr_fbwp wpac">
    <script type="text/javascript">
    function total_reviews_badge_init(el) {
        var btn = el.querySelector('.wp-google-badge'),
            form = el.querySelector('.wp-google-form');

        var wpac = document.createElement('div');
        wpac.className = 'tr_fbwp wpac';
        wpac.appendChild(form);
        document.body.appendChild(wpac);

        btn.onclick = function() {
            form.style.display='block';
        };
    }
    </script>
    <a href="<?php echo TOTAL_REVIEWS_FB . $page_id ;?>" <?php if($open_link == 1) { echo 'target="_blank"'; }?> <?php if($nofollow_link == 1) { echo 'rel="nofollow"'; } ?> >
        <div class="total-reviews-badges-facebook wp-facebook-badge <?php  if($display_fixed == 1){ echo 'display-fixed '; echo $view_mode == 'badge_left' ? 'wp-badge-fixed_left' : ''; echo $view_mode == 'badge_right' ? 'wp-badge-fixed_right' : ''; } ?>">
            <div class="wp-facebook-border"></div>
            <div class="wp-facebook-badge-btn">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="30" height="30" viewBox="0 0 100 100">                <g transform="translate(23,85) scale(0.05,-0.05)">                    <path fill="#fff" d="M959 1524v-264h-157q-86 0 -116 -36t-30 -108v-189h293l-39 -296h-254v-759h-306v759h-255v296h255v218q0 186 104 288.5t277 102.5q147 0 228 -12z"></path>                </g>            </svg>
                <div class="wp-facebook-badge-score">
                    <div><?php echo total_reviews_text('Facebook Rating'); ?></div>
                    <span class="wp-facebook-rating"><?php echo $rating; ?></span>
                    <span class="wp-facebook-stars"><?php total_reviews_stars($rating); ?></span>
					<?php if($display_reviews == 1){ ?>							
						<br><span><?php echo $total_all_reviews;  if($total_all_reviews > 1) { echo total_reviews_text(' Reviews'); } else { echo total_reviews_text(' Review'); };  ?></span>
					<?php } ?>
                </div>
            </div>
        </div>
    </a>
    <div class="wp-facebook-form" style="display:none">
        <div class="wp-facebook-head">
            <div class="wp-facebook-head-inner">
                <?php //total_reviews_place($rating, $place, $reviews, $dark_theme, false); ?>
            </div>
            <button class="wp-facebook-close" type="button" onclick="this.parentNode.parentNode.style.display='none'">×</button>
        </div>
        <div class="wp-facebook-body"></div>
        <div class="wp-facebook-content">
            <div class="wp-facebook-content-inner">
                <?php //total_reviews_place_reviews($place, $reviews, $place_id, $text_size); ?>
            </div>
        </div>
        <div class="wp-facebook-footer">
            <!-- <img src="<?php //echo TOTAL_REVIEWS_PLUGIN_URL; ?>/assets/img/powered_by_google_on_<?php //if ($dark_theme) { ?>non_<?php //} ?>white.png" alt="powered by Google"> -->
        </div>
    </div>
    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" onload="(function(el) { document.addEventListener('DOMContentLoaded', function() { /* total_reviews_badge_init(el); */ }); })(this.parentNode);" style="display:none">
</div>

<?php } else { ?>
<div class="tr_fbwp wpac">
    <div class="wp-facebook-list<?php if ($dark_theme) { ?> wp-dark<?php } ?>">
        <div class="wp-facebook-place">
            <?php total_reviews_facebook_page($page_id, $page_name, $rating, $reviews, $open_link, $nofollow_link); ?>
        </div>
        <div class="wp-facebook-content-inner">
            <?php total_reviews_facebook_reviews($page_id, $reviews, $open_link, $nofollow_link,  $display_fixed); ?>
        </div>
    </div>
</div>
<?php } ?>

