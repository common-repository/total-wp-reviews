<?php

if (!current_user_can('manage_options')) {
    die('The account you\'re logged in to doesn\'t have permission to access this page.');
}

function total_reviews_has_valid_nonce() {
    $nonce_actions = array('total_reviews_reset', 'total_reviews_settings', 'total_reviews_active');
    $nonce_form_prefix = 'total_reviews-form_nonce_';
    $nonce_action_prefix = 'total_reviews-wpnonce_';
    foreach ($nonce_actions as $key => $value) {
        if (isset($_POST[$nonce_form_prefix.$value])) {
            check_admin_referer($nonce_action_prefix.$value, $nonce_form_prefix.$value);
            return true;
        }
    }
    return false;
}

function total_reviews_debug() {
    global $wpdb;
    $places = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "total_reviews_google_place");
    $places_error = $wpdb->last_error;
    $reviews = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "total_reviews_google_review");
    $reviews_error = $wpdb->last_error; ?>

    DB Places: <?php echo print_r($places); ?>

    DB Places error: <?php echo $places_error; ?>

    DB Reviews: <?php echo print_r($reviews); ?>

    DB Reviews error: <?php echo $reviews_error;
}

if (!empty($_POST) && !isset($_POST['submit_save_fb_page'])) {
    $nonce_result_check = total_reviews_has_valid_nonce();
    if ($nonce_result_check === false) {
        die('Unable to save changes. Make sure you are accessing this page from the Wordpress dashboard.');
    }
}


// Check POST fields and remove bad input.

if (isset($_POST['total_reviews_active']) && isset($_GET['total_reviews_active'])) {
    update_option('total_reviews_active', ($_GET['total_reviews_active'] == '1' ? '1' : '0'));
}

if (isset($_POST['total_reviews_setting'])) {
    $total_review_api_key = trim(sanitize_text_field($_POST['total_reviews_google_api_key']));
    $total_reviews_lang = trim(sanitize_text_field($_POST['total_reviews_language']));
    update_option('total_reviews_google_api_key', $total_review_api_key);
    update_option('total_reviews_language', $total_reviews_lang);
}

if (isset($_POST['total_reviews_install_db'])) {
    total_reviews_install_db();
}

wp_enqueue_script('jquery');

wp_register_script('twitter_bootstrap3_js', plugins_url('/assets/js/bootstrap.min.js', __FILE__));
wp_enqueue_script('twitter_bootstrap3_js', plugins_url('/assets/js/bootstrap.min.js', __FILE__));
wp_register_style('twitter_bootstrap3_css', plugins_url('/assets/css/bootstrap.min.css', __FILE__));
wp_enqueue_style('twitter_bootstrap3_css', plugins_url('/assets/css/bootstrap.min.css', __FILE__));

wp_register_style('total_reviews_setting_css', plugins_url('/assets/css/total-reviews-setting.min.css', __FILE__));
wp_enqueue_style('total_reviews_setting_css', plugins_url('/assets/css/total-reviews-setting.min.css', __FILE__));

$total_reviews_enabled = get_option('total_reviews_active') == '1';
$total_reviews_google_api_key = get_option('total_reviews_google_api_key');
$total_reviews_language = get_option('total_reviews_language');
?>
<div class="row">
    <div class="col-sm-12">
        <h1><?php echo total_reviews_text('Total WP Reviews'); ?></h1>
        <h3><?php echo total_reviews_text('About Total Reviews Plugin Widget'); ?></h3>
        <ol>
            <li>Go to <b>"Appearance->Widgets".</b> </li>
            <li>Move "Total Reviews Google Widget" or "Total Reviews Facebook Widget" widget to sidebar or any other widget location.</li>
            <li>Find the place or Connect to Facebook Page.</li>
            <li>Select display options.</li>
            <li>Save the data.</li>
        </ol>
    </div>
    <div class="col-sm-12">
        <h3><?php echo total_reviews_text('Recommended Hosting'); ?></h3>
        <a href="https://goo.gl/1TBVfm" target="_blank"><img src="<?php echo plugins_url('/assets/img/468x60.gif', __FILE__) ?>" alt="Web Hosting" width="468" height="60" border="0"></a>        
    </div>
    <div class="col-sm-12">
        <h3><?php echo total_reviews_text('Like this plugin? Feel free to donate!'); ?></h3>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="73BH2ACCDA4HY">
        <input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>
    <div class="col-sm-12">
        <h3><?php echo total_reviews_text('Settings'); ?></h3>
        
    </div>
