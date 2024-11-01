<?php
/**
 * Plugin Name: Total WP Reviews
 * Description: Show Facebook Page Reviews and Google Places Reviews on your websites in fixed position or widget.
 * Author: Shounak Gupte
 * Version: 1.0.2
 * Author URI: http://www.shounakgupte.com
 * License: GPLv3
*/


require(ABSPATH . 'wp-includes/version.php');
include_once(dirname(__FILE__) . '/api/urlopen.php');
include_once(dirname(__FILE__) . '/total-reviews.shortcode.php');

define('TOTAL_REVIEWS',             '1.0');
define('TOTAL_REVIEWS_GG_PLACE_API',    'https://maps.googleapis.com/maps/api/place/');
define('TOTAL_REVIEWS_PLUGIN_URL',          plugins_url(basename(plugin_dir_path(__FILE__ )), basename(__FILE__)));
define('TOTAL_REVIEWS_GRAPH_API',          'https://graph.facebook.com/');
define('TOTAL_REVIEWS_API_RATINGS_LIMIT',  '25');
define('TOTAL_REVIEWS_FB','https://www.facebook.com/');
define('TOTAL_REVIEWS_FB_AVATAR',             TOTAL_REVIEWS_PLUGIN_URL . '/assets/img/114307615494839964028.jpg');
define('TOTAL_REVIEWS_GG_AVATAR',       TOTAL_REVIEWS_PLUGIN_URL . '/assets/img/114307615494839964028.jpg');

function total_review_settings() {
    return array(
        'total_reviews_version',
        'total_reviews_active',
        'total_reviews_google_api_key',
        'total_reviews_language',
    );
}

/*-------------------------------- Google Widget --------------------------------*/
function total_reviews_init_google_widget() {
    if (!class_exists('Total_Reviews_Google_Widget' ) ) {
        require 'google-widget/widget.php';
    }
}

add_action('widgets_init', 'total_reviews_init_google_widget');
add_action('widgets_init', create_function('', 'register_widget("Total_Reviews_Google_Widget");'));

/*-------------------------------- FB Widget --------------------------------*/
function total_reviews_init_fb_widget() {
    if (!class_exists('Total_Reviews_Facebook_Widget' ) ) {
        require 'fb-widget/widget.php';
    }
}

add_action('widgets_init', 'total_reviews_init_fb_widget');
add_action('widgets_init', create_function('', 'register_widget("Total_Reviews_Facebook_Widget");'));

add_shortcode( 'google_badge', array( 'Total_Reviews_ShortCode', 'google_badge' ) );
add_shortcode( 'facebook_badge', array( 'Total_Reviews_ShortCode', 'facebook_badge' ) );

function total_reviews_menu() {
    add_submenu_page(
        'options-general.php',
        'Total Reviews Settings',
        'Total Reviews',
        'moderate_comments',
        'total_reviews',
        'total_reviews_setting'
    );
}
add_action('admin_menu', 'total_reviews_menu', 10);

function total_reviews_setting() {
    include_once(dirname(__FILE__) . '/total-reviews.setting.php');
}

