<?php
defined( 'ABSPATH' ) || exit; 

class WC_AR_CONFIGWISE_PRODUCT_SETTINGS{
	
    public function __construct()
    {
			add_filter( 'woocommerce_product_data_tabs', array($this,'wcar_config_product_tab'), 10, 1 );
			add_action( 'woocommerce_product_data_panels', array($this,'wcar_product_fields') );
			add_action('woocommerce_process_product_meta', array($this,'wcar_product_fields_save'));
    }
	
	
	/** 
     * create new tab in Products
     */
	public function wcar_config_product_tab($default_tabs){
		$default_tabs['wcar_tab'] = array(
			'label'   =>  __( 'AR ConfigWise', 'domain' ),
			'target'  => 'wcar_product_field',
			'priority' => 60,
			'class'   => array()
		);
		return $default_tabs;
	}
	
	/** 
     * Create Fields for ARConfigwise Tab
     */
	public function wcar_product_fields()
	{
		global $woocommerce, $post;
		echo '<div id="wcar_product_field" class="panel woocommerce_options_panel">';
		
		woocommerce_wp_select( array( 
			'id'          => '_wcar_enable_widget',
			'label'       => __( 'ConfigWise AR', 'woocommerce' ),
			'options'     => array(
				''    => 'System settings',
				'yes'    => __('Enable', 'woocommerce' ),
				'no' => __('Disable', 'woocommerce' ),
			)
		) );	
		
		woocommerce_wp_text_input(
			array(
				'id' => '_wcar_product_id',
				'placeholder' => 'Product ID',
				'label' => __('Product ID', 'woocommerce'),
				'desc_tip' => 'true'
			)
		);	

		
		woocommerce_wp_text_input(
			array(
				'id' => '_wcar_channel_id',
				'placeholder' => 'Channel ID',
				'label' => __('Channel ID', 'woocommerce'),
				'desc_tip' => 'true'
			)
		);
		
		woocommerce_wp_text_input(
			array(
				'id' => '_wcar_domain',
				'placeholder' => 'Domain',
				'label' => __('Domain', 'woocommerce'),
				'desc_tip' => 'true'
			)
		);
		woocommerce_wp_text_input(
			array(
				'id' => '_wcar_ref_number',
				'placeholder' => 'Company Reference Number',
				'label' => __('Company Reference Number', 'woocommerce'),
				'desc_tip' => 'true'
			)
		);
		
		echo '</div>';

	}		
	
	/** 
     * Save Fields of ARConfigwise Tab
     */
	public function wcar_product_fields_save($post_id)
	{
		if (isset($_POST['_wcar_channel_id']))
			update_post_meta($post_id, '_wcar_channel_id', esc_attr($_POST['_wcar_channel_id']));
		if (isset($_POST['_wcar_domain']))
			update_post_meta($post_id, '_wcar_domain', esc_attr($_POST['_wcar_domain']));
		if (isset($_POST['_wcar_ref_number']))
			update_post_meta($post_id, '_wcar_ref_number', esc_attr($_POST['_wcar_ref_number']));
	    if (isset($_POST['_wcar_enable_widget']))
			update_post_meta($post_id, '_wcar_enable_widget', esc_attr($_POST['_wcar_enable_widget']));
	    if (isset($_POST['_wcar_product_identifier']))
			update_post_meta($post_id, '_wcar_product_identifier', esc_attr($_POST['_wcar_product_identifier']));
	    if (isset($_POST['_wcar_product_id']))
			update_post_meta($post_id, '_wcar_product_id', esc_attr($_POST['_wcar_product_id']));


	}	

} 

$wc_ar_product_settings = new WC_AR_CONFIGWISE_PRODUCT_SETTINGS();






	
	
