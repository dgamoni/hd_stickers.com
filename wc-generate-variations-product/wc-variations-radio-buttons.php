<?php
/**
 * Plugin Name: WC Generate Variations Product
 * Plugin URI:  
 * Description: Generate Variations Product
 * Version:     1.0.0
 * Author:      dgamoni
 * Author URI:  http://
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Check if WooCommerce is active
if ( is_plugin_active( 'woocommerce/woocommerce.php')  && is_plugin_active( 'advanced-custom-fields-pro/acf.php') ) {

	class WC_Radio_Buttons {
		// plugin version
		const VERSION = '2.0.0';

		private $plugin_path;
		private $plugin_url;

		public function __construct() {
			add_filter( 'woocommerce_locate_template', array( $this, 'locate_template' ), 10, 3 );

			//js scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ), 999 );

			// load acf
			include 'includes/load_acf.php';

			// if ( !is_admin() ) {
			// 	include 'includes/register_variations.php';
			// }
			if ( is_admin() ) {
				//include 'includes/create_atribute.php';
				include 'includes/options_page.php';
				include 'includes/register_variations.php';
				include 'includes/generate_product.php';
			}

		}

		public function get_plugin_path() {

			if ( $this->plugin_path ) {
				return $this->plugin_path;
			}

			return $this->plugin_path = plugin_dir_path( __FILE__ );
		}

		public function get_plugin_url() {

			if ( $this->plugin_url ) {
				return $this->plugin_url;
			}

			return $this->plugin_url = plugin_dir_url( __FILE__ );
		}

		public function locate_template( $template, $template_name, $template_path ) {
			global $woocommerce;

			$_template = $template;

			if ( ! $template_path ) {
				$template_path = $woocommerce->template_url;
			}

			$plugin_path = $this->get_plugin_path() . 'templates/';

			// Look within passed path within the theme - this is priority
			$template = locate_template( array(
				$template_path . $template_name,
				$template_name
			) );

			// Modification: Get the template from this plugin, if it exists
			if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
				$template = $plugin_path . $template_name;
			}

			// Use default template
			if ( ! $template ) {
				$template = $_template;
			}

			return $template;
		}

		function load_scripts() {
			wp_deregister_script( 'wc-add-to-cart-variation' );
			wp_register_script( 'wc-add-to-cart-variation', $this->get_plugin_url() . 'assets/js/frontend/add-to-cart-variation.js', array( 'jquery', 'wp-util' ), self::VERSION );
		}
	}

	new WC_Radio_Buttons();
}