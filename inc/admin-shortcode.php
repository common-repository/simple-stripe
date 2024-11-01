<?php

defined( 'ABSPATH' ) || exit;

function simple_stripe_admin_shorcode() {
	?>

	<div id="simple_stripe_edit_shortcode" style="max-width:600px;">
		<h2><?php _e('Basic shortcode','simple-stripe'); ?></h2>
		<input type="text" id="simple_stripe_basic_shortcode" name="simple_stripe_basic_shortcode" value="[simple_stripe]" readonly>
		<button class="button button-primary" style="" type="button" onclick="document.getElementById('simple_stripe_basic_shortcode').select();document.execCommand('copy');simple_stripe_pop_up_message(simple_stripe_admin.copy,'#4caf50');"><?php _e('Copy','simple-stripe'); ?></button>
		<hr>
		<h2><?php _e('Custom shortcode','simple-stripe'); ?></h2>
		<div style="margin-bottom: 12px;">
			<input type="text" id="simple_stripe_custom_shortcode" class="widefat" name="simple_stripe_custom_shortcode" value="[simple_stripe]" style="margin-bottom:8px" readonly><br>
			<button class="button button-primary" style="" type="button" onclick="document.getElementById('simple_stripe_custom_shortcode').select();document.execCommand('copy');simple_stripe_pop_up_message(simple_stripe_admin.copy,'#4caf50');"><?php _e('Copy','simple-stripe'); ?></button>
		</div>
		<div>
			<label for=""><?php _e('Logo image','simple-stripe'); ?></label>
			<button id="simple_stripe_logo_image_clear_custom" type="button"><?php _e('Clear','simple-stripe'); ?></button><br>
			<div style="position:relative;min-width: 200px;min-height: 200px;max-width: 200px;max-height: 200px;border: 2px dotted #222;margin: 24px 0;cursor: pointer;">
				<div id="simple_stripe_image_div_custom" style="position: absolute;z-index:2;width: 100%;height: 100%;background-repeat: no-repeat;background-position: center center;background-size: cover;background-image: url('');"></div>
				<div style="z-index:1;position: absolute;top: 50%;left: 50%;transform: translateY(-50%) translateX(-50%);-webkit- transform: translateY(-50%) translateX(-50%);"><?php _e('Please select an image','simple-stripe'); ?></div>
			</div>
			<input id="simple_stripe_logo_image_custom" name="simple_stripe_logo_image_custom" class="" type="hidden" value="" readonly  />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_currency_custom"><?php _e('Currency','simple-stripe'); ?></label><br>
			<select id="simple_stripe_currency_custom" name="simple_stripe_currency_custom" onchange="simple_stripe_custom_shortcode();">
				<option value="default" selected><?php _e('Default','simple-stripe'); ?></option>
				<?php
				foreach (simple_stripe_currencies() as $key => $value) {
					echo '<option value="'.$key.'">'.$key.' ('. $value[0] . ' ' . $value[1] .')</option>';
				}
				?>
			</select>
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_amount_custom"><?php _e('Amount','simple-stripe'); ?></label><br>
			<input id="simple_stripe_amount_custom" name="simple_stripe_amount_custom" type="number" min="0" placeholder="<?php _e('Amount','simple-stripe'); ?>" maxlength="100" value="" class="simple_stripe_input" />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_name_custom"><?php _e('Name','simple-stripe'); ?></label><br>
			<input id="simple_stripe_name_custom" name="simple_stripe_name_custom" class="widefat simple_stripe_input" type="text" placeholder="<?php _e('Name','simple-stripe'); ?>" maxlength="100" value="" />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_description_custom"><?php _e('Description','simple-stripe'); ?></label><br>
			<input id="simple_stripe_description_custom" name="simple_stripe_description_custom" class="widefat simple_stripe_input" type="text" placeholder="<?php _e('Description','simple-stripe'); ?>" maxlength="100" value="" />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_panellabel_custom"><?php _e('Button Label','simple-stripe'); ?></label><br>
			<input id="simple_stripe_panellabel_custom" name="simple_stripe_panellabel_custom" class="simple_stripe_input" type="text" placeholder="<?php _e('Button Label','simple-stripe'); ?>" maxlength="100" value="" />
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_locale_custom"><?php _e('Locale','simple-stripe'); ?></label><br>
			<select id="simple_stripe_locale_custom" name="simple_stripe_locale_custom" onchange="simple_stripe_custom_shortcode();">
				<option value="default" selected><?php _e('Default','simple-stripe'); ?></option>
				<?php
				foreach (simple_stripe_locales() as $key => $value) {
					echo '<option value="'.$key.'">'.$key.' ('. $value .')</option>';
				}
				?>
			</select>
		</div>
		<div style="margin-bottom: 12px;">
			<label for=""><?php _e('Shipping Address','simple-stripe'); ?></label><br>
			<input type="radio" name="simple_stripe_address_custom" value="default" checked="checked" onchange="simple_stripe_custom_shortcode();"><?php _e('Default','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_address_custom" value="true" onchange="simple_stripe_custom_shortcode();"><?php _e('Enabled','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_address_custom" value="false" onchange="simple_stripe_custom_shortcode();"><?php _e('Disabled','simple-stripe'); ?>
		</div>
		<div style="margin-bottom: 12px;">
			<label for=""><?php _e('Zip code','simple-stripe'); ?></label><br>
			<input type="radio" name="simple_stripe_zip_custom" value="default" checked="checked" onchange="simple_stripe_custom_shortcode();"><?php _e('Default','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_zip_custom" value="true" onchange="simple_stripe_custom_shortcode();"><?php _e('Enabled','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_zip_custom" value="false" onchange="simple_stripe_custom_shortcode();"><?php _e('Disabled','simple-stripe'); ?>
		</div>
		<div style="margin-bottom: 12px;">
			<label for=""><?php _e('Remember Me','simple-stripe'); ?></label><br>
			<input type="radio" name="simple_stripe_remember_custom" value="default" checked="checked" onchange="simple_stripe_custom_shortcode();"><?php _e('Default','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_remember_custom" value="true" onchange="simple_stripe_custom_shortcode();"><?php _e('Enabled','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_remember_custom" value="false" onchange="simple_stripe_custom_shortcode();"><?php _e('Disabled','simple-stripe'); ?>
		</div>
		<div style="margin-bottom: 12px;">
			<label for=""><?php _e('Amount adjustment','simple-stripe'); ?></label><br>
			<input type="radio" name="simple_stripe_amount_mode_custom" value="default" checked="checked" onchange="simple_stripe_custom_shortcode();"><?php _e('Default','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_amount_mode_custom" value="freely" onchange="simple_stripe_custom_shortcode();"><?php _e('Freely','simple-stripe'); ?>
			<input type="radio" name="simple_stripe_amount_mode_custom" value="fixed" onchange="simple_stripe_custom_shortcode();"><?php _e('Fixed','simple-stripe'); ?>
		</div>
		<div style="margin-bottom: 12px;">
			<label for="simple_stripe_open_button_custom"><?php _e('Button label for opening modal','simple-stripe'); ?></label><br>
			<input id="simple_stripe_open_button_custom" name="simple_stripe_open_button_custom" class="simple_stripe_input" type="text" placeholder="<?php _e('Label of open button','simple-stripe'); ?>" maxlength="100" value="" />
		</div>
	</div>

	<?php
}
