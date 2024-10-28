<?php
/*
Plugin Name: BCT for Gravity Forms
Description: Button Click Text is a really simple way for people using your form to see that your form is actually working when they push the submit button. The "click text" will change from the original submit text to anything you desire.
Version: 1.0.1
Author: Frog Eat Fly
Tested up to: 6.1
Author URI: https://gformsdemo.com/

*/

define( 'GF_BCT_VERSION', '1.0.1' );
define('GF_BCT_DIR', plugin_dir_path(__FILE__) );
define('GF_BCT_URL', plugin_dir_url(__FILE__));


add_action( 'gform_loaded', array( 'GF_BCT_ADDON', 'load' ), 35 );

class GF_BCT_ADDON {

	public static function load() {

		if ( ! method_exists( 'GFForms', 'include_feed_addon_framework' ) ) {
			return;
		}
		
		if ( ! file_exists( GF_BCT_DIR.'class-button-text-addon.php' ) ) {
			define( 'BCT_PLUGIN_TYPE', 0 );			
		}
		else {			
			require_once( 'class-button-text-addon.php' );
			GFAddOn::register( 'GFButtonTextAddOn' );
		}		
	}	
}

add_action( 'wp_enqueue_scripts', 'gravitybct_register_assets', 10 );
add_action( 'admin_enqueue_scripts', 'gravitybct_register_assets', 10 );
function gravitybct_register_assets() {
	wp_register_script( 'bct-submit', plugin_dir_url( __FILE__ ).'js/button.js', array('jquery'), GF_BCT_VERSION, true );
	wp_enqueue_script( 'bct-submit' );
}

add_action( 'plugins_loaded', 'gravitybct_load_language', 0 );
function gravitybct_load_language() {
	load_plugin_textdomain( 'gravitybct', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}


add_filter('gform_pre_render', 'bct_pre_render_setup', 100, 1);
function bct_pre_render_setup($form) {
	$form_id = $form['id'];
	$button = $form['button'];
		
	$button_click_text = ! empty( $button['bct_default_click_text'] ) ? $button['bct_default_click_text'] :  false ;
	$button_default_text = ! empty( $button['text'] ) ? $button['text'] : __( 'Submit', 'gravitybct' );
		
	$custom_button_text['default'] = $button_default_text;
	$custom_button_text['default_click_text'] = $button_click_text;
	
	$custom_button_data['text'] = $custom_button_text;
	$custom_button_data['form_id'] = $form_id;
	
	if( $button_click_text ) {
		echo "<script type='text/javascript'> 
				if( typeof custom_button_data == 'undefined' || ! Array.isArray(custom_button_data) ) {
					custom_button_data = new Array();
				}
				custom_button_data['$form_id'] = ".json_encode($custom_button_data)."
			</script>";
	}
	
	
	return $form;
}


add_action( 'gform_field_standard_settings', 'bct_field_settings', 10, 2 );
function bct_field_settings( $position, $form_id ) {
	$form = GFAPI::get_form($form_id);
	$button = $form['button'];
	$button_click_text = ! empty( $button['bct_default_click_text'] ) ? $button['bct_default_click_text'] : '';
	
	if($position == 5){ ?>
		<li class="submit_text_setting field_setting" style="">
			<label for="switch_submit_text" class="section_label"><?php _e( 'Switch Text', 'gravitybct') ?></label>
			<input type="text" id="switch_submit_text" name="bct_default_click_text" value="<?php echo $button_click_text ?>">
		</li>			
		<?php
	}
}


add_action('gform_editor_js', 'bct_editor_script', 12);
function bct_editor_script(){
	?>
	<script type='text/javascript'>
		jQuery(document).bind("gform_load_field_settings", function(event, field, form){			
			if(field.type == 'submit' ) {
				setTimeout(function() {
					jQuery("#general_tab .submit_text_setting").show();
				}, 100);
			}
			else {
				setTimeout(function() {
					jQuery("#general_tab .submit_text_setting").hide();
				}, 100);
			}			
		});
		
		jQuery('#switch_submit_text').on('input propertychange', function(){
			form.button.bct_default_click_text = jQuery(this).val();
		});
		
	</script>
	<?php
}






















