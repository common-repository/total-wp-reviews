<?php if (isset($title)) { ?>
<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="form-control" placeholder="<?php echo total_reviews_text('Widget title'); ?>" />
    </div>
</div>
<?php } ?>

<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('place_name'); ?>" name="<?php echo $this->get_field_name('place_name'); ?>" value="<?php echo $place_name; ?>" class="form-control total-reviews-google-place-name" placeholder="<?php echo total_reviews_text('Google Place Name'); ?>" readonly />
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <input type="text" id="<?php echo $this->get_field_id('place_id'); ?>" name="<?php echo $this->get_field_name('place_id'); ?>" value="<?php echo $place_id; ?>" class="form-control total-reviews-google-place-id" placeholder="<?php echo total_reviews_text('Google Place ID'); ?>" readonly />
    </div>
</div>

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
                <?php echo total_reviews_text('Use no follow links'); ?>
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
            <?php echo total_reviews_text('Language of reviews'); ?>
            <select id="<?php echo $this->get_field_id('reviews_lang'); ?>" name="<?php echo $this->get_field_name('reviews_lang'); ?>" class="form-control">
                <option value="" <?php selected('', $reviews_lang); ?>><?php echo total_reviews_text('Disable'); ?></option>
                <option value="ar" <?php selected('ar', $reviews_lang); ?>><?php echo total_reviews_text('Arabic'); ?></option>
                <option value="bg" <?php selected('bg', $reviews_lang); ?>><?php echo total_reviews_text('Bulgarian'); ?></option>
                <option value="bn" <?php selected('bn', $reviews_lang); ?>><?php echo total_reviews_text('Bengali'); ?></option>
                <option value="ca" <?php selected('ca', $reviews_lang); ?>><?php echo total_reviews_text('Catalan'); ?></option>
                <option value="cs" <?php selected('cs', $reviews_lang); ?>><?php echo total_reviews_text('Czech'); ?></option>
                <option value="da" <?php selected('da', $reviews_lang); ?>><?php echo total_reviews_text('Danish'); ?></option>
                <option value="de" <?php selected('de', $reviews_lang); ?>><?php echo total_reviews_text('German'); ?></option>
                <option value="el" <?php selected('el', $reviews_lang); ?>><?php echo total_reviews_text('Greek'); ?></option>
                <option value="en" <?php selected('en', $reviews_lang); ?>><?php echo total_reviews_text('English'); ?></option>
                <option value="en-AU" <?php selected('en-AU', $reviews_lang); ?>><?php echo total_reviews_text('English (Australian)'); ?></option>
                <option value="en-GB" <?php selected('en-GB', $reviews_lang); ?>><?php echo total_reviews_text('English (Great Britain)'); ?></option>
                <option value="es" <?php selected('es', $reviews_lang); ?>><?php echo total_reviews_text('Spanish'); ?></option>
                <option value="eu" <?php selected('eu', $reviews_lang); ?>><?php echo total_reviews_text('Basque'); ?></option>
                <option value="eu" <?php selected('eu', $reviews_lang); ?>><?php echo total_reviews_text('Basque'); ?></option>
                <option value="fa" <?php selected('fa', $reviews_lang); ?>><?php echo total_reviews_text('Farsi'); ?></option>
                <option value="fi" <?php selected('fi', $reviews_lang); ?>><?php echo total_reviews_text('Finnish'); ?></option>
                <option value="fil" <?php selected('fil', $reviews_lang); ?>><?php echo total_reviews_text('Filipino'); ?></option>
                <option value="fr" <?php selected('fr', $reviews_lang); ?>><?php echo total_reviews_text('French'); ?></option>
                <option value="gl" <?php selected('gl', $reviews_lang); ?>><?php echo total_reviews_text('Galician'); ?></option>
                <option value="gu" <?php selected('gu', $reviews_lang); ?>><?php echo total_reviews_text('Gujarati'); ?></option>
                <option value="hi" <?php selected('hi', $reviews_lang); ?>><?php echo total_reviews_text('Hindi'); ?></option>
                <option value="hr" <?php selected('hr', $reviews_lang); ?>><?php echo total_reviews_text('Croatian'); ?></option>
                <option value="hu" <?php selected('hu', $reviews_lang); ?>><?php echo total_reviews_text('Hungarian'); ?></option>
                <option value="id" <?php selected('id', $reviews_lang); ?>><?php echo total_reviews_text('Indonesian'); ?></option>
                <option value="it" <?php selected('it', $reviews_lang); ?>><?php echo total_reviews_text('Italian'); ?></option>
                <option value="iw" <?php selected('iw', $reviews_lang); ?>><?php echo total_reviews_text('Hebrew'); ?></option>
                <option value="ja" <?php selected('ja', $reviews_lang); ?>><?php echo total_reviews_text('Japanese'); ?></option>
                <option value="kn" <?php selected('kn', $reviews_lang); ?>><?php echo total_reviews_text('Kannada'); ?></option>
                <option value="ko" <?php selected('ko', $reviews_lang); ?>><?php echo total_reviews_text('Korean'); ?></option>
                <option value="lt" <?php selected('lt', $reviews_lang); ?>><?php echo total_reviews_text('Lithuanian'); ?></option>
                <option value="lv" <?php selected('lv', $reviews_lang); ?>><?php echo total_reviews_text('Latvian'); ?></option>
                <option value="ml" <?php selected('ml', $reviews_lang); ?>><?php echo total_reviews_text('Malayalam'); ?></option>
                <option value="mr" <?php selected('mr', $reviews_lang); ?>><?php echo total_reviews_text('Marathi'); ?></option>
                <option value="nl" <?php selected('nl', $reviews_lang); ?>><?php echo total_reviews_text('Dutch'); ?></option>
                <option value="no" <?php selected('no', $reviews_lang); ?>><?php echo total_reviews_text('Norwegian'); ?></option>
                <option value="pl" <?php selected('pl', $reviews_lang); ?>><?php echo total_reviews_text('Polish'); ?></option>
                <option value="pt" <?php selected('pt', $reviews_lang); ?>><?php echo total_reviews_text('Portuguese'); ?></option>
                <option value="pt-BR" <?php selected('pt-BR', $reviews_lang); ?>><?php echo total_reviews_text('Portuguese (Brazil)'); ?></option>
                <option value="pt-PT" <?php selected('pt-PT', $reviews_lang); ?>><?php echo total_reviews_text('Portuguese (Portugal)'); ?></option>
                <option value="ro" <?php selected('ro', $reviews_lang); ?>><?php echo total_reviews_text('Romanian'); ?></option>
                <option value="ru" <?php selected('ru', $reviews_lang); ?>><?php echo total_reviews_text('Russian'); ?></option>
                <option value="sk" <?php selected('sk', $reviews_lang); ?>><?php echo total_reviews_text('Slovak'); ?></option>
                <option value="sl" <?php selected('sl', $reviews_lang); ?>><?php echo total_reviews_text('Slovenian'); ?></option>
                <option value="sr" <?php selected('sr', $reviews_lang); ?>><?php echo total_reviews_text('Serbian'); ?></option>
                <option value="sv" <?php selected('sv', $reviews_lang); ?>><?php echo total_reviews_text('Swedish'); ?></option>
                <option value="ta" <?php selected('ta', $reviews_lang); ?>><?php echo total_reviews_text('Tamil'); ?></option>
                <option value="te" <?php selected('te', $reviews_lang); ?>><?php echo total_reviews_text('Telugu'); ?></option>
                <option value="th" <?php selected('th', $reviews_lang); ?>><?php echo total_reviews_text('Thai'); ?></option>
                <option value="tl" <?php selected('tl', $reviews_lang); ?>><?php echo total_reviews_text('Tagalog'); ?></option>
                <option value="tr" <?php selected('tr', $reviews_lang); ?>><?php echo total_reviews_text('Turkish'); ?></option>
                <option value="uk" <?php selected('uk', $reviews_lang); ?>><?php echo total_reviews_text('Ukrainian'); ?></option>
                <option value="vi" <?php selected('vi', $reviews_lang); ?>><?php echo total_reviews_text('Vietnamese'); ?></option>
                <option value="zh-CN" <?php selected('zh-CN', $reviews_lang); ?>><?php echo total_reviews_text('Chinese (Simplified)'); ?></option>
                <option value="zh-TW" <?php selected('zh-TW', $reviews_lang); ?>><?php echo total_reviews_text('Chinese (Traditional)'); ?></option>
            </select>
        </div>
    </div>
</div>
