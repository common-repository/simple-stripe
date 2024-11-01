<?php

defined( 'ABSPATH' ) || exit;



add_shortcode('simple_stripe', 'simple_stripe_shortcode');

function simple_stripe_shortcode($atts) {

	$load_setting = get_option('simple_stripe_settings');

	if ( !$load_setting ) return;

	$load_setting = shortcode_atts( $load_setting, $atts, 'stripe' );

	add_action('wp_footer', 'simple_stripe_checkout_script');



	require_once SIMPLE_STRIPE_DIR . 'inc/currencies.php';

	$load_setting['symbol'] = simple_stripe_currencies();

	if($load_setting['amount_mode'] === 'freely' && $load_setting['minimum_amount'] !== ''){
		$load_setting['minimum_amount'] = (int)$load_setting['minimum_amount'];
	}else{
		$load_setting['minimum_amount'] = $load_setting['symbol'][$load_setting['currency']][2];
	}

	$load_setting['symbol'] = $load_setting['symbol'][$load_setting['currency']][1];

	if($load_setting['payment_mode'] === 'legacy'){
		
		if($load_setting['amount_mode'] === 'fixed'){
			$load_setting['amount_mode'] = 'none';
		}else{
			$load_setting['amount_mode'] = 'flex';
		}

		$front_code = '<div style="text-align: center;">';
		$front_code .= '<div style="display:'.$load_setting['amount_mode'].';justify-content: center;align-items: center;">';
		$front_code .= '<span class="currency" style="padding:4px;margin:0;font-size:24px;line-height:1;">'.$load_setting['symbol'].'</span>';
		$front_code .= '<input type="number" name="simple_stripe_amount" class="simple_stripe_amount" value="'. intval( $load_setting['amount'] ) .'" placeholder="0" autocomplete="off" onpaste="return false;" required="" onkeydown="return simple_stripe_amount_key(event.keyCode);" data-currency="'. esc_attr( $load_setting['currency'] ) .'" data-name="'. esc_attr( $load_setting['name'] ) .'" data-image="'. esc_url( $load_setting['image'] ) .'" data-description="'. esc_attr( $load_setting['description'] ) .'" data-panelLabel="'. esc_attr( $load_setting['panellabel'] ) .'" data-minimum_amount="'. esc_attr( $load_setting['minimum_amount'] ) .'" data-locale="'. esc_attr( $load_setting['locale'] ) .'" data-shippingAddress="'. esc_attr( $load_setting['address'] ) .'" data-zipCode="'. esc_attr( $load_setting['zip'] ) .'" data-allowRememberMe="'. esc_attr( $load_setting['remember'] ) .'" style="max-width:140px;display:block;padding:4px;border:2px solid #eee;margin:0;height:40px;font-size:24px;line-height:1;text-align:right;" />';
		$front_code .= '</div>';
		$front_code .= '<button onclick="simple_stripe_checkin(event,this.previousElementSibling.children[1],this.nextElementSibling.firstElementChild);" style="margin:16px 0;">'. esc_html( $load_setting['open_button'] ) .'</button>';
		$front_code .= '<div id="simple_stripe_message_wrap"><span class="simple_stripe_message" style="display:none;padding: 8px;margin:18px 0;"></span></div>';
		$front_code .= '</div>';

		return $front_code;

	}else{
		

		$uniqid = uniqid();

		if($load_setting['amount_mode'] === 'fixed'){
			$load_setting['disabled'] = ' disabled';
		}else{
			$load_setting['disabled'] = '';
		}

		if($load_setting['panellabel'] !== ''){
			$load_setting['pay_label'] = $load_setting['panellabel'];
		}else{
			$load_setting['pay_label'] = esc_html__('Pay','simple-stripe');
		}

		if( $load_setting['zip'] === 'false'){
			$load_setting['zip'] = 'true';
		}else{
			$load_setting['zip'] = 'false';
		}

		$return_url = esc_url($load_setting['redirect_url_success_pay']);
		global $wp;
		$current_url = add_query_arg( $wp->query_string, '' , home_url( $wp->request ) );
		$here_url = strtok( $current_url, '?' );

		$front_code = '<div class="simple_stripe_wrap">';



		$front_code .= '<form method="post" id="payment-form_'.$uniqid.'">';



		$front_code .= '<div style="display:flex;justify-content: center;align-items: center;">';

		$front_code .= '<span class="currency" style="padding:4px;margin:0;font-size:24px;line-height:1;">'.$load_setting['symbol'].'</span>';
		$front_code .= '<input type="number" name="simple_stripe_amount" class="simple_stripe_amount" value="'. intval( $load_setting['amount'] ) .'" placeholder="0" autocomplete="off" onpaste="return false;" required="" onkeydown="return simple_stripe_amount_key(event.keyCode);" data-currency="'. esc_attr( $load_setting['currency'] ) .'" data-name="'. esc_attr( $load_setting['name'] ) .'" data-image="'. esc_url( $load_setting['image'] ) .'" data-description="'. esc_attr( $load_setting['description'] ) .'" data-panelLabel="'. esc_attr( $load_setting['panellabel'] ) .'" data-minimum_amount="'. esc_attr( $load_setting['minimum_amount'] ) .'" data-locale="'. esc_attr( $load_setting['locale'] ) .'" data-shippingAddress="'. esc_attr( $load_setting['address'] ) .'" data-hidePostalCode="'. esc_attr( $load_setting['zip'] ) .'" data-allowRememberMe="'. esc_attr( $load_setting['remember'] ) .'" data-uniqid="'.$uniqid.'" data-return_url="'.esc_url($return_url).'" data-here_url="'.esc_url($here_url).'" style="max-width:140px;display:block;padding:4px;border:2px solid #eee;margin:0;height:40px;font-size:24px;line-height:1;text-align:right;"'.$load_setting['disabled'].' />';

		$front_code .= '</div>';





		$front_code .= '<div class="form-row" style="max-width: 420px;margin: auto;width: 100%;">';
		$front_code .= '<label for="card-element">'.esc_html__('Credit or debit card','simple-stripe').'</label>';
		$front_code .= '<div id="card-element_'.$uniqid.'" class="ss_card-element" style="padding: 8px;background: #f5f5f5;border-radius: 4px;margin-bottom: 8px;"><!-- Your form goes here --></div>';
		$front_code .= '<!-- Used to display form errors -->';
		$front_code .= '<div>';
		$front_code .= '<div id="card-errors_'.$uniqid.'" role="alert"></div>';
		$front_code .= '<div id="card-status_'.$uniqid.'" style="display:none;padding:8px;margin:8px 0 16px;"></div>';
		$front_code .= '</div>';
		$front_code .= '</div>';
		$front_code .= '<div style="text-align:center;"><button id="pay_'.$uniqid.'" type="button" onclick="simple_stripe_createToken(event)">'.esc_html($load_setting['pay_label']).'</button></div>';
		$front_code .= '</form>';

		$front_code .= '</div>';

		return $front_code;

	}


}

