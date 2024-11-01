jQuery(document).ready(function() {
    var bottom_right = 30;
    var bottom_left = 30;
    jQuery('[class*="total-reviews-badges"]').each(function(index){
        if(jQuery(this).hasClass('wp-badge-fixed_right')){
            jQuery(this).attr('style', 'bottom: '+bottom_right+'px !important');
            bottom_right += 90;
        }
        if(jQuery(this).hasClass('wp-badge-fixed_left')){
            jQuery(this).attr('style', 'bottom: '+bottom_left+'px !important');
            bottom_left += 90;
        }
        
    })  
    
})