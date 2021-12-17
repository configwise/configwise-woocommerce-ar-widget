<?php
/*
   Plugin Name:Woocommerce AR ConfigWise
   Plugin URI: http://technaitra.com
   description: A plugin for adding AR widget configuration to Wocommerce
   Version: 1.0
   Author: Technaitra
   Author URI: http://technaitra.com
   License: GPL2
   */

if ( ! defined('ABSPATH') ) {
    die('Please do not load this file directly!');
}


if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ) {

	/**
	* Check WooCommerce is installed and active
	*
	* This function will check that woocommerce is installed and active
	* and returns true or false
	*
	* @return true or false
	*/
	function wcar_admin_notice() {

			// Deactivate the plugin
		deactivate_plugins(__FILE__);

		$allowed_tags = array(
			'a' => array(
				'class' => array(),
				'href'  => array(),
				'rel'   => array(),
				'title' => array(),
			),
			'abbr' => array(
				'title' => array(),
			),
			'b' => array(),
			'blockquote' => array(
				'cite'  => array(),
			),
			'cite' => array(
				'title' => array(),
			),
			'code' => array(),
			'del' => array(
				'datetime' => array(),
				'title' => array(),
			),
			'dd' => array(),
			'div' => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'dl' => array(),
			'dt' => array(),
			'em' => array(),
			'h1' => array(),
			'h2' => array(),
			'h3' => array(),
			'h4' => array(),
			'h5' => array(),
			'h6' => array(),
			'i' => array(),
			'img' => array(
				'alt'    => array(),
				'class'  => array(),
				'height' => array(),
				'src'    => array(),
				'width'  => array(),
			),
			'li' => array(
				'class' => array(),
			),
			'ol' => array(
				'class' => array(),
			),
			'p' => array(
				'class' => array(),
			),
			'q' => array(
				'cite' => array(),
				'title' => array(),
			),
			'span' => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'strike' => array(),
			'strong' => array(),
			'ul' => array(
				'class' => array(),
			),
		);
		
		$wooextmm_message = '<div id="message" class="error">
		<p><strong>AR ConfiWise Plugin is inactive.</strong> The <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce plugin</a> must be active for this plugin to work. Please install &amp; activate WooCommerce »</p></div>';

		echo wp_kses(__($wooextmm_message, 'exthwsm'), $allowed_tags);

	}
	add_action('admin_notices', 'wcar_admin_notice');
}

if ( !class_exists('WC_AR_CONFIGWISE') ){
	class WC_AR_CONFIGWISE
	{
		public function __construct()
		{
			$this->define_constants();
			$this->includes();
			$this->init_hooks();
		}
		
		
		public function load_wc_ar_widget($atts){
			$product_id = 0;
			if(isset($atts['product_id']) && !empty($atts['product_id'])){
				$product_id = $atts['product_id'];
			}else{
				global $product;
				if(is_single()){
					$product_id = $product->get_id();
				}	
			}
			if(!empty($product_id)){
				$post_type = get_post_type($product_id);
				if($post_type == 'product'){
					return $this->get_product_ar_script($product_id);
				}
			}
		}		

		public function get_product_ar_script($product_id){

			// system Level Settings
			$system_ar_settings = $this->get_ar_system_level_settings();
			// product level settings
			$product_ar_settings = $this->get_ar_product_level_settings($product_id);
			
			if($system_ar_settings['enable_widget'] == 'yes'){
				$ar_identifier = $system_ar_settings['product_identifier'];
				$ar_channel_id = $system_ar_settings['channel_id'];
				$ar_domain = $system_ar_settings['domain'];
				$ar_ref_number = $system_ar_settings['ref_number'];
			}		
			
			if($product_ar_settings['enable_widget'] == 'yes' && !empty($product_ar_settings['product_identifier'])&&!empty($product_ar_settings['channel_id'])&& !empty($product_ar_settings['domain']) && !empty($product_ar_settings['ref_number'])){
				$ar_identifier = $product_ar_settings['product_identifier'];
				$ar_channel_id = $product_ar_settings['channel_id'];
				$ar_domain = $product_ar_settings['domain'];
				$ar_ref_number = $product_ar_settings['ref_number'];
			}
			
			if($ar_identifier == 'product_sku'):
				$ar_product_id = get_post_meta($product_id,'_sku',true);
			elseif($ar_identifier == 'product_id'):	
				$ar_product_id = $product_id;
			else:
				$ar_product_id = $ar_identifier;
			endif;	
			
			//$ar_product_id ='CONFIGWISE_TEST_EXAMPLE_CHAIR';
			if($system_ar_settings['enable_widget'] == 'yes' && $product_ar_settings['enable_widget'] != 'no' && !empty($ar_product_id) && !empty($ar_identifier) && !empty($ar_channel_id)&& !empty($ar_domain)&& !empty($ar_ref_number)){ 	
				return '<div style="height: 400px; width: 600px;"><script type="text/javascript" src="https://ar.configwise.io/configwise/canvas/web-viewer.js?product_id='.$ar_product_id.'&channel_id='.$ar_channel_id.'&domain='.$ar_domain.'&company_reference_number='.$ar_ref_number.'"></script><div>';
			}	
		
		}

		private function get_ar_system_level_settings() {
			return get_option('wc_ar_settings',true);
		}

		private function get_ar_product_level_settings($product_id) {
			$product_ar_settings = array();
			$product_ar_settings['enable_widget'] = get_post_meta($product_id,'_wcar_enable_widget',true);
			$product_ar_settings['product_identifier'] = get_post_meta($product_id,'_wcar_product_id',true);
			$product_ar_settings['channel_id'] = get_post_meta($product_id,'_wcar_channel_id',true);
			$product_ar_settings['domain'] = get_post_meta($product_id,'_wcar_domain',true);
			$product_ar_settings['ref_number'] = get_post_meta($product_id,'_wcar_ref_number',true);
			
			return $product_ar_settings;
		}


		private function init_hooks() {
			add_shortcode('wc_ar_widget', array($this, 'load_wc_ar_widget'));
		}
		
		
		private function includes() {
			require_once WC_AR_ABSPATH . 'includes/admin-settings.php';
			require_once WC_AR_ABSPATH . 'includes/product-settings.php';
		}
		
		private function define_constants() {

			$this->define( 'WC_AR_PLUGIN_FILE', __FILE__);
			$this->define( 'WC_AR_ABSPATH', plugin_dir_path(WC_AR_PLUGIN_FILE));
			$this->define( 'WC_AR_DIR_URL', plugin_dir_url(WC_AR_PLUGIN_FILE));


			$this->plugin_url = plugin_dir_url(WC_AR_PLUGIN_FILE);
			$this->plugin_path = plugin_dir_path(WC_AR_PLUGIN_FILE);
		}		
		
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		
		
	}
	$wc_ar = new WC_AR_CONFIGWISE();	
} 



