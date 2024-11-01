<?php

defined( 'ABSPATH' ) || exit;




function simple_stripe_add_menu() {

	$s_s_submenu_page = add_options_page(__('Setting Up Simple Stripe','simple-stripe'),'Simple Stripe', 'administrator' , 'simple-stripe','simple_stripe_admin_page');
	add_action( "admin_print_scripts-$s_s_submenu_page", 'simple_stripe_only_edit_page_scripts' );

}
add_action( 'admin_menu', 'simple_stripe_add_menu' );


function simple_stripe_admin_page() {



	require_once SIMPLE_STRIPE_DIR . 'inc/currencies.php';



	?>

	<style>
		#ss_loading {
			width: 60px;
			height: 60px;
			border: 10px solid #f6f2ef;
			border-top-color: #00b9eb;
			border-radius: 50%;
			animation: ss_loading_spin 1.2s linear 0s infinite;
			text-align: center;
			z-index: 10;
			position:absolute;
			top: 0;
			bottom: 0;
			left: 0;
			right: 0;
			margin: auto;
		}
		@keyframes ss_loading_spin {
			0% {transform: rotate(0deg);}
			100% {transform: rotate(360deg);}
		}
		#ss_loading_bg{
			width: 100%;
			height: 100%;
			z-index: 10000;
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: rgba(0,0,0,0.90);
			overflow: hidden;
			overflow-y: auto;
			-webkit-overflow-scrolling: touch;
			-webkit-backface-visibility: hidden;
			backface-visibility: hidden;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			-o-box-sizing: border-box;
			-ms-box-sizing: border-box;
			box-sizing: border-box;
		}
	</style>

	<div id="ss_loading_bg"><div id="ss_loading"></div></div>
	<div id="ss_pop_up_message"></div>

	<input id="ss_settings" class="tabs" type="radio" name="tab_item" checked="">
	<input id="ss_shortcode" class="tabs" type="radio" name="tab_item">

	<div id="simple_stripe_header" class="simple_stripe_header">
		<div class="ss_flex ss_ai_c ss_jc_c">
			<img width="254" height="80" src="<?php echo SIMPLE_STRIPE_URI; ?>assets/images/simple-stripe.svg" alt="Simple Stripe" style="margin: 20px auto 4px;">
		</div>
		<div class="ss_admin_edit_version">
			<?php echo SIMPLE_STRIPE_VERSION; ?>
		</div>

		<div class="ss_flex ss_ai_c ss_o_s_t">
			<label id="ss_settings_label" class="tab_item" for="ss_settings"><?php esc_html_e('Settings','simple-stripe'); ?></label>
			<label id="ss_shortcode_label" class="tab_item" for="ss_shortcode"><?php esc_html_e('Shortcode','simple-stripe'); ?></label>
		</div>
	</div>

	<?php
	if (isset($_POST['posted']) && $_POST['posted'] === 'simple_stripe_update') {

		$update_settings = array(
			'api_mode' => sanitize_text_field( $_POST['simple_stripe_api_mode'] ),
			'payment_mode' => sanitize_text_field( $_POST['simple_stripe_payment_mode'] ),
			'pk_test' => sanitize_text_field( $_POST['simple_stripe_pk_test'] ),
			'sk_test' => sanitize_text_field( $_POST['simple_stripe_sk_test'] ),
			'pk_live' => sanitize_text_field( $_POST['simple_stripe_pk_live'] ),
			'sk_live' => sanitize_text_field( $_POST['simple_stripe_sk_live'] ),
			'image' => sanitize_text_field( $_POST['simple_stripe_logo_image'] ),
			'currency' => sanitize_text_field( $_POST['simple_stripe_currency'] ),
			'amount' => sanitize_text_field( $_POST['simple_stripe_amount'] ),
			'name' => sanitize_text_field( $_POST['simple_stripe_name'] ),
			'description' => sanitize_text_field( $_POST['simple_stripe_description'] ),
			'panellabel' => sanitize_text_field( $_POST['simple_stripe_panellabel'] ),
			'amount_mode' => sanitize_text_field( $_POST['simple_stripe_amount_mode'] ),
			'minimum_amount' => sanitize_text_field( $_POST['simple_stripe_minimum_amount'] ),
			'open_button' => sanitize_text_field( $_POST['simple_stripe_open_button'] ),
			'locale' => sanitize_text_field( $_POST['simple_stripe_locale'] ),
			'address' => sanitize_text_field( $_POST['simple_stripe_address'] ),
			'zip' => sanitize_text_field( $_POST['simple_stripe_zip'] ),
			'remember' => sanitize_text_field( $_POST['simple_stripe_remember'] ),
			'simple_stripe_version' => SIMPLE_STRIPE_VERSION,
			'redirect_url_success_pay' => sanitize_text_field( $_POST['simple_stripe_redirect_url_success_pay'] ),
		);

		update_option('simple_stripe_settings', $update_settings );

		echo '<div id="message" class="updated notice notice-success is-dismissible notice-alt updated-message"><p>'.__('Settings updated successfully.','simple-stripe').'</p></div>'; 

	}


	?>

	<div id="simple_stripe" class="simple_stripe_wrap">

		<div id="ss_settings_content" class="tab_content ss_box_design">

			<?php
			require_once SIMPLE_STRIPE_DIR . 'inc/admin-settings.php';
			simple_stripe_admin_settings();
			?>

		</div>

		<div id="ss_shortcode_content" class="tab_content ss_box_design">

			<?php
			require_once SIMPLE_STRIPE_DIR . 'inc/admin-shortcode.php';
			simple_stripe_admin_shorcode();
			?>

		</div>

		<div class="ss_sub_menu ss_box_design">
			<a href="https://wordpress.org/support/plugin/simple-stripe/" class="" target="_blank">
				<?php esc_html_e('Support Forum','simple-stripe'); ?>
				<p style="margin: 4px 0 0;">
					<i class="fa-external-link" aria-hidden="true" style=""></i> WordPress.org
				</p>
			</a>
		</div>

	</div>

	<?php
}


