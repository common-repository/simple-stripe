<?php

defined( 'ABSPATH' ) || exit;


add_action( 'wp_ajax_simple_stripe_ajax', 'simple_stripe_payment' );
add_action( 'wp_ajax_nopriv_simple_stripe_ajax', 'simple_stripe_payment' );

function simple_stripe_payment(){

	check_ajax_referer( "simple_stripe_nonce" , 'nonce' );

	if ( !wp_verify_nonce( $_POST['nonce'], 'simple_stripe_nonce' ) ) {
		wp_die();
	}

	$checked = false;

	require_once SIMPLE_STRIPE_DIR.'stripe-php-master/init.php';

	$load_setting = get_option('simple_stripe_settings');

	if($load_setting['api_mode'] === 'test'){
		$load_setting['secret_key'] = $load_setting['sk_test'];
	}else{
		$load_setting['secret_key'] = $load_setting['sk_live'];
	}


	if( isset($_POST['amount']) ){
		$data['amount'] = (int) sanitize_text_field( $_POST['amount'] );
	}else{
		wp_die();
	}

	if( isset($_POST['currency']) ){
		$data['currency'] = sanitize_text_field( $_POST['currency'] );
	}else{
		wp_die();
	}

	if( isset($_POST['token']) ){
		$data['card'] = sanitize_text_field( $_POST['token'] );
	}else{
		wp_die();
	}

	if( isset($_POST['return_url']) && !empty($_POST['return_url']) && $_POST['return_url'] !== '' ){
		$data['return_url'] = esc_url( $_POST['return_url'] );
	}else{
		$data['return_url'] = esc_url( $_POST['here_url'] );
	}

	if( $load_setting['secret_key'] === '' || !isset($data['amount']) || !isset($data['currency']) || !isset($data['card']) ){
		echo json_encode( array(
			"message" => __( 'Settings for this payment are incomplete.', 'simple-stripe' )
		));
		wp_die();
	}

	if( isset($_POST['description']) ){
		$data['description'] = sanitize_text_field( urldecode( $_POST['description'] ) );
	}else{
		$data['description'] = '';
	}

	if($load_setting['payment_mode'] === 'new'){
		if( isset($_POST['paymentMethod_id']) ){
			$data['paymentMethod_id'] = sanitize_text_field( $_POST['paymentMethod_id'] );
		}else{
			wp_die();
		}
	}




	\Stripe\Stripe::setApiKey( $load_setting['secret_key'] );

	try{

		if($load_setting['payment_mode'] === 'legacy'){

			$charge = \Stripe\Charge::create([
				'description' => $data['description'],
				'amount' => $data['amount'],
				'currency' => $data['currency'],
				'card' => $data['card']
			]);

		}else{

			//$stripe = new \Stripe\StripeClient($load_setting['secret_key']);

/*
			$intent = $stripe->paymentIntents->create([
				'amount' => $data['amount'],
				'currency' => $data['currency'],
				'confirm' => true,
				'description' => $data['description'],
				'return_url' => esc_url($data['return_url']),
				'payment_method_types' => ['card'],
				'payment_method' => $data['paymentMethod_id'],
			]);
*/
			$intent = \Stripe\PaymentIntent::create([
				'amount' => $data['amount'],
				'currency' => $data['currency'],
				'confirm' => true,
				'description' => $data['description'],
				'return_url' => esc_url($data['return_url']),
				'payment_method_types' => ['card'],
				'payment_method' => $data['paymentMethod_id'],
			]);

		}

	} catch (Stripe_ApiConnectionError $e) {
		echo json_encode( array(
			"message" => __( 'We couldn\'t payment due to network problems.', 'simple-stripe' ).__( 'Please try again later.', 'simple-stripe' )
		) );
		wp_die();
	} catch (Stripe_InvalidRequestError $e) {
		echo json_encode( array(
			"message" => __( 'We couldn\'t payment due to a program error.', 'simple-stripe' )
		) );
		wp_die();
	} catch (Stripe_ApiError $e) {
		echo json_encode( array(
			"message" => __( 'Stripe\'s server is down.', 'simple-stripe' ).__( 'Please try again later.', 'simple-stripe' )
		));
		wp_die();
	} catch (Stripe_CardError $e) {
		echo json_encode( array(
			"message" => __( 'Card company has stopped payment.', 'simple-stripe' )
		));
		wp_die();
	}


	if($load_setting['payment_mode'] === 'legacy'){
		if($charge["paid"] && $charge["status"] === "succeeded") $checked = true;
	}else{
		if($intent["status"] === "succeeded") $checked = true;
	}


	if($checked){
		echo json_encode( array(
			"message" => "OK"
		));
	}else{
		echo json_encode( array(
			"message" => __( 'The payment failed for an unknown reason.', 'simple-stripe' )
		));
	}

	wp_die();

}