function simple_stripe_checkout_script(){

	$load_setting = get_option('simple_stripe_settings');

	if($load_setting['api_mode'] === 'test'){
		$load_setting['publishable_key'] = $load_setting['pk_test'];
	}else{
		$load_setting['publishable_key'] = $load_setting['pk_live'];
	}



	$localize = array(
		'publishable_key' => $load_setting['publishable_key'],
		'nonce' => wp_create_nonce( 'simple_stripe_nonce' ),
		'msg_successful' => __('Payment was successful','simple-stripe'),
		'msg_failed' => __('Payment failed','simple-stripe'),
		'msg_connecting' => __('Connecting...','simple-stripe'),
		'msg_minimum_amount' => __('Falls below the minimum amount prescribed by Stripe.','simple-stripe'),
		'admin_ajax' => esc_url( admin_url('admin-ajax.php') ),
		'redirect_url_success_pay' => esc_url($load_setting['redirect_url_success_pay']),
	);


	

	if($load_setting['payment_mode'] === 'legacy'){
		wp_enqueue_script('checkout_stripe_legacy','https://checkout.stripe.com/checkout.js', array(), null , true);
		wp_enqueue_script('simple_stripe_legacy', SIMPLE_STRIPE_URI .'assets/js/simple-stripe_legacy.min.js', array('checkout_stripe_legacy'), SIMPLE_STRIPE_VERSION , true);
		wp_localize_script( 'simple_stripe_legacy', 'simple_stripe_legacy', $localize );

	}else{
		wp_enqueue_script('checkout_stripe_new','https://js.stripe.com/v3/', array(), null , true);
		wp_enqueue_script('simple_stripe_new', SIMPLE_STRIPE_URI .'assets/js/simple-stripe_new.min.js', array('checkout_stripe_new'), SIMPLE_STRIPE_VERSION , true);
		wp_localize_script( 'simple_stripe_new', 'simple_stripe_new', $localize );

	}
	




}



