<?php
/*
Plugin Name: Simple Stripe
Plugin URI: https://dev.back2nature.jp/en/simple-stripe/
Description: Just register your Stripe API key and use the shortcode.You can easily make a payment page anywhere.
Version: 0.9.15
Author: YAHMAN
Author URI: https://back2nature.jp/en/
License: GNU General Public License v3 or later
Text Domain: simple-stripe
Domain Path: /languages/
*/

/*
    Simple Stripe
    Copyright (C) 2020 YAHMAN

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
    defined( 'ABSPATH' ) || exit;

    define( 'SIMPLE_STRIPE_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    define( 'SIMPLE_STRIPE_URI', trailingslashit( esc_url( plugin_dir_url( __FILE__ ) ) ) );

    $data = get_file_data( __FILE__, array( 'Version' ) );

    define( 'SIMPLE_STRIPE_VERSION', $data[0] );

    
    



    function simple_stripe_file_load() {

        
        
        load_plugin_textdomain( 'simple-stripe', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' );

        if( is_admin() ){

            if ( wp_doing_ajax() ){
                require_once SIMPLE_STRIPE_DIR . 'inc/ajax.php';
            }

            require_once SIMPLE_STRIPE_DIR . 'inc/admin.php';

        }else{

            require_once SIMPLE_STRIPE_DIR . 'inc/shortcode.php';

        }

    }
    add_action( 'plugins_loaded', 'simple_stripe_file_load');





