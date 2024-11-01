<?php
wp_register_script('rplg_js', plugin_dir_url( __FILE__ ) . '../assets/js/rplg.js');
wp_enqueue_script('rplg_js', plugin_dir_url( __FILE__ ) . '../assets/js/rplg.js');

include_once(dirname(__FILE__) . '/total-reviews.helper.php');

$reviews_where = '';
if (strlen($reviews_lang) > 0) {
    $reviews_where = $reviews_where . ' AND language = \'' . $reviews_lang . '\'';
}

	
	// cache 
	$response_cache_key = 'total_reviews_google_' . $place_id;
    $options_cache_key = 'total_reviews_google_option_' . $place_id;
	
	$api_response = get_transient($response_cache_key);
    $widget_options = get_transient($options_cache_key); 
	
    $serialized_instance = serialize($instance);	
	//print_r($instance);
	// check cache 
	if ($api_response === false || $serialized_instance !== $widget_options || true) {

        $expiration = $cache;
        switch ($expiration) {
            case '1':
                $expiration = 3600;
                break;
            case '3':
                $expiration = 3600 * 3;
                break;
            case '6':
                $expiration = 3600 * 6;
                break;
            case '12':
                $expiration = 3600 * 12;
                break;
            case '24':
                $expiration = 3600 * 24;
                break;
            case '48':
                $expiration = 3600 * 48;
                break;
            case '168':
                $expiration = 3600 * 168;
                break;
            default:
                $expiration = 3600 * 24;
        }
		
			/*
				update 
			*/
			 $placeid = $place_id;
			$url = total_reviews_api_url($placeid);
			//print_r($url);
			$response = rplg_urlopen($url);
			//print_r($response);
			$response_data = $response['data'];
			$response_json = rplg_json_decode($response_data);

			if ( isset( $response_json->result ) && $response_json->result) {
				total_reviews_save_reviews($response_json->result);
				 $status = 'success';
			} else {
				 $status = 'failed';
			} 
        $place = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "total_reviews_google_place WHERE place_id = %s", $place_id));
		
		
		$reviews = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "total_reviews_google_review WHERE google_place_id = %d" . $reviews_where, $place->id));
		
		
        $api_response = array('place' => $place,'reviews' => $reviews);
        set_transient($response_cache_key, $api_response, $expiration);
        set_transient($options_cache_key, $serialized_instance, $expiration);
		//print_r($api_response);
    } else {
		$place = $api_response['place'];
		$reviews =  $api_response['reviews'];;
	}

$rating = 0;
if ($place->rating > 0) {
    $rating = $place->rating;
} else if (count($reviews) > 0) {
    foreach ($reviews as $review) {
        $rating = $rating + $review->rating;
    }
    $rating = round($rating / count($reviews), 1);
}
$rating = number_format((float)$rating, 1, '.', '');
//print_r($reviews);
?>

<?php if ($view_mode != 'list') { ?>

<div class="tr_ggwp wpac">
    <script type="text/javascript">
    function total_reviews_badge_init(el) {
        var btn = el.querySelector('.wp-google-badge'),
            form = el.querySelector('.wp-google-form');

        var wpac = document.createElement('div');
        wpac.className = 'tr_ggwp wpac';
        wpac.appendChild(form);
        document.body.appendChild(wpac);

        btn.onclick = function() {
            form.style.display='block';
        };
    }
    </script>
	 
    <a href="<?php echo $place->url ;?>" <?php if($open_link == 1) { echo 'target="_blank"'; }?> <?php if($nofollow_link == 1) { echo 'rel="nofollow"'; } ?>  >
        <div class="total-reviews-badges-google wp-google-badge <?php  if($display_fixed == 1){ echo 'display-fixed '; echo $view_mode == 'badge_left' ? 'wp-badge-fixed_left' : ''; echo $view_mode == 'badge_right' ? 'wp-badge-fixed_right' : ''; } ?>">
            <div class="wp-google-border"></div>
            <div class="wp-google-badge-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="44" width="44"><g fill="none" fill-rule="evenodd"><path d="M482.56 261.36c0-16.73-1.5-32.83-4.29-48.27H256v91.29h127.01c-5.47 29.5-22.1 54.49-47.09 71.23v59.21h76.27c44.63-41.09 70.37-101.59 70.37-173.46z" fill="#4285f4"/><path d="M256 492c63.72 0 117.14-21.13 156.19-57.18l-76.27-59.21c-21.13 14.16-48.17 22.53-79.92 22.53-61.47 0-113.49-41.51-132.05-97.3H45.1v61.15c38.83 77.13 118.64 130.01 210.9 130.01z" fill="#34a853"/><path d="M123.95 300.84c-4.72-14.16-7.4-29.29-7.4-44.84s2.68-30.68 7.4-44.84V150.01H45.1C29.12 181.87 20 217.92 20 256c0 38.08 9.12 74.13 25.1 105.99l78.85-61.15z" fill="#fbbc05"/><path d="M256 113.86c34.65 0 65.76 11.91 90.22 35.29l67.69-67.69C373.03 43.39 319.61 20 256 20c-92.25 0-172.07 52.89-210.9 130.01l78.85 61.15c18.56-55.78 70.59-97.3 132.05-97.3z" fill="#ea4335"/><path d="M20 20h472v472H20V20z"/></g></svg>
                <div class="wp-google-badge-score">
                    <div><?php echo total_reviews_text('Google Rating'); ?></div>
                    <span class="wp-google-rating"><?php echo $rating; ?></span>
                    <span class="wp-google-stars"><?php total_reviews_stars($rating); ?></span>
					<?php if($display_reviews == 1){ ?>
						<?php
							$total_reviews = count($reviews);
							if($total_reviews > 5){
								$total_reviews = '5+';
							}
						?>
						<br><span><?php echo $total_reviews;  if(count($reviews) > 1) { echo total_reviews_text(' Reviews'); } else { echo total_reviews_text(' Review'); };  ?></span>
					<?php } ?>
                </div>
            </div>
        </div>
    </a>
    <div class="wp-google-form" style="display:none">
        <div class="wp-google-head">
            <div class="wp-google-head-inner">
                <?php total_reviews_place($rating, $place, $reviews, $dark_theme, false); ?>
            </div>
            <button class="wp-google-close" type="button" onclick="this.parentNode.parentNode.style.display='none'">×</button>
        </div>
        <div class="wp-google-body"></div>
        <div class="wp-google-content">
            <div class="wp-google-content-inner">
                <?php total_reviews_place_reviews($place, $reviews, $place_id, $text_size); ?>
            </div>
        </div>
        <div class="wp-google-footer">
            <img src="<?php echo TOTAL_REVIEWS_PLUGIN_URL; ?>/assets/img/powered_by_google_on_<?php if ($dark_theme) { ?>non_<?php } ?>white.png" alt="powered by Google">
        </div>
    </div>
    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" onload="(function(el) { document.addEventListener('DOMContentLoaded', function() { /* total_reviews_badge_init(el); */ }); })(this.parentNode);" style="display:none">
</div>

<?php } else { ?>

<div class="tr_ggwp wpac">
    <div class="wp-google-list<?php if ($dark_theme) { ?> wp-dark<?php } ?>">
        <div class="wp-google-place">
            <?php total_reviews_place($rating, $place, $reviews, $dark_theme); ?>
        </div>
        <div class="wp-google-content-inner">
            <?php total_reviews_place_reviews($place, $reviews, $place_id, $text_size); ?>
        </div>
    </div>
</div>
<?php } ?>
