<?php
include_once(dirname(__FILE__) . '/google-widget/total-reviews.helper.php');
if(!class_exists('Total_Reviews_ShortCode')){

    class Total_Reviews_ShortCode {

        public $options;
        public $api_key;

        

        public function __construct(){

        }
        public static function google_badge($atts) {

            global $wpdb;

            $atts = shortcode_atts( array(
                'title'                => 'Google Rating',
                'place_name'           => '',
                'place_id'             => '',
                'text_size'            => '',
                'dark_theme'           => '',
                'view_mode'            => '',
                'open_link'            => true,
                'nofollow_link'        => true,
				'display_fixed'        => true,
                'reviews_lang'         => '',
                'position'             => 'left',
                'bottom'               => '',
            ), $atts, 'google_badge' );
            
            $title = $atts['title'];
            $text_size = $atts['text_size'];
            $dark_theme = $atts['dark_theme'];
            $place_id = $atts['place_id'];
            $reviews_lang = $atts['reviews_lang'];
            $position = $atts['position'];
            
            
            $reviews_where = '';
            if (strlen($reviews_lang) > 0) {
                $reviews_where = $reviews_where . ' AND language = \'' . $reviews_lang . '\'';
            }
            $place = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "total_reviews_google_place WHERE place_id = %s", $place_id));
            $reviews = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "total_reviews_google_review WHERE google_place_id = %d" . $reviews_where, $place->id));
            
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
            ?>
                   
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
                <a href="<?php echo $place->url ;?>" target="_blank">
                    <div class="total-reviews-badges-google wp-google-badge wp-badge-fixed_<?php echo $position; ?> ">
                        <div class="wp-google-border"></div>
                        <div class="wp-google-badge-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="44" width="44"><g fill="none" fill-rule="evenodd"><path d="M482.56 261.36c0-16.73-1.5-32.83-4.29-48.27H256v91.29h127.01c-5.47 29.5-22.1 54.49-47.09 71.23v59.21h76.27c44.63-41.09 70.37-101.59 70.37-173.46z" fill="#4285f4"/><path d="M256 492c63.72 0 117.14-21.13 156.19-57.18l-76.27-59.21c-21.13 14.16-48.17 22.53-79.92 22.53-61.47 0-113.49-41.51-132.05-97.3H45.1v61.15c38.83 77.13 118.64 130.01 210.9 130.01z" fill="#34a853"/><path d="M123.95 300.84c-4.72-14.16-7.4-29.29-7.4-44.84s2.68-30.68 7.4-44.84V150.01H45.1C29.12 181.87 20 217.92 20 256c0 38.08 9.12 74.13 25.1 105.99l78.85-61.15z" fill="#fbbc05"/><path d="M256 113.86c34.65 0 65.76 11.91 90.22 35.29l67.69-67.69C373.03 43.39 319.61 20 256 20c-92.25 0-172.07 52.89-210.9 130.01l78.85 61.15c18.56-55.78 70.59-97.3 132.05-97.3z" fill="#ea4335"/><path d="M20 20h472v472H20V20z"/></g></svg>
                            <div class="wp-google-badge-score">
                                <div><?php echo $title; ?></div>
                                <span class="wp-google-rating"><?php echo $rating; ?></span>
                                <span class="wp-google-stars"><?php total_reviews_stars($rating); ?></span>
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

            <?php
        }

        public static function facebook_badge( $atts, $content = "" ) {
            global $wpdb;
            
            $atts = shortcode_atts( array(
                'title'                => 'Facebook Rating',
                'page_name'           => '',
                'page_id'             => '',
                'text_size'            => '',
                'dark_theme'           => '',
                'view_mode'            => '',
                'open_link'            => true,
                'nofollow_link'        => true,
				'display_fixed'        => true,
                'position'             => 'left',
                'bottom'               => '',
            ), $atts, 'facebook_badge' );
            
            $title = $atts['title'];
            $text_size = $atts['text_size'];
            $dark_theme = $atts['dark_theme'];
            $page_id = $atts['page_id'];
            $position = $atts['position'];
            
            $page = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "total_reviews_facebook_page WHERE page_id = %s", $page_id));
            
            $rating = 0;
            if ($page->rating >= 0) {
                $rating = $page->rating;
            } 
            $rating = number_format((float)$rating, 1, '.', '');
            ?>
                    
            <div class="tr_fbwp wpac">
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
                <a href="<?php echo TOTAL_REVIEWS_FB . $page_id ;?>" target="_blank">
                    <div class="total-reviews-badges-facebook wp-facebook-badge wp-badge-fixed_<?php echo $position; ?> ">
                        <div class="wp-facebook-border"></div>
                        <div class="wp-facebook-badge-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="30" height="30" viewBox="0 0 100 100">                <g transform="translate(23,85) scale(0.05,-0.05)">                    <path fill="#fff" d="M959 1524v-264h-157q-86 0 -116 -36t-30 -108v-189h293l-39 -296h-254v-759h-306v759h-255v296h255v218q0 186 104 288.5t277 102.5q147 0 228 -12z"></path>                </g>            </svg>
                            <div class="wp-facebook-badge-score">
                                <div><?php echo $title ?></div>
                                <span class="wp-facebook-rating"><?php echo $rating; ?></span>
                                <span class="wp-facebook-stars"><?php total_reviews_stars($rating); ?></span>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="wp-facebook-form" style="display:none">
                    <div class="wp-facebook-head">
                        <div class="wp-facebook-head-inner">
                            <?php total_reviews_place($rating, $place, $reviews, $dark_theme, false); ?>
                        </div>
                        <button class="wp-facebook-close" type="button" onclick="this.parentNode.parentNode.style.display='none'">×</button>
                    </div>
                    <div class="wp-facebook-body"></div>
                    <div class="wp-facebook-content">
                        <div class="wp-facebook-content-inner">
                            <?php total_reviews_place_reviews($place, $reviews, $place_id, $text_size); ?>
                        </div>
                    </div>
                    <div class="wp-facebook-footer">
                        <!-- <img src="<?php //echo TOTAL_REVIEWS_PLUGIN_URL; ?>/assets/img/powered_by_google_on_<?php //if ($dark_theme) { ?>non_<?php //} ?>white.png" alt="powered by Google"> -->
                    </div>
                </div>
                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" onload="(function(el) { document.addEventListener('DOMContentLoaded', function() { /* total_reviews_badge_init(el); */ }); })(this.parentNode);" style="display:none">
            </div>

            <?php
        }
    }
}

?>
