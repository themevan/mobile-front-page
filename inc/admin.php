<?php MobileFrontPage::save_settings();?>
<div class="wrap">

	<h1>Mobile Front Page</h1>

	<h3><?php esc_html_e('Set the different front page for each kinds of device.','mfp');?></h3>

	<form method="post" action="<?php echo admin_url('options-general.php?page=mobile-front-page');?>">
	    
	    <?php settings_fields( 'mfp-settings-group' ); ?>

	    <?php do_settings_sections( 'mfp-settings-group' ); ?>
	    <table class="form-table">

	        <?php do_action('mfp_before_options');?>

	        <tr valign="top">
	           <th scope="row">iPhone</th>
	           <td><?php MobileFrontPage::page_selector('iphone_page',get_option('iphone_page'));?></td>
	        </tr>

	        <tr valign="top">
	           <th scope="row">Android Phone</th>
	           <td><?php MobileFrontPage::page_selector('android_phone_page',get_option('android_phone_page'));?></td>
	        </tr>

	        <tr valign="top">
	           <th scope="row">iPad</th>
	           <td><?php MobileFrontPage::page_selector('ipad_page',get_option('ipad_page'));?></td>
	        </tr>

	        <tr valign="top">
	           <th scope="row">Android Tablet</th>
	           <td><?php MobileFrontPage::page_selector('android_tablet_page',get_option('android_tablet_page'));?></td>
	        </tr>

	        <?php do_action('mfp_after_options');?>

	    </table>
	    
	    <?php submit_button(); ?>
	    <input type="hidden" name="action" value="save" />
	</form>

</div>