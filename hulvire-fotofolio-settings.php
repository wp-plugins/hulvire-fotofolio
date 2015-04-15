<?php
if(!class_exists('WP_Hulvire_Fotofolio_Settings'))
{
	class WP_Hulvire_Fotofolio_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	// register your plugin's settings
        	register_setting('wp_hulvire_fotofolio-group', 'setting_inputFooter');
        	register_setting('wp_hulvire_fotofolio-group', 'setting_inputFooter_color');
        	//register_setting('wp_hulvire_fotofolio-group', 'setting_b');
        	//register_setting('wp_hulvire_fotofolio-group', 'setting_animacia');
        	register_setting('wp_hulvire_fotofolio-group', 'setting_checkbox_popiskaPOSTU');

        	// add your settings section
        	add_settings_section(
        	    'wp_hulvire_fotofolio-section', 
        	    'Hulvire Fotofolio Settings', 
        	    array(&$this, 'settings_section_hulvire_fotofolio'), 
        	    'wp_hulvire_fotofolio'
        	);
        	
        	// add your setting's fields
            add_settings_field(
                'wp_hulvire_fotofolio-setting_inputFooter', 
                'input footer', 
                array(&$this, 'settings_field_input_text'), 
                'wp_hulvire_fotofolio', 
                'wp_hulvire_fotofolio-section',
                array(
                    'field' => 'setting_inputFooter'
                )
            );
            add_settings_field(
                'wp_hulvire_fotofolio-setting_inputFooter_color', 
                'inputFooter color', 
                array(&$this, 'settings_field_input_text'), 
                'wp_hulvire_fotofolio', 
                'wp_hulvire_fotofolio-section',
                array(
                    'field' => 'setting_inputFooter_color'
                )
            );
            add_settings_field(
                'wp_hulvire_fotofolio-setting_checkbox_popiskaPOSTU', 
                'Zobraz popisku POSTU', 
                array(&$this, 'settings_field_input_checkbox'), 
                'wp_hulvire_fotofolio', 
                'wp_hulvire_fotofolio-section',
                array(
                    'field' => 'setting_checkbox_popiskaPOSTU'
                )
            );
			/*
			add_settings_field(
			    'wp_hulvire_fotofolio-setting_animacia',
			    'nastav animaciu',
			    array(&$this, 'settings_field_input_radio'),
				'wp_hulvire_fotofolio',
			    'wp_hulvire_fotofolio-section',
                array(
                    'field' => 'setting_animacia'
                )
			);
			add_settings_field(
			    'wp_hulvire_fotofolio-setting_checkbox_element_callback',
			    'nastav checkbox',
			    array(&$this, 'settings_field_checkbox_element_callback'),
				'wp_hulvire_fotofolio',
			    'wp_hulvire_fotofolio-section',
                array(
                    'field' => 'setting_checkbox_element_callback'
                )
			);
			*/
            // Possibly do additional admin_init tasks
        } // END public static function activate
        
        public function settings_section_hulvire_fotofolio()
        {
            // Think of this as help text for the section.
            echo 'Nastavenia pre Hulvire Fotofolio. ٩(●̮̮̃•)۶';
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)
        
		public function settings_field_input_radio($args) {
 
            $field = $args['field'];
			
            $value = get_option($field);
			$value = isset( $value ) ? esc_attr( $value ) : 'fade';

		    echo sprintf('<input type="radio" name="%s" id="%s" value="fade"' . checked( "fade", $value, false ) . ' />', $field, $field);
		    echo sprintf('<label for="radio">FADE</label><br />');
		    echo sprintf('<input type="radio" name="%s" id="%s" value="slide"' . checked( "slide", $value, false ) . ' />', $field, $field);
		    echo sprintf('<label for="radio">SLIDE</label>');

		} // end settings_field_input_radio
		
		public function settings_field_input_checkbox($args) {
 
            $field = $args['field'];
			
            $value = get_option($field);
			$value = isset( $value ) ? esc_attr( $value ) : 'ano';

		    echo sprintf('<input type="checkbox" name="%s" id="%s" value="ano"' . checked( "ano", $value, false ) . ' />', $field, $field);


		} // end settings_field_input_radio
		
		public function settings_field_checkbox_element_callback($args) {
 
            $field = $args['field'];

            $value = get_option($field);

		    $html = '<select id="time_options" name="sandbox_theme_input_examples[time_options]">';
		        $html .= '<option value="default">Select a time option...</option>';
		        $html .= '<option value="never"' . selected( $options['time_options'], 'never', false) . '>Never</option>';
		        $html .= '<option value="sometimes"' . selected( $options['time_options'], 'sometimes', false) . '>Sometimes</option>';
		        $html .= '<option value="always"' . selected( $options['time_options'], 'always', false) . '>Always</option>';
		    $html .= '</select>';
		    echo $html;
 
		} // end sandbox_checkbox_element_callback
		
		
        /**
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'WP Hulvire Fotofolio Settings', 
        	    'Hulvire Fotofolio', 
        	    'manage_options', 
        	    'wp_hulvire_fotofolio', 
        	    array(&$this, 'plugin_settings_page')
        	);
        } // END public function add_menu()
    
        /**
         * Menu Callback
         */		
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class WP_Hulvire_Fotofolio_Settings
} // END if(!class_exists('WP_Hulvire_Fotofolio_Settings'))