function simple_stripe_locales(){
	
	return array(
		'auto' => __( 'Auto' , 'simple-stripe' ),
		'ar' => __( 'Arabic' , 'simple-stripe' ),
		'bg' => __( 'Bulgarian (Bulgaria)' , 'simple-stripe' ),
		'cs' => __( 'Czech (Czech Republic)' , 'simple-stripe' ),
		'da' => __( 'Danish' , 'simple-stripe' ),
		'de' => __( 'German (Germany)' , 'simple-stripe' ),
		'el' => __( 'Greek (Greece)' , 'simple-stripe' ),
		'en' => __( 'English' , 'simple-stripe' ),
		'en-GB' => __( 'English (United Kingdom)' , 'simple-stripe' ),
		'es' => __( 'Spanish (Spain)' , 'simple-stripe' ),
		'es-419' => __( 'Spanish (Latin America)' , 'simple-stripe' ),
		'et' => __( 'Estonian (Estonia)' , 'simple-stripe' ),
		'fi' => __( 'Finnish (Finland)' , 'simple-stripe' ),
		'fil' => __( 'Filipino (Philipines)' , 'simple-stripe' ),
		'fr' => __( 'French (France)' , 'simple-stripe' ),
		'fr-CA' => __( 'French (Canada)' , 'simple-stripe' ),
		'he' => __( 'Hebrew (Israel)' , 'simple-stripe' ),
		'hr' => __( 'Croatian (Croatia)' , 'simple-stripe' ),
		'hu' => __( 'Hungarian (Hungary)' , 'simple-stripe' ),
		'id' => __( 'Indonesian (Indonesia)' , 'simple-stripe' ),
		'it' => __( 'Italian (Italy)' , 'simple-stripe' ),
		'ja' => __( 'Japanese' , 'simple-stripe' ),
		'ko' => __( 'Korean (Korea)' , 'simple-stripe' ),
		'lt' => __( 'Lithuanian (Lithuania)' , 'simple-stripe' ),
		'lv' => __( 'Latvian (Latvia)' , 'simple-stripe' ),
		'ms' => __( 'Malay (Malaysia)' , 'simple-stripe' ),
		'mt' => __( 'Maltese (Malta)' , 'simple-stripe' ),
		'nb' => __( 'Norwegian Bokmål' , 'simple-stripe' ),
		'nl' => __( 'Dutch (Netherlands)' , 'simple-stripe' ),
		'pl' => __( 'Polish (Poland)' , 'simple-stripe' ),
		'pt-BR' => __( 'Portuguese (Brazil)' , 'simple-stripe' ),
		'pt' => __( 'Portuguese (Brazil)' , 'simple-stripe' ),
		'ro' => __( 'Romanian (Romania)' , 'simple-stripe' ),
		'ru' => __( 'Russian (Russia)' , 'simple-stripe' ),
		'sk' => __( 'Slovak (Slovakia)' , 'simple-stripe' ),
		'sl' => __( 'Slovenian (Slovenia)' , 'simple-stripe' ),
		'sv' => __( 'Swedish (Sweden)' , 'simple-stripe' ),
		'th' => __( 'Thai' , 'simple-stripe' ),
		'tr' => __( 'Turkish (Turkey)' , 'simple-stripe' ),
		'vi' => __( 'Vietnamese (Vietnam)' , 'simple-stripe' ),
		'zh' => __( 'Chinese Simplified (China)' , 'simple-stripe' ),
		'zh-HK' => __( 'Chinese Traditional (Hong Kong)' , 'simple-stripe' ),
		'zh-TW' => __( 'Chinese Traditional (Taiwan)' , 'simple-stripe' ),
	);
}