/*-------------------------------- Links --------------------------------*/
function total_reviews_plugin_settings_url($links, $file) {
    $plugin_file = basename(__FILE__);
    if (basename($file) == $plugin_file) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=total_reviews') . '">'.total_reviews_text('Settings') . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'total_reviews_plugin_settings_url', 10, 2);

/*-------------------------------- Row Meta --------------------------------*/
function total_reviews_plugin_row_meta($input, $file) {
    if ($file != plugin_basename( __FILE__ )) {
        return $input;
    }

    $links = array(
        '<a href="' . admin_url('options-general.php?page=total_reviews')  . '" target="_blank">' . total_reviews_text('View Documentation') . '</a>',
    );
    $input = array_merge($input, $links);
    return $input;
}
add_filter('plugin_row_meta', 'total_reviews_plugin_row_meta', 10, 2);

/*-------------------------------- Database --------------------------------*/
function total_reviews_activation() {
    if (total_reviews_does_need_update()) {
        total_reviews_install();
    }
}
register_activation_hook(__FILE__, 'total_reviews_activation');

function total_reviews_install($allow_db_install=true) {
    global $wpdb, $userdata;

    $version = (string)get_option('total_reviews');
    if (!$version) {
        $version = '0';
    }

    if ($allow_db_install) {
        total_reviews_install_db($version);
    }

    if (version_compare($version, TOTAL_REVIEWS, '=')) {
        return;
    }

    add_option('total_reviews_active', '1');
    add_option('total_reviews_google_api_key', '');
    add_option('fbrev_active', '1');
    update_option('total_reviews', TOTAL_REVIEWS);
}

function total_reviews_install_db() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $wpdb->query("CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "total_reviews_google_place (".
        "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,".
        "place_id VARCHAR(80) NOT NULL,".
        "name VARCHAR(255) NOT NULL,".
        "photo VARCHAR(255),".
        "icon VARCHAR(255),".
        "address VARCHAR(255),".
        "rating DOUBLE PRECISION,".
        "url VARCHAR(255),".
        "website VARCHAR(255),".
        "updated BIGINT(20),".
        "PRIMARY KEY (`id`),".
        "UNIQUE INDEX total_reviews_google_place_id (`place_id`)".
        ") " . $charset_collate . ";");

    $wpdb->query("CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "total_reviews_facebook_page (".
        "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,".
        "page_id VARCHAR(80) NOT NULL,".
        "token VARCHAR(255) NOT NULL,".
        "name VARCHAR(255) NOT NULL,".
        "photo VARCHAR(255),".
        "icon VARCHAR(255),".
        "rating DOUBLE PRECISION,".
        "PRIMARY KEY (`id`),".
        "UNIQUE INDEX total_reviews_facebook_page (`page_id`)".
        ") " . $charset_collate . ";");

    $wpdb->query("CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "total_reviews_google_review (".
        "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,".
        "google_place_id BIGINT(20) UNSIGNED NOT NULL,".
        "hash VARCHAR(40) NOT NULL,".
        "rating INTEGER NOT NULL,".
        "text VARCHAR(10000),".
        "time INTEGER NOT NULL,".
        "language VARCHAR(2),".
        "author_name VARCHAR(255),".
        "author_url VARCHAR(255),".
        "profile_photo_url VARCHAR(255),".
        "PRIMARY KEY (`id`),".
        "UNIQUE INDEX total_reviews_google_hash (`hash`),".
        "INDEX total_reviews_google_place_id (`google_place_id`)".
        ") " . $charset_collate . ";");
}

function total_reviews_reset_db() {
    global $wpdb;

    $wpdb->query("DROP TABLE " . $wpdb->prefix . "total_reviews_google_place;");
    $wpdb->query("DROP TABLE " . $wpdb->prefix . "total_reviews_google_review;");
}

function total_reviews_widget_scripts($hook) {
    if ($hook == 'settings_page_total_reviews'  || $hook == 'widgets.php' || ($hook == 'customize.php' && defined('SITEORIGIN_PANELS_VERSION'))) {
        
        wp_enqueue_script('jquery');

        $finder_vars = array(
            'GOOGLE_AVATAR' => TOTAL_REVIEWS_GG_AVATAR,
            'handlerUrl' => admin_url('options-general.php?page=total_reviews'),
            'actionPrefix' => 'total_reviews'
        );
        wp_register_script('total_reviews_finder_js', plugin_dir_url( __FILE__ ) .'assets/js/total-reviews-finder.min.js');
        wp_localize_script('total_reviews_finder_js', 'total_reviewsVars', $finder_vars );
        wp_enqueue_script('total_reviews_finder_js', plugin_dir_url( __FILE__ ) .'assets/js/total-reviews-finder.min.js');

        wp_register_script('wpac_js', plugins_url('assets/js/wpac.js', __FILE__));
        wp_enqueue_script('wpac_js', plugins_url('assets/js/wpac.js', __FILE__));

        wp_register_script('total_reviews_facebook_connect_js', plugins_url('assets/js/total-reviews-connect.min.js', __FILE__));
        wp_enqueue_script('total_reviews_facebook_connect_js', plugins_url('assets/js/total-reviews-connect.min.js', __FILE__));

        wp_register_style('rplg_css', plugins_url('assets/css/rplg.css', __FILE__));
        wp_enqueue_style('rplg_css', plugins_url('assets/css/rplg.css', __FILE__));

        wp_register_script('wpac_time_js', plugins_url('assets/js/wpac-time.js', __FILE__));
        wp_enqueue_script('wpac_time_js', plugins_url('assets/js/wpac-time.js', __FILE__));

        wp_register_style('total_reviews_widget', plugin_dir_url( __FILE__ ) .'assets/css/total-reviews-widget.min.css');
        wp_enqueue_style('total_reviews_widget', plugin_dir_url( __FILE__ ) .'assets/css/total-reviews-widget.min.css');


    }
}
add_action( 'admin_enqueue_scripts', 'total_reviews_widget_scripts' );
function total_reviews_widget_position(){
    wp_register_script('total_reviews_widget_position_js', plugins_url('assets/js/total-reviews-position.js', __FILE__));
    wp_enqueue_script('total_reviews_widget_position_js', plugins_url('assets/js/total-reviews-position.js', __FILE__), false, true, true);
}
add_action( 'wp_enqueue_scripts', 'total_reviews_widget_position' );

add_action( 'wp_ajax_total_reviews_save_fb_data', 'total_reviews_save_fb_data' );
add_action( 'wp_ajax_nopriv_total_reviews_save_fb_data', 'total_reviews_save_fb_data' );
function total_reviews_save_fb_data() {
    if (isset($_POST['submit_save_fb_page']) && is_admin() ) {
        global $wpdb;
        if(isset($_POST['page_id']) && isset($_POST['page_access_token']) && isset($_POST['page_name'])){
            $page_access_token = trim(sanitize_text_field($_POST['page_access_token']));
            $page_id = trim(sanitize_text_field($_POST['page_id']));

            $params = array('access_token' => $page_access_token, 'limit' => TOTAL_REVIEWS_API_RATINGS_LIMIT);
            $url = TOTAL_REVIEWS_GRAPH_API . $page_id . '/ratings?' . rplg_get_query_string($params);
            $api_response = rplg_urlopen($url);
            $response_json = rplg_json_decode($api_response['data']);
            if (isset($response_json->data)) {
                $reviews = $response_json->data;
            
                $rating = 0;
                if (count($reviews) > 0) {
                    foreach ($reviews as $review) {
                        $rating = $rating + $review->rating;
                    }
                    $rating = round($rating / count($reviews), 1);
                    $rating = number_format((float)$rating, 1, '.', '');
                }
                $page_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "total_reviews_facebook_page WHERE page_id = %s", $_POST['page_id']));
                if ($page_id) {
                    $wpdb->update($wpdb->prefix . 'total_reviews_facebook_page', array('name' => htmlspecialchars($_POST['page_name']), 'rating' => $rating), array('ID' => $page_id));
                    echo 'Success';
                    die();
                }else{
                    $wpdb->insert($wpdb->prefix . 'total_reviews_facebook_page', array(
                        'page_id' => htmlspecialchars($_POST['page_id']),
                        'name' => htmlspecialchars($_POST['page_name']),
                        'token' => htmlspecialchars($_POST['page_access_token']),
                        'rating' => isset($rating) ? $rating : null
                    ));
                    echo 'Success';
                    die();
                    
                }
                
            }
        }
            
    }
}

/*-------------------------------- Request --------------------------------*/
function total_reviews_request_handler() {
    global $wpdb;

    if (!empty($_GET['cf_action'])) {

        switch ($_GET['cf_action']) {
            case 'total_reviews_google_api_key':
                if (current_user_can('manage_options')) {
                    if (isset($_POST['total_reviews_wpnonce']) === false) {
                        $error = total_reviews_text('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('total_reviews_wpnonce', 'total_reviews_wpnonce');

                        update_option('total_reviews_google_api_key', trim(sanitize_text_field($_POST['key'])));
                        $status = 'success';
                        $response = compact('status');

                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response);
                    die();
                }
                break;
            case 'total_reviews_search':
                if (current_user_can('manage_options')) {
                    if (isset($_GET['total_reviews_wpnonce']) === false) {
                        $error = total_reviews_text('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('total_reviews_wpnonce', 'total_reviews_wpnonce');
                        $query = trim(sanitize_text_field($_GET['query']));
                        $total_reviews_google_api_key = get_option('total_reviews_google_api_key');
                        $url = TOTAL_REVIEWS_GG_PLACE_API . 'textsearch/json?query=' . $_GET['query'] . '&key=' . $total_reviews_google_api_key;

                        $total_reviews_language = get_option('total_reviews_language');
                        if (strlen($total_reviews_language) > 0) {
                            $url = $url . '&language=' . $total_reviews_language;
                        }

                        $response = rplg_urlopen($url);

                        $response_data = $response['data'];
                        $response_json = rplg_json_decode($response_data);

                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response_json->results);
                    die();
                }
                break;
            case 'total_reviews_reviews':
                if (current_user_can('manage_options')) {
                    if (isset($_GET['total_reviews_wpnonce']) === false) {
                        $error = total_reviews_text('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('total_reviews_wpnonce', 'total_reviews_wpnonce');

                        $url = total_reviews_api_url($_GET['placeid']);

                        $response = rplg_urlopen($url);

                        $response_data = $response['data'];
                        $response_json = rplg_json_decode($response_data);

                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response_json->result);
                    die();
                }
                break;
            case 'total_reviews_save':
                if (current_user_can('manage_options')) {
                    if (isset($_POST['total_reviews_wpnonce']) === false) {
                        $error = total_reviews_text('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('total_reviews_wpnonce', 'total_reviews_wpnonce');
                        $placeid = trim(sanitize_text_field($_POST['placeid']));
                        $url = total_reviews_api_url($placeid);

                        $response = rplg_urlopen($url);

                        $response_data = $response['data'];
                        $response_json = rplg_json_decode($response_data);

                        if ($response_json && $response_json->result) {
                            total_reviews_save_reviews($response_json->result);
                            $status = 'success';
                        } else {
                            $status = 'failed';
                        }
                        $response = compact('status');
                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response);
                    die();
                }
            
                break;
        }
    }
}
add_action('init', 'total_reviews_request_handler');

function total_reviews_save_reviews($place, $min_filter = 0) {
    global $wpdb;

    $google_place_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "total_reviews_google_place WHERE place_id = %s", $place->place_id));
    if ($google_place_id) {
        $wpdb->update($wpdb->prefix . 'total_reviews_google_place', array('name' => $place->name, 'rating' => $place->rating), array('ID' => $google_place_id));
    } else {
        $wpdb->insert($wpdb->prefix . 'total_reviews_google_place', array(
            'place_id' => $place->place_id,
            'name' => $place->name,
            //'photo' => $place->photo,
            'icon' => $place->icon,
            'address' => $place->formatted_address,
            'rating' => isset($place->rating) ? $place->rating : null,
            'url' => isset($place->url) ? $place->url : null,
            'website' => isset($place->website) ? $place->website : null
        ));
        $google_place_id = $wpdb->insert_id;
    }

    if ($place->reviews) {
        $reviews = $place->reviews;
        foreach ($reviews as $review) {
            if ($min_filter > 0 && $min_filter > $review->rating) {
                continue;
            }
            if (!isset($review->author_url) || strlen($review->author_url) < 1) {
                continue;
            }
            $hash = sha1($place->place_id . $review->author_url);
            $google_review_hash = $wpdb->get_var($wpdb->prepare("SELECT hash FROM " . $wpdb->prefix . "total_reviews_google_review WHERE hash = %s", $hash));
            if (!$google_review_hash) {
                $wpdb->insert($wpdb->prefix . 'total_reviews_google_review', array(
                    'google_place_id' => $google_place_id,
                    'hash' => $hash,
                    'rating' => $review->rating,
                    'text' => $review->text,
                    'time' => $review->time,
                    'language' => $review->language,
                    'author_name' => $review->author_name,
                    'author_url' => $review->author_url,
                    'profile_photo_url' => isset($review->profile_photo_url) ? $review->profile_photo_url : null
                ));
            }
        }
    }
}

function total_reviews_lang_init() {
    $plugin_dir = basename(dirname(__FILE__));
    load_plugin_textdomain('total_reviews', false, basename( dirname( __FILE__ ) ) . '/languages');
}
add_action('plugins_loaded', 'total_reviews_lang_init');

/*-------------------------------- Helpers --------------------------------*/
function total_reviews_enabled() {
    global $id, $post;

    $active = get_option('total_reviews_active');
    if (empty($active) || $active === '0') { return false; }
    return true;
}

function total_reviews_api_url($placeid, $reviews_lang = '') {
    $total_reviews_google_api_key = get_option('total_reviews_google_api_key');
    $url = TOTAL_REVIEWS_GG_PLACE_API . 'details/json?placeid=' . $placeid . '&key=' . $total_reviews_google_api_key;

    $total_reviews_language = strlen($reviews_lang) > 0 ? $reviews_lang : get_option('total_reviews_language');
    if (strlen($total_reviews_language) > 0) {
        $url = $url . '&language=' . $total_reviews_language;
    }
    return $url;
}


function total_reviews_does_need_update() {
    $version = (string)get_option('total_reviews_version');
    if (empty($version)) {
        $version = '0';
    }
    if (version_compare($version, '1.0', '<')) {
        return true;
    }
    return false;
}
function total_reviews_facebook_rating($page_id, $page_access_token, $options, $cache_name, $cache_option, $limit, $page_name) {
    global $wpdb;

    $response_cache_key = 'total_reviews_fb' . $cache_name . '_api_' . $page_id;
    $options_cache_key = 'total_reviews_fb' . $cache_name . '_options_' . $page_id;
    $options_cache_count = 'total_reviews_fb_count'. $page_id;

    if (!isset($limit) || $limit == null) {
        $limit=TOTAL_REVIEWS_API_RATINGS_LIMIT;
    }

    $api_response = get_transient($response_cache_key);
    $widget_options = get_transient($options_cache_key);
    $options_count = get_transient($options_cache_count);
	/* if($options_count ===  false  ) {
		print_r($options_cache_count.'vinh false<br>');
	} else {
		print_r($options_cache_count.'vinh1234 true<br>');
	} */
    $serialized_instance = serialize($options);

    if ($api_response === false || $serialized_instance !== $widget_options || $options_count ===  false || $options_count ==  '') {
        $expiration = $cache_option;
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
		
        $params = array('access_token' => $page_access_token, 'limit' => $limit);
        $url = TOTAL_REVIEWS_GRAPH_API . $page_id . '/ratings?' . rplg_get_query_string($params);
		echo $url ;
		$api_response = rplg_urlopen($url);


        $urlcount = TOTAL_REVIEWS_GRAPH_API . $page_id . '/?fields=rating_count,overall_star_rating&' . rplg_get_query_string($params);
		//echo $url;
		$urlcount_array =  rplg_urlopen($urlcount);
		$urlcount_array_count = rplg_json_decode($urlcount_array['data']);
		$urlcount_array_counts =  $urlcount_array_count -> rating_count;
		$urlcount_array_overall_star_rating =  $urlcount_array_count -> overall_star_rating;

		if(is_numeric($urlcount_array_counts) && $urlcount_array_counts >= 0) {
			set_transient($options_cache_count, $urlcount_array_counts, $expiration);
			$api_response["totalreviewsfbcount"] = $urlcount_array_counts;
		} else {
			set_transient($options_cache_count, 0 , $expiration);
			$api_response["totalreviewsfbcount"] = 0;
		}
		$api_response["overall_star_rating"] = $urlcount_array_overall_star_rating;
        

        //print_r($api_response);
        set_transient($response_cache_key, $api_response, $expiration);
        set_transient($options_cache_key, $serialized_instance, $expiration);


    }
    
	//print_r($api_response);
    return $api_response;
}


function total_reviews_text($text, $params=null) {
    if (!is_array($params)) {
        $params = func_get_args();
        $params = array_slice($params, 1);
    }
    return vsprintf(__($text, 'total_reviews'), $params);
}

if (!function_exists('esc_html')) {
    function esc_html( $text ) {
        $safe_text = wp_check_invalid_utf8( $text );
        $safe_text = _wp_specialchars( $safe_text, ENT_QUOTES );
        return apply_filters( 'esc_html', $safe_text, $text );
    }
}

if (!function_exists('esc_attr')) {
    function esc_attr( $text ) {
        $safe_text = wp_check_invalid_utf8( $text );
        $safe_text = _wp_specialchars( $safe_text, ENT_QUOTES );
        return apply_filters( 'attribute_escape', $safe_text, $text );
    }
}

/**
 * JSON ENCODE for PHP < 5.2.0
 */
if (!function_exists('json_encode')) {

    function json_encode($data) {
        return cfjson_encode($data);
    }

    function cfjson_encode_string($str) {
        if(is_bool($str)) {
            return $str ? 'true' : 'false';
        }

        return str_replace(
            array(
                '\\'
            , '"'
                //, '/'
            , "\n"
            , "\r"
            )
            , array(
                '\\\\'
            , '\"'
                //, '\/'
            , '\n'
            , '\r'
            )
            , $str
        );
    }

    function cfjson_encode($arr) {
        $json_str = '';
        if (is_array($arr)) {
            $pure_array = true;
            $array_length = count($arr);
            for ( $i = 0; $i < $array_length ; $i++) {
                if (!isset($arr[$i])) {
                    $pure_array = false;
                    break;
                }
            }
            if ($pure_array) {
                $json_str = '[';
                $temp = array();
                for ($i=0; $i < $array_length; $i++) {
                    $temp[] = sprintf("%s", cfjson_encode($arr[$i]));
                }
                $json_str .= implode(',', $temp);
                $json_str .="]";
            }
            else {
                $json_str = '{';
                $temp = array();
                foreach ($arr as $key => $value) {
                    $temp[] = sprintf("\"%s\":%s", $key, cfjson_encode($value));
                }
                $json_str .= implode(',', $temp);
                $json_str .= '}';
            }
        }
        else if (is_object($arr)) {
            $json_str = '{';
            $temp = array();
            foreach ($arr as $k => $v) {
                $temp[] = '"'.$k.'":'.cfjson_encode($v);
            }
            $json_str .= implode(',', $temp);
            $json_str .= '}';
        }
        else if (is_string($arr)) {
            $json_str = '"'. cfjson_encode_string($arr) . '"';
        }
        else if (is_numeric($arr)) {
            $json_str = $arr;
        }
        else if (is_bool($arr)) {
            $json_str = $arr ? 'true' : 'false';
        }
        else {
            $json_str = '"'. cfjson_encode_string($arr) . '"';
        }
        return $json_str;
    }
}
?>
