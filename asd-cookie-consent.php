<?php
/*
 Plugin Name: ASD Cookie Consent
 Description: Add cookie consent to your WordPress installation
 Author: Armando Savarese
 Author URI: https://armandosavarese.it
 Version: 1.0.0
 License: GPL2 or later
 Text Domain: asd-cookie-consent
 Domain Path: /asd-cookie-consent/languages/
 */

/*
 Copyright (C) 2018  Armando Savarese  armando.savarese@gmail.com
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

///////////////
// Front-end //
///////////////
add_action('wp_enqueue_scripts', 'asd_cookie_frontend_init');

// Load script / css / js vars
function asd_cookie_frontend_init() {
	wp_enqueue_style(  'cookieconsent', plugins_url('public/css/cookieconsent/cookieconsent.min.css', __FILE__));
	wp_enqueue_script( 'cookieconsent', plugins_url('public/js/cookieconsent/cookieconsent.min.js', __FILE__ ));
	wp_enqueue_script( 'main', plugins_url('public/js/main.js', __FILE__) );

	// js main-script variable
	$db_options= get_option('asd_cookie_consent');
	$vars= array(
		'theme'				=>	 esc_html( $db_options['theme'] ),
		'position'			=>	 esc_html( $db_options['position'] ),
		'back_color'		=>	 esc_html( $db_options['back_color'] ),
		'text_color'		=>	 esc_html( $db_options['text_color'] ),
		'message'			=>	 esc_html( $db_options['message'] ),
		'show_link'			=>	 esc_html( $db_options['show_link'] ),
		'text_cookie_info'	=>	 esc_html( $db_options['text_cookie_info'] ),
		'href_cookie_info'	=>	 esc_url( $db_options['href_cookie_info'] ),
		'btn_color'			=>	 esc_html( $db_options['btn_color'] ),
		'btn_text_color'	=>	 esc_html( $db_options['btn_text_color'] ),
		'btn_text'			=>	 esc_html( $db_options['btn_text'] ),
		'expiry'			=>	 esc_html( $db_options['expiry'] ),
	);
	wp_localize_script( 'main', 'cookie_panel', $vars );
}

//////////////
// Back-end //
//////////////
if( is_admin() ) {
	add_action('init', 'asd_cookie_update_options');
	add_action('admin_menu', 'asd_cookie_add_page');
	add_action('admin_init', 'asd_cookie_init');
	load_plugin_textdomain( 'asd-cookie-consent', false, 'asd-cookie-consent/languages' );

	$plugin_info= array(
		'name'		  => __('ASD Cookie Consent', 'asd-cookie-consent'),
		'description' => __('Add cookie consent to your WordPress installation', 'asd-cookie-consent')
	);
}

// Add options page
function asd_cookie_add_page() {
	$page_title= esc_html__('Settings ASD Cookie Consent', 'asd-cookie-consent');
	$menu_title= esc_html__('ASDCookieConsent', 'asd-cookie-consent');
	$capability= 'manage_options';
	$slug= 'asd-cookie-consent';
	$callback= 'asd_cookie_template';
	$icon= '';
	$position= null;

	add_options_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
}

// load template page
function asd_cookie_template() {
	include('includes/config.php');
}

// Init settings
function asd_cookie_init() {
	// create options page
	$page= 'asd-cookie-consent';
	$section= 'general_setting';
	$options= get_option('asd_cookie_consent');
	$text_domain= 'asd-cookie-consent';

	

	add_settings_section( $section, '', '', 'asd-cookie-consent' );

	add_settings_field(  
		'theme',
		'<label for="theme">' . esc_html__('Theme', $text_domain) . '</label>',
		'asd_cookie_input_select',
		$page,
		$section,
		array(
			'id'		=>	'theme',
			'value'		=>	esc_attr( $options['theme'] ),
			'options'	=> 	array(
				'block'		=>	esc_attr__('Block', $text_domain),
				'edgeless'	=>	esc_attr__('Edgeless', $text_domain),
				'classic'	=>	esc_attr__('Classic', $text_domain)
			)
		)
	);

	add_settings_field( 
		'position', 
		'<label for="position">' . esc_html__('Widget position', $text_domain) . '</label>', 
		'asd_cookie_input_select', 
		$page, 
		$section,
		array( 
			'id' => 'position', 
			'value' => esc_attr( $options['position'] ),
			'options' => array(
				'top' 			=> esc_attr__('Top', $text_domain),
				'bottom'		=> esc_attr__('Bottom', $text_domain),
				'bottom-left'	=> esc_attr__('Bottom left', $text_domain),
				'bottom-right'	=> esc_attr__('Bottom right', $text_domain)
			)
		)
	);

	add_settings_field( 
		'back_color', 
		'<label for="back_color">' . esc_html__('Background color', $text_domain) . '</label>',
		'asd_cookie_input_text',
		$page,
		$section,
		array(
			'id' => 'back_color', 
			'value' => esc_attr( $options['back_color'] ), 
		  	'type' => 'color'
		)
	);

	add_settings_field( 
		'text_color',
		'<label for="text_color">' . esc_html__('Text color', $text_domain) . '</label>',
		'asd_cookie_input_text',
		$page,
		$section,
		array( 
			'id' => 'text_color', 
		   	'value' => esc_attr( $options['text_color'] ), 
		   	'type' => 'color' 
		)
	);

	add_settings_field( 
		'message',
		'<label for="message">' . esc_html__('Message', $text_domain) . '</label>',
		'asd_cookie_textarea',
		$page,
		$section,
		array( 
			'id' => 'message', 
		   	'value' => esc_attr( $options['message'] ) 
		)
	);

	add_settings_field( 
		'show_link',
		'<label for="show_link1">' . esc_html__('Show read more', $text_domain) . '</label>',
		'asd_cookie_input_text',
		$page,
		$section,
		array( 
			'id' => 'show_link1', 
		   	'id_2' => 'show_link2', 
		   	'name' => 'show_link', 
		   	'value' => esc_attr( $options['show_link'] ), 
		   	'type' => 'radio'
		)
	);

	add_settings_field( 
		'text_cookie_info',
		'<label for="text_cookie_info">' . esc_html__('Text read more', $text_domain) . '</label>',
		'asd_cookie_input_text',
		$page,
		$section,
		array( 
			'id' 	=> 'text_cookie_info', 
			'value' => esc_attr( $options['text_cookie_info'] ), 
			'type' 	=> 'text' 
		)
	);
	
	add_settings_field( 
		'href_cookie_info',
		'<label for="href_cookie_info">' . esc_html__('Link read more', $text_domain) . '</label>',
		'asd_cookie_input_text',
		$page,
		$section,
		array( 
			'id'  	=>  'href_cookie_info',
			'value'	=>	esc_url($options['href_cookie_info']),
			'type'	=>	'text'
		)
	);

	add_settings_field( 
		'btn_text',
		'<label for="btn_text">' . esc_html__('Button text', $text_domain) . '</label>',
		'asd_cookie_input_text',
		$page,
		$section,
		array(
			'id'    => 'btn_text',
			'value'	=> esc_attr( $options['btn_text'] ),
			'type'	=> 'text'
		)

	);

	add_settings_field( 
		'btn_color',
		'<label for="btn_color">' . esc_html__('Button color', $text_domain) . '</label>',
		'asd_cookie_input_text',
		$page,
		$section,
		array(
			'id'	=> 'btn_color',
			'value'	=> esc_attr( $options['btn_color'] ),
			'type'	=> 'color'
		)

	);

	add_settings_field( 
		'btn_text_color',
		'<label for="btn_text_color">' . esc_html__('Button text color', $text_domain) .'</label>',
		'asd_cookie_input_text',
		$page,
		$section,
		array(
			'id'	=> 'btn_text_color',
			'value'	=> esc_attr( $options['btn_text_color'] ),
			'type'	=> 'color'
		)

	);

	add_settings_field( 
		'expiry',
		'<label for="expiry">' . esc_html__('Expiry days', $text_domain) . '</label>',
		'asd_cookie_input_text',
		$page,
		$section,
		array(
			'id'	=>	'expiry',
			'value'	=>	esc_attr( $options['expiry'] ),
			'type'	=>	'number'
		)
	);

	register_setting( $section, 'theme' );
	register_setting( $section, 'position' );
	register_setting( $section, 'back_color' );
	register_setting( $section, 'text_color' );
	register_setting( $section, 'message' );
	register_setting( $section, 'show_link' );
	register_setting( $section, 'text_cookie_info' );
	register_setting( $section, 'href_cookie_info' );
	register_setting( $section, 'btn_text' );
	register_setting( $section, 'btn_color' );
	register_setting( $section, 'btn_text_color' );
	register_setting( $section, 'expiry' );
}

// render select position input
function asd_cookie_input_select($args) {
	$id= $args['id'];
	$value= $args['value'];
	
	$html= '<select id="' . $id . '" name="' . $id . '" value="' . $value . '" class="regular-text">';

	foreach ($args['options'] as $option => $text) {
		if($args['value'] === $option) {
			$selected= 'selected="selected"';
		} else {
			$selected= '';
		}

		$html .= '<option value="' . $option . '" ' . $selected . '>' . esc_html($text) . '</option>';
	}

	$html .= '</select>';
	echo $html;
}

// render input
function asd_cookie_input_text($args) {
	$html = '<input type="' . $args['type'] . '" id="' . $args['id']  . '" name="' . $args['id'];
	$html .= '" value="' . $args['value'] . '" class="regular-text">';

	if($args['type'] === 'radio') {
		if($args['value'] === '1') {
			$active1= 'checked="checked"';
			$active2= '';
		} else {
			$active2= 'checked="checked"';
			$active1= '';
		}

		$html = '<input type="' . $args['type'] . '" id="' . $args['id']  . '" name="' . $args['name'] . '" value="1" ' . $active1 .'>';
		$html .= '<label for="' . $args['id'] . '">' . __('yes', 'asd-cookie-consent') . '</label>';
		$html .= '&nbsp;&nbsp;';
		$html .= '<input type="' . $args['type'] . '" id="' . $args['id_2']  . '" name="' . $args['name'] . '" value="2" ' . $active2 . '>';
		$html .= '<label for="' . $args['id_2'] . '">' . __('no', 'asd-cookie-consent') . '</label>';
	}

	echo $html;
}

// render textarea 
function asd_cookie_textarea($args) {
	$html  = '<textarea id="' . $args['id'] . '" ';
	$html .= 'name="' . $args['id'] . '" ';
	$html .= 'rows="5" class="regular-text">';
	$html .= $args['value'];
	$html .= '</textarea>';
	echo $html;
}

// check request and update options
function asd_cookie_update_options() {

	if( isset($_POST['option_page']) && $_POST['option_page'] === 'general_setting' &&
		check_admin_referer('general_setting-options', '_wpnonce') === 1 )
	{
		if( $_POST['action'] === 'update' && isset($_POST['submit']) ) {

			if( isset($_POST['show_link']) ) {
				$show_link= $_POST['show_link'];
			} else {
				$show_link= 0;
			}

			$options= array(
				'theme'					=> sanitize_text_field( $_POST['theme'] ),
				'position'				=> sanitize_text_field( $_POST['position'] ),
				'back_color'			=> sanitize_text_field( $_POST['back_color'] ),
				'text_color'			=> sanitize_text_field( $_POST['text_color'] ),
				'btn_color'				=> sanitize_text_field( $_POST['btn_color'] ),
				'btn_text'				=> sanitize_text_field( $_POST['btn_text'] ),
				'btn_text_color'		=> sanitize_text_field( $_POST['btn_text_color'] ),
				'href_cookie_info'		=> sanitize_text_field( $_POST['href_cookie_info'] ),
				'text_cookie_info'		=> sanitize_text_field( $_POST['text_cookie_info'] ),
				'message'				=> sanitize_textarea_field( $_POST['message'] ),
				'show_link'				=> sanitize_text_field( $show_link ),
				'expiry'				=> sanitize_text_field( $_POST['expiry'] )
			);

			$options['theme']= asd_cookie_input_validate($options['theme'], 20);
			$options['position']= asd_cookie_input_validate($options['position'], 20);
			$options['back_color']= asd_cookie_input_validate($options['back_color'], 7);
			$options['text_color']= asd_cookie_input_validate($options['text_color'], 7);
			$options['btn_color']= asd_cookie_input_validate($options['btn_color'], 7);
			$options['btn_text']= asd_cookie_input_validate($options['btn_text'], 30);
			$options['btn_text_color']= asd_cookie_input_validate($options['btn_text_color'], 7); 
			$options['href_cookie_info']= asd_cookie_input_validate($options['href_cookie_info'], 50);
			$options['text_cookie_info']= asd_cookie_input_validate($options['text_cookie_info'], 80);
			$options['message']= asd_cookie_input_validate($options['message'], 250);
			$options['show_link']= asd_cookie_input_validate($options['show_link'], 1);    
			$options['expiry']= asd_cookie_input_validate($options['expiry'], 5); 

			update_option( 'asd_cookie_consent', $options, true );
		} else if( $_POST['action'] === 'update' && isset($_POST['reset']) ) {
			$options= asd_cookie_default_value(get_locale());
			update_option( 'asd_cookie_consent', $options, true );
		}
	}

}

// validate user input
function asd_cookie_input_validate( $field, $lenght ) {
	if( strlen($field) > $lenght ) {
		$field_valid= substr( $field, 0, $lenght );
		return wp_unslash( $field_valid );
	} else {
		return wp_unslash( $field );
	}
}

// set default options
function asd_cookie_default_value($locale) {
	$options_en= array(
		'theme'					=> 'block',
		'position'				=> 'bottom',
		'back_color'			=> '#252e39',
		'text_color'			=> '#ffffff',
		'btn_color'				=> '#14a7d0',
		'btn_text'				=> 'Got it!',
		'btn_text_color'		=> '#ffffff',
		'href_cookie_info'		=> 'http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm',
		'text_cookie_info'		=> 'Learn more',
		'message'				=> 'This website uses cookies to ensure you get the best experience on our website',
		'show_link'				=> '1',
		'expiry'				=> '365'
	); 
	
	$options_it= array(
		'theme'					=> 'block',
		'position'				=> 'bottom',
		'back_color'			=> '#252e39',
		'text_color'			=> '#ffffff',
		'btn_color'				=> '#14a7d0',
		'btn_text'				=> 'Accetto!',
		'btn_text_color'		=> '#ffffff',
		'href_cookie_info'		=> 'http://www.garanteprivacy.it/cookie',
		'text_cookie_info'		=> 'l\' informativa e consenso per l\' uso dei cookie.',
		'message'				=> 'Questo sito utilizza cookie, anche di terze parti, per migliorare la tua esperienza di navigazione. Se vuoi saperne di più leggi',
		'show_link'				=> '1',
		'expiry'				=>	'365'
	);

	if( $locale === 'it_IT' ) {
		return $options_it;
	} else {
		return $options_en;
	}
} 

///////////////////////
// Plugin activation //
///////////////////////
register_activation_hook( __FILE__, 'asd_cookie_consent_activate' );

function asd_cookie_consent_activate() {
	
	if( get_option('asd_cookie_consent') === FALSE ) {
		// set defaults values
		$options= asd_cookie_default_value(get_locale());
		add_option( 'asd_cookie_consent', $options );
	}
	
}