function simple_stripe_only_edit_page_scripts() {

	
	wp_enqueue_media();
	wp_enqueue_style('simple_stripe_admin',SIMPLE_STRIPE_URI . 'assets/css/admin.min.css',array() , SIMPLE_STRIPE_VERSION);
	wp_enqueue_style('simple_stripe_fontawesome',SIMPLE_STRIPE_URI . 'assets/fonts/fontawesome/style.min.css',array() , SIMPLE_STRIPE_VERSION);
	wp_enqueue_script('simple_stripe_admin',SIMPLE_STRIPE_URI . 'assets/js/admin.min.js',array(), SIMPLE_STRIPE_VERSION , true);

	wp_localize_script( 'simple_stripe_admin', 'simple_stripe_admin', array(
		'select_image' => __('Select image','simple-stripe'),
		'copy' => __('Copied','simple-stripe'),
	) );
}







function simple_stripe_plugin_action_links($links, $file) {
	if ('simple-stripe/simple-stripe.php' == $file  && current_user_can( 'manage_options' )) {
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=simple-stripe' ) . '">'.__( 'Settings', 'simple-stripe' ).'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'simple_stripe_plugin_action_links', 10, 2);



add_shortcode('simple_stripe', '__return_false');



add_action( 'admin_init', 'simple_stripe_version_check' );
function simple_stripe_version_check() {

	$load_setting = get_option('simple_stripe_settings');

	$check = false;

	if( isset($load_setting['simple_stripe_version']) && SIMPLE_STRIPE_VERSION === $load_setting['simple_stripe_version'] ){
		$check = true;
	}

	if(!$check){
		simple_stripe_update_option();
	}

}

function simple_stripe_update_option() {

	$load_setting = get_option('simple_stripe_settings');
	if ( !$load_setting ) {
		$load_setting['api_mode'] = 'test';
		$load_setting['pk_test'] = '';
		$load_setting['sk_test'] = '';
		$load_setting['pk_live'] = '';
		$load_setting['sk_live'] = '';
		$load_setting['image'] = '';
		$load_setting['currency'] = get_locale() === 'ja' ? 'JPY' : 'USD';
		$load_setting['amount'] = 10;
		$load_setting['name'] = esc_html__( get_bloginfo() );
		$load_setting['description'] = esc_html__( 'Have a great day', 'simple-stripe' );
		$load_setting['panellabel'] = '';
		$load_setting['amount_mode'] = 'freely';
		$load_setting['minimum_amount'] = '';
		$load_setting['open_button'] = esc_html__('Pay','simple-stripe');
		$load_setting['remember'] = 'false';
		$load_setting['locale'] = 'auto';
		$load_setting['zip'] = 'false';
		$load_setting['address'] = 'false';
		$load_setting['payment_mode'] = 'legacy';
		$load_setting['redirect_url_success_pay'] = '';
	}

	if( !isset($load_setting['simple_stripe_version']) ){
		$load_setting['payment_mode'] = 'legacy';//add 0.9.5
	}
	if( !isset($load_setting['redirect_url_success_pay']) ){
		$load_setting['redirect_url_success_pay'] = '';//add 0.9.9
	}
	if( !isset($load_setting['minimum_amount']) ){
		$load_setting['minimum_amount'] = '';//add 0.9.11
	}
	$load_setting['simple_stripe_version'] = SIMPLE_STRIPE_VERSION;
	update_option('simple_stripe_settings', $load_setting );

}