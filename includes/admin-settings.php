<?php
class WC_AR_CONFIGWISE_ADMIN_SETTINGS
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_wcar_page' ) );
        add_action( 'admin_init', array( $this, 'save_page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_wcar_page()
    {
        add_menu_page(__('AR Widget', 'wooarwidget'), __('AR ConfigWise', 'wooarwidget'), 'manage_woocommerce', 'wooarwidget', array($this, 'create_admin_page'));
        
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'wc_ar_settings' );
        ?>
        <div class="wrap">
            <h1>Configure</h1>
            <form method="post" action="options.php">
            <?php
                settings_fields( 'wc_ar_group' );
                do_settings_sections( 'wc_ar_settings_admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function save_page_init()
    {        
        register_setting(
            'wc_ar_group', 
            'wc_ar_settings', 
            array( $this, 'sanitize' ) 
        );

        add_settings_section(
            'wc_ar_section_general', 
            'General Configuration', 
            array( $this, 'print_section_info' ), 
            'wc_ar_settings_admin' 
        );  

          

        add_settings_field(
            'enable_widget', 
            'Enable Widget',  
            array( $this, 'enable_widget_callback' ), 
            'wc_ar_settings_admin', 
            'wc_ar_section_general'          
        );      
		
        add_settings_field(
            'product_identifier', 
            'Product Identifier',  
            array( $this, 'product_identifier_callback' ), 
            'wc_ar_settings_admin', 
            'wc_ar_section_general'          
        );   		
		
        add_settings_field(
            'channel_id', 
            'Channel ID',  
            array( $this, 'channel_id_callback' ), 
            'wc_ar_settings_admin', 
            'wc_ar_section_general'          
        );      

        add_settings_field(
            'domain', 
            'Domain', 
            array( $this, 'domain_callback' ), 
            'wc_ar_settings_admin', 
            'wc_ar_section_general'
        );      
		add_settings_field(
            'ref_number', 
            'Company Reference Number', 
            array( $this, 'ref_number_callback' ), 
            'wc_ar_settings_admin', 
            'wc_ar_section_general'
        );     		
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['channel_id'] ) )
            $new_input['channel_id'] = sanitize_text_field( $input['channel_id'] );

        if( isset( $input['domain'] ) )
            $new_input['domain'] = sanitize_text_field( $input['domain'] );

        if( isset( $input['ref_number'] ) )
            $new_input['ref_number'] = sanitize_text_field( $input['ref_number'] );

       if( isset( $input['enable_widget'] ) )
            $new_input['enable_widget'] = sanitize_text_field( $input['enable_widget'] );

       if( isset( $input['product_identifier'] ) )
            $new_input['product_identifier'] = sanitize_text_field( $input['product_identifier'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your configuration below:';
    }

    
    public function enable_widget_callback()
    {
		$no_selected = '';
		$yes_selected = '';
		$selected_val = isset( $this->options['enable_widget'] ) ? esc_attr( $this->options['enable_widget']) : '';
		echo '<select id="enable_widget" name="wc_ar_settings[enable_widget]">';
			if($selected_val == 'yes'){$yes_selected = 'selected';}
			if($selected_val == 'no'){$no_selected = 'selected';}
			echo '<option value="yes" '.$yes_selected.' >Yes</option>';
			echo '<option value="no" '.$no_selected.'>No</option>';
		echo '</select>';
    } 
	
	public function product_identifier_callback()
    {
		$sku_selected = '';
		$id_selected = '';
		$selected_val = isset( $this->options['product_identifier'] ) ? esc_attr( $this->options['product_identifier']) : '';
		echo '<select id="product_identifier" name="wc_ar_settings[product_identifier]">';
			if($selected_val == 'product_sku'){$sku_selected = 'selected';}
			if($selected_val == 'product_id'){$id_selected = 'selected';}
			echo '<option value="product_sku" '.$sku_selected.' >Product SKU</option>';
			echo '<option value="product_id" '.$id_selected.' >Product ID</option>';
		echo '</select>';
    }
	
	public function channel_id_callback()
    {
        printf(
            '<input type="text" id="channel_id" size="60" name="wc_ar_settings[channel_id]" value="%s" />',
            isset( $this->options['channel_id'] ) ? esc_attr( $this->options['channel_id']) : ''
        );
    }	

   
    public function domain_callback()
    {
        printf(
            '<input type="text" id="domain" size="60" name="wc_ar_settings[domain]" value="%s" />',
            isset( $this->options['domain'] ) ? esc_attr( $this->options['domain']) : ''
        );
    }
	
	public function ref_number_callback()
    {
        printf(
            '<input type="text" id="ref_number" size="60" name="wc_ar_settings[ref_number]" value="%s" />',
            isset( $this->options['ref_number'] ) ? esc_attr( $this->options['ref_number']) : ''
        );
    }
}

if( is_admin() )
    $wc_ar_admin_settings = new WC_AR_CONFIGWISE_ADMIN_SETTINGS();