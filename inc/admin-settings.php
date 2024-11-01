<?php

defined( 'ABSPATH' ) || exit;

function simple_stripe_admin_settings() {

	$load_setting = get_option('simple_stripe_settings');

	if ( !$load_setting ) {
		simple_stripe_update_option();
		$load_setting = get_option('simple_stripe_settings');
	}

	?>

	<form name="simple_stripe_edit_form" method="post" action="<?php print wp_nonce_url( admin_url('options-general.php?page=simple-stripe') , 'simple_stripe_nonce_field_action', 'simple_stripe_nonce_name');?>" onsubmit="return false;" style="max-width:600px;">
		<?php wp_nonce_field( 'simple_stripe_nonce_field_action','simple_stripe_nonce_name' ); ?>
		<input type="hidden" name="posted" value="simple_stripe_update">
		<h2><?php _e('Stripe API key','simple-stripe'); ?></h2>
		<div style="margin-bottom: 12px;">
			<label for=""><?php _e('Mode','simple-stripe'); ?></label><br>
			<input type="radio" name="simple_stripe_api_mode" value="test"<?php checked( $load_setting['api_mode'], "test" ); ?>><?php _e('Test','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_api_mode" value="live"<?php checked( $load_setting['api_mode'], "live" ); ?>><?php _e('Live','simple-stripe'); ?>
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_pk_test"><?php _e('Publishable key','simple-stripe')._e('(test)','simple-stripe'); ?></label><br>
			<input id="simple_stripe_pk_test" name="simple_stripe_pk_test" class="widefat" type="text" placeholder="<?php _e('Test publishable key','simple-stripe'); ?>" maxlength="300" value="<?php echo $load_setting['pk_test']; ?>" />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_sk_test"><?php _e('Secret key','simple-stripe')._e('(test)','simple-stripe'); ?></label><br>
			<input id="simple_stripe_sk_test" name="simple_stripe_sk_test" class="widefat" type="text" placeholder="<?php _e('Test secret key','simple-stripe'); ?>" maxlength="300" value="<?php echo $load_setting['sk_test']; ?>" />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_pk_live"><?php _e('Publishable key','simple-stripe')._e('(live)','simple-stripe'); ?></label><br>
			<input id="simple_stripe_pk_live" name="simple_stripe_pk_live" class="widefat" type="text" placeholder="<?php _e('Live publishable key','simple-stripe'); ?>" maxlength="300" value="<?php echo $load_setting['pk_live']; ?>" />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_sk_live"><?php _e('Secret key','simple-stripe')._e('(live)','simple-stripe'); ?></label><br>
			<input id="simple_stripe_sk_live" name="simple_stripe_sk_live" class="widefat" type="text" placeholder="<?php _e('Live secret key','simple-stripe'); ?>" maxlength="300" value="<?php echo $load_setting['sk_live']; ?>" />
		</div>
		<hr>
		<h2><?php _e('Front end','simple-stripe'); ?></h2>

		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_name"><?php _e('Name','simple-stripe'); ?></label><br>
			<input id="simple_stripe_name" name="simple_stripe_name" class="widefat" type="text" placeholder="<?php _e('Name','simple-stripe'); ?>" maxlength="100" value="<?php echo $load_setting['name']; ?>" />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_description"><?php _e('Description','simple-stripe'); ?></label><br>
			<input id="simple_stripe_description" name="simple_stripe_description" class="widefat" type="text" placeholder="<?php _e('Description','simple-stripe'); ?>" maxlength="100" value="<?php echo $load_setting['description']; ?>" />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_currency"><?php _e('Currency','simple-stripe'); ?></label><br>
			<select id="simple_stripe_currency" name="simple_stripe_currency">
				<?php
				foreach (simple_stripe_currencies() as $key => $value) {
					echo '<option value="'.$key.'"'.selected( $load_setting['currency'], $key , false ).'>'.$key.' ('. $value[0] . ' ' . $value[1] .')</option>';
				}
				?>
			</select>
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_amount"><?php _e('Amount','simple-stripe'); ?></label><br>
			<input id="simple_stripe_amount" name="simple_stripe_amount" class="" type="number" min="0" placeholder="<?php _e('Amount','simple-stripe'); ?>" maxlength="100" value="<?php echo $load_setting['amount']; ?>" />
		</div>

		<div style="margin-bottom: 12px;">
			<label for=""><?php _e('Amount adjustment','simple-stripe'); ?></label><br>
			<input type="radio" name="simple_stripe_amount_mode" value="freely"<?php checked( $load_setting['amount_mode'], "freely" ); ?>><?php _e('Freely','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_amount_mode" value="fixed"<?php checked( $load_setting['amount_mode'], "fixed" ); ?>><?php _e('Fixed','simple-stripe'); ?>
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_minimum_amount"><?php _e('Minimum amount','simple-stripe'); ?> (<?php _e('Enable when amount adjustment is freely','simple-stripe'); ?>)</label><br>
			<input id="simple_stripe_minimum_amount" name="simple_stripe_minimum_amount" class="" type="number" placeholder="" value="<?php echo $load_setting['minimum_amount']; ?>" />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_panellabel"><?php _e('Payment button label','simple-stripe'); ?></label><br>
			<input id="simple_stripe_panellabel" name="simple_stripe_panellabel" class="" type="text" placeholder="<?php _e('Payment button label','simple-stripe'); ?>" maxlength="100" value="<?php echo $load_setting['panellabel']; ?>" />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_locale"><?php _e('Locale','simple-stripe'); ?></label><br>
			<select id="simple_stripe_locale" name="simple_stripe_locale">
				<?php
				foreach (simple_stripe_locales() as $key => $value) {
					echo '<option value="'.$key.'"'.selected( $load_setting['locale'], $key , false ).'>'.$key.' ('. $value .')</option>';
				}
				?>
			</select>
		</div>
		<div style="margin-bottom: 12px;">
			<label for=""><?php _e('Shipping Address','simple-stripe'); ?></label><br>
			<input type="radio" name="simple_stripe_address" value="true"<?php checked( $load_setting['address'], "true" ); ?>><?php _e('Enabled','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_address" value="false"<?php checked( $load_setting['address'], "false" ); ?>><?php _e('Disabled','simple-stripe'); ?>
		</div>
		<div style="margin-bottom: 12px;">
			<label for=""><?php _e('Zip code','simple-stripe'); ?></label><br>
			<input type="radio" name="simple_stripe_zip" value="true"<?php checked( $load_setting['zip'], "true" ); ?>><?php _e('Enabled','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_zip" value="false"<?php checked( $load_setting['zip'], "false" ); ?>><?php _e('Disabled','simple-stripe'); ?>
		</div>
		<div style="margin-bottom: 12px;">
			<label for=""><?php _e('Remember Me','simple-stripe'); ?></label><br>
			<input type="radio" name="simple_stripe_remember" value="true"<?php checked( $load_setting['remember'], "true" ); ?>><?php _e('Enabled','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_remember" value="false"<?php checked( $load_setting['remember'], "false" ); ?>><?php _e('Disabled','simple-stripe'); ?>
		</div>

		<div style="margin-bottom: 12px;">
			<label for=""><?php _e('Stripe Checkout','simple-stripe'); ?></label><br>
			<select id="simple_stripe_payment_mode" name="simple_stripe_payment_mode">
				<option value="legacy"<?php echo selected( $load_setting['payment_mode'], 'legacy' , false ) ?>><?php _e('Legacy','simple-stripe'); ?></option>
				<option value="new"<?php echo selected( $load_setting['payment_mode'], 'new' , false ) ?>><?php _e('New','simple-stripe'); ?></option>
			</select>
		</div>
		<div style="margin-bottom: 12px;">
			<label for=""><?php _e('Redirect URL after successful payment','simple-stripe'); ?></label><br>
			<input id="simple_stripe_redirect_url_success_pay" name="simple_stripe_redirect_url_success_pay" class="widefat" type="text" placeholder="<?php echo esc_url( home_url( '/' ) ) ?>success" value="<?php echo $load_setting['redirect_url_success_pay']; ?>" />
		</div>
		<hr>
		<div id="legacy_wrap">
			<h2><?php _e('Stripe modal','simple-stripe')._e('(Legacy version)','simple-stripe'); ?></h2>
			<div>
				<label for=""><?php _e('Logo image','simple-stripe'); ?></label>
				<button id="simple_stripe_logo_image_clear" type="button"><?php _e('Clear','simple-stripe'); ?></button><br>
				<div style="position:relative;min-width: 200px;min-height: 200px;max-width: 200px;max-height: 200px;border: 2px dotted #222;margin: 24px 0;cursor: pointer;">
					<div id="simple_stripe_image_div" style="position: absolute;z-index:2;width: 100%;height: 100%;background-repeat: no-repeat;background-position: center center;background-size: cover;background-image: url('<?php echo $load_setting['image']; ?>');"></div>
					<div style="z-index:1;position: absolute;top: 50%;left: 50%;transform: translateY(-50%) translateX(-50%);-webkit- transform: translateY(-50%) translateX(-50%);"><?php _e('Please select an image','simple-stripe'); ?></div>
				</div>
				<input id="simple_stripe_logo_image" name="simple_stripe_logo_image" class="" type="hidden" value="<?php echo $load_setting['image']; ?>" readonly />
			</div>




			<div style="margin-bottom: 12px;">
				<label for="simple_stripe_open_button"><?php _e('Button label for opening modal','simple-stripe'); ?></label><br>
				<input id="simple_stripe_open_button" name="simple_stripe_open_button" class="" type="text" placeholder="<?php _e('Label of open button','simple-stripe'); ?>" maxlength="100" value="<?php echo $load_setting['open_button']; ?>" />
			</div>
		</div>
		<hr>
		<input type="button" id="simple_stripe_submit" class="button button-primary" value="<?php _e('Update','simple-stripe'); ?>" onclick="submit();" />
	</form>

	<?php
}
