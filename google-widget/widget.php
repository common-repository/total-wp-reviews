<?php
if(!class_exists('Total_Reviews_Google_Widget')){
    
    class Total_Reviews_Google_Widget extends WP_Widget {

        public $options;
        public $api_key;

        public $widget_fields = array(
            'title'                => '',
            'place_name'           => '',
            'place_id'             => '',
            'text_size'            => '',
            'dark_theme'           => '',
            'view_mode'            => '',
            'display_reviews'            => true,
            'open_link'            => true,
            'nofollow_link'        => true,
			'display_fixed'        => true,
			'cache'                => '24',
            'reviews_lang'         => '',
        );

        public function __construct() {
            parent::__construct(
                'total_reviews_google_widget',
                'Total Reviews Google Widget',
                array(
                    'classname'   => 'total-reviews-google-widget',
                    'description' => total_reviews_text('Display Google Places Reviews on your website.', 'total_reviews')
                )
            );


            wp_register_script('wpac_time_js', plugin_dir_url( __FILE__ ) . '../assets/js/wpac-time.js');
            wp_enqueue_script('wpac_time_js', plugin_dir_url( __FILE__ ) .'../assets/js/wpac-time.js');

            wp_register_style('total_reviews_widget_css', plugin_dir_url( __FILE__ ) .'../assets/css/total-reviews.min.css');
            wp_enqueue_style('total_reviews_widget_css', plugin_dir_url( __FILE__ ) .'../assets/css/total-reviews.min.css');
        }

        
        function widget($args, $instance) {
            global $wpdb;

            if (total_reviews_enabled()) {
                extract($args);
                foreach ($this->widget_fields as $variable => $value) {
                    ${$variable} = !isset($instance[$variable]) ? $this->widget_fields[$variable] : esc_attr($instance[$variable]);
                }

                echo $before_widget;
                if ($place_id) {
                    if ($title) { ?><h2 class="total_reviews-widget-title widget-title"><?php echo $title; ?></h2><?php }
                    include(dirname(__FILE__) . '/total-reviews.reviews.php');
                    if ($view_mode == 'badge') {
                        ?>
                        <style>
                        #<?php echo $this->id; ?> {
                        margin: 0;
                        padding: 0;
                        border: none;
                        }
                        </style>
                        <?php
                    }
                } else { ?>
                    <div class="total-reviews-error" style="padding:10px;color:#B94A48;background-color:#F2DEDE;border-color:#EED3D7;">
                        <?php echo total_reviews_text('Please check that this widget <b>Google Reviews</b> has a Google Place ID set.'); ?>
                    </div>
                <?php }
                echo $after_widget;
            }
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            foreach ($this->widget_fields as $field => $value) {
                $instance[$field] = strip_tags(stripslashes($new_instance[$field]));
            }
            return $instance;
        }

        function form($instance) {
            global $wp_version;
            foreach ($this->widget_fields as $field => $value) {
                if (array_key_exists($field, $this->widget_fields)) {
                    ${$field} = !isset($instance[$field]) ? $value : esc_attr($instance[$field]);
                }
            }

            wp_nonce_field('total_reviews_wpnonce', 'total_reviews_nonce');

            $total_reviews_google_api_key = get_option('total_reviews_google_api_key');
            if ($total_reviews_google_api_key) {
                
                ?>
                <div id="<?php echo $this->id; ?>" class="rplg-widget"><?php
                    if (!$place_id) {
                        include(dirname(__FILE__) . '/total-reviews.finder.php');
                    } else { ?>
                        <script type="text/javascript">
                            jQuery('.total_reviews-tooltip').remove();
                        </script> <?php
                    }
                    include(dirname(__FILE__) . '/total-reviews.options.php'); ?>
                </div>
                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-widget-id="<?php echo $this->id; ?>"
                    onload="total_reviews_init({widgetId: this.getAttribute('data-widget-id')})" style="display:none">
                <?php
            } else {
                ?>
                <h4 class="text-left"><?php echo total_reviews_text('First configure Google API Key'); ?></h4>
                <ul style="line-height:20px">
                    <li>
                        <span class="total_reviews-step">1</span>
                        <?php echo total_reviews_text('Visit the Google Places API Web Service page. If you are not logged into your Google Account already, you can do so there.'); ?>
                        <a href="https://developers.google.com/places/web-service/get-api-key" target="_blank">
                            <?php echo total_reviews_text('Google Places API Key'); ?>
                        </a>
                    </li>
                    <li>
                        <span class="total_reviews-step">2</span>
                        <?php echo total_reviews_text('Click on the “Get a Key” button in the top-right of the screen.'); ?>
                    </li>
                    <li>
                        <span class="total_reviews-step">3</span>
                        <?php echo total_reviews_text('You’ll be presented with a modal which allows you to choose an existing API Project, or create a new one. Do either, then click on “Next”.'); ?>
                    </li>
                    <li>
                        <span class="total_reviews-step">4</span>
                        <?php echo total_reviews_text('Copy key YOUR API KEY and paste it in the box below.'); ?>
                        <input type="text" class="total_reviews-apikey" name="total_reviews_google_api_key" placeholder="<?php echo total_reviews_text('Google Places API Key'); ?>" />
                    </li>
                    <li>
                        <span class="total_reviews-step">5</span>
                        <?php echo total_reviews_text('Save the widget'); ?>
                    </li>
                </ul>
                <script type="text/javascript">
                    var apikey = document.querySelectorAll('.total_reviews-apikey');
                    if (apikey) {
                        WPacFastjs.onall(apikey, 'change', function() {
                            if (!this.value) return;
                            jQuery.post('<?php echo admin_url('options-general.php?page=total_reviews'); ?>&cf_action=' + this.getAttribute('name'), {
                                key: this.value,
                                total_reviews_wpnonce: jQuery('#total_reviews_nonce').val()
                            }, function(res) {
                                console.log('RESPONSE', res);
                            }, 'json');
                        });
                    }
                </script>
                <?php
            }
        }
    }
}
?>