</div>
<div class="total_reviews-setting container-fluid">
    <ul class="nav nav-tabs" role="tablist">
        
        <li role="presentation" class="active">
            <a href="#setting" aria-controls="setting" role="tab" data-toggle="tab"><?php echo total_reviews_text('Google API'); ?></a>
        </li>
        <li role="presentation">
            <a href="#shortcode-google" aria-controls="shortcode-google" role="tab" data-toggle="tab"><?php echo total_reviews_text('Google Shortcode Helper'); ?></a>
        </li>
        <li role="presentation">
            <a href="#shortcode-facebook" aria-controls="shortcode-facebook" role="tab" data-toggle="tab"><?php echo total_reviews_text('Facebook Shortcode Helper'); ?></a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="setting">
            <h4><?php echo total_reviews_text('Setup Google Places API'); ?></h4>
            <!-- Configuration form -->
            <form method="POST" enctype="multipart/form-data">
                <?php wp_nonce_field('total_reviews-wpnonce_total_reviews_settings', 'total_reviews-form_nonce_total_reviews_settings'); ?>
                <div class="form-group">
                    <input class="form-control" type="text" id="total_reviews_google_api_key" placeholder="<?php echo total_reviews_text('Enter your Google Places API key'); ?>" name="total_reviews_google_api_key" value="<?php echo esc_attr($total_reviews_google_api_key); ?>">
                    <br/>
                    To get an API key you will need an active Google account, then follow these steps:<br/>
                    <ol>
                        <li>Visit the <a href="https://developers.google.com/places/web-service/get-api-key" target="_blank">Google Places API Web Service</a> page. If you are not logged into your Google Account already, you can do so there.</li>
                        <li>Click on the “Get a Key” button in the top-right of the screen.</li>
                        <li>You’ll be presented with a modal which allows you to choose an existing API Project, or create a new one. Do either, then click on “Next”.</li>
                        <img src="<?php echo plugins_url('/assets/img/api-step2.PNG', __FILE__) ?>"><br/>
                        <li>Copy key YOUR API KEY and paste it in the box above.</li>
                        <img src="<?php echo plugins_url('/assets/img/api-step3.PNG', __FILE__) ?>"><br/>
                    </ol>
                </div>
                
                
                <p class="submit" style="text-align: left">
                    <input name="total_reviews_setting" type="submit" value="Save" class="button-primary button" tabindex="4">
                </p>
            </form>
            <hr>

            
            <!-- Enable/disable Google Reviews Widget toggle -->
            <!--<form method="POST" action="?page=total_reviews&amp;total_reviews_active=<?php //echo (string)((int)($total_reviews_enabled != true)); ?>">
                <?php //wp_nonce_field('total_reviews-wpnonce_total_reviews_active', 'total_reviews-form_nonce_total_reviews_active'); ?>
                <span class="status">
                    <?php //echo total_reviews_text('Total Reviews are currently '). '<b>' .($total_reviews_enabled ? total_reviews_text('enable') : total_reviews_text('disable')). '</b>'; ?>
                </span>
                <input type="submit" name="total_reviews_active" class="button" value="<?php //echo $total_reviews_enabled ? total_reviews_text('Disable') : total_reviews_text('Enable'); ?>" />
            </form>
            <hr>-->
           
            <!-- Reset form -->
            <!-- <form action="?page=total_reviews" method="POST">
                <?php //wp_nonce_field('total_reviews-wpnonce_total_reviews_reset', 'total_reviews-form_nonce_total_reviews_reset'); ?>
                <p>
                    <input type="submit" value="Reset" name="reset" onclick="return confirm('<?php //echo total_reviews_text('Are you sure you want to reset the Google Reviews Widget plugin?'); ?>')" class="button" />
                    <?php //echo total_reviews_text('This removes all plugin-specific settings.') ?>
                </p>
                <p>
                    <input type="checkbox" id="reset_db" name="reset_db">
                    <label for="reset_db"><?php //echo total_reviews_text('Remove all data including Google Reviews'); ?></label>
                </p>
            </form> -->
        </div>
        <div role="tabpanel" class="tab-pane" id="shortcode-facebook">
            <div id="total-reviews-get-page-builder" class="col-md-6 shortcode-facebook">
                <button class="total-reviews-fbconnect"><?php echo total_reviews_text('Connect to Facebook'); ?></button>

                <div class="total-reviews-fbpages"></div>
                <form>
                    <div class="form-group">
                    Please use shortcode [facebook_badge page_id="Facebook Page ID" page_name="Facebook Page Name"]. Replace "page_id" and "page_name" with the values below.
                        <div class="col-sm-12">
                            <input type="text" id="page_name" name="page_name" value="" class="form-control total-reviews-fbpage-name" placeholder="<?php echo total_reviews_text('Page Name'); ?>" readonly />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" id="page_id" name="page_id" value="" class="form-control total-reviews-fbpage-id" placeholder="<?php echo total_reviews_text('Page ID'); ?>" readonly />
                        </div>
                    </div>

                    <input type="hidden" id="page_access_token" name="page_access_token" value="" class="form-control total-reviews-fbpage-token" placeholder="<?php echo total_reviews_text('Access token'); ?>" readonly />
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="button" class="btn btn-block btn-primary" name="submit_save_fb_page" id="submit_save_fb_page" value="<?php echo total_reviews_text('Save Rating'); ?>" />
                        </div>
                    </div>
                    
                    
                </form>
                <script type="text/javascript">
                    (function($){
                        $(document).ready(function(){
                            $('#submit_save_fb_page').on('click', function(e){
                                e.preventDefault();
                                e.stopPropagation();
                                console.log('dsds')
                                $.ajax({
                                    type : "post",
                                    url : '<?php echo admin_url('admin-ajax.php');?>', 
                                    data : {
                                        action: "total_reviews_save_fb_data",
                                        submit_save_fb_page : true,
                                        page_id: $('#page_id').val(),
                                        page_access_token:$('#page_access_token').val(),
                                        page_name: $('#page_name').val(),
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        if(response == 'Success'){
                                            $('#submit_save_fb_page').css('background-color','#00897b');
                                            $('#submit_save_fb_page').val('Save Success');
                                        }
                                        
                                    },
                                    error: function( jqXHR, textStatus, errorThrown ){
                                        console.log( 'The following error occured: ' + textStatus, errorThrown );
                                    }
                                })
                                return false;
                            })
                        })
                    })(jQuery)
                </script>
            </div>

            <div class="col-md-6">
                <h4>Facebook Reviews Shortcode Attributes: [facebook_badge]</h4>
                <ul>
                    <li><b>title</b>: Default is "Facebook Rating"</li>
                    <li><b>page_name *</b>: Connect to Facebook and select Page to get page_name</li>
                    <li><b>page_id *</b>: Connect to Facebook and select Page to get page_id</li>
                    <li><b>position</b>: Display you badge in bottom of site, position should be: 'left' or 'right'. Default is 'left'</li>
                </ul>
            </div>

            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" onload="total_reviews_fbinit({widgetId: 'total-reviews-get-page-builder'})" style="display:none">
        </div>
        <div role="tabpanel" class="tab-pane" id="shortcode-google">
        <?php wp_nonce_field('total_reviews_wpnonce', 'total_reviews_nonce'); ?>
            <div id="total-reviews-find-place-builder" class="col-md-6">
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
                        <h4 class="text-left"><span class="badge">3</span> <?php echo total_reviews_text('Save the Place'); ?></h4>
                        <div class="total-reviews-reviews"></div>
                        <!--<div class="total-reviews-five-reviews-note" style="display:none"><?php //echo total_reviews_text('Google returns 5 reviews only'); ?></div>-->
                        <div class="total-reviews-save-reviews-container"></div>
                    </div>
                </div>

                <div class="form-group">
                    Please use shortcode [google_badge place_id="Google Place ID" place_name="Google Place Name"]. Replace "place_id" and "place_name" with the values below.
                    <div class="col-sm-12">
                        place_name: <input type="text" class="form-control total-reviews-google-place-name" placeholder="Google Place Name" readonly="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        place_id : <input type="text" class="form-control total-reviews-google-place-id" placeholder="Google Place ID" readonly="">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Google Reviews Shortcode Attributes: [google_badge]</h4>
                <ul>
                    <li><b>title</b>: Default is "Google Rating"</li>
                    <li><b>place_name *</b>: Find Place to get place_name</li>
                    <li><b>place_id *</b>: Find Place to get place_id</li>
                    <li><b>position</b>: Display you badge in bottom of site, position should be: 'left' or 'right'. Default is 'left'</li>
                </ul>
            </div>
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" onload="total_reviews_init({widgetId: 'total-reviews-find-place-builder'})" style="display:none" >
            
       </div>
    </div>
</div>
