<?php
if(!class_exists('Total_Reviews_Facebook_Widget')){
    
    class Total_Reviews_Facebook_Widget extends WP_Widget {

        public $options;

        public $widget_fields = array(
            'title'                => '',
            'page_id'              => '',
            'page_name'            => '',
            'page_access_token'    => '',
            'dark_theme'           => '',
            'view_mode'            => '',
            'display_reviews'            => true,
            'open_link'            => true,
            'nofollow_link'        => true,
			'display_fixed'        => true,
            'cache'                => '24',
            'api_ratings_limit'    => TOTAL_REVIEWS_API_RATINGS_LIMIT,
        );

        public function __construct() {
            parent::__construct(
                'total_reviews_facebook_widget',
                'Total Reviews Facebook Widget',
                array(
                    'classname'   => 'total-reviews-facebook-widget',
                    'description' => total_reviews_text('Display Facebook Reviews on your website.', 'total_reviews')
                )
            );
            wp_register_style('total_reviews_widget_css', plugins_url('../assets/css/total-reviews.min.css', __FILE__));
            wp_enqueue_style('total_reviews_widget_css', plugins_url('../assets/css/total-reviews.min.css', __FILE__));
        }

        function widget($args, $instance) {
            global $wpdb;

            if (total_reviews_enabled()) {
                extract($args);
                foreach ($this->widget_fields as $variable => $value) {
                    ${$variable} = !isset($instance[$variable]) ? $this->widget_fields[$variable] : esc_attr($instance[$variable]);
                }

                if (empty($page_id)) { ?>
                    <div class="total-reviews-fberror" style="padding:10px;color:#B94A48;background-color:#F2DEDE;border-color:#EED3D7;">
                        <?php echo total_reviews_text('Please check that this widget <b>Facebook Reviews</b> has a connected Facebook.'); ?>
                    </div> <?php
                    return false;
                }

                echo $before_widget;
                $response = total_reviews_facebook_rating($page_id, $page_access_token, $instance, $this->id, $cache, $api_ratings_limit, $page_name);
                $response_data = $response['data'];
                
                $response_json = rplg_json_decode($response_data);
				$total_all_reviews = $response['totalreviewsfbcount'];
				$overall_star_rating = $response['overall_star_rating'];
                if (isset($response_json->data)) {
                    $reviews = $response_json->data;
                    if ($title) { ?><h2 class="total-reviews-fbwidget-title widget-title"><?php echo $title; ?></h2><?php }
                    include(dirname(__FILE__) . '/total-reviews.reviews.php');
                } else {
                    ?>
                    <div class="total-reviews-fberror" style="padding:10px;color:#B94A48;background-color:#F2DEDE;border-color:#EED3D7;">
                        <?php echo total_reviews_text('Facebook API Rating: ') . $response_data; ?>
                    </div>
                    <?php
                }
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
            } ?>

            <div id="<?php echo $this->id; ?>" class="rplg-widget">
                <?php include(dirname(__FILE__) . '/total-reviews.options.php'); ?>
                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-widget-id="<?php echo $this->id; ?>"
                onload="total_reviews_fbinit({widgetId: this.getAttribute('data-widget-id')})" style="display:none">
            </div>

            <?php
        }
    }
}
?>
