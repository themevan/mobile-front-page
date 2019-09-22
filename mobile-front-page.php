<?php
/*
Plugin Name: Mobile Front Page
Plugin URI: https://www.themevan.com/
Author: ThemeVan
Author URI: https://www.themevan.com
Version: 1.0.0
Description: You can set the different front page for the mobile devices quickly.
Text Domain: mfp
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if(!class_exists('MobileFrontPage')){
	class MobileFrontPage{

		/**
         * Instance of MobileFrontPage
         */
		public static function instance() {
			// Store the instance locally to avoid private static replication
			static $instance = null;
			
			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new MobileFrontPage;
				$instance->init();
			}

			return $instance;
		}
        
        /**
         * Initialize
         */
		private function init() {
			self::define_constants();
			add_action( 'init', array($this,'textdomain')); 
			add_action( 'init', array($this,'load_files'));
			add_action( 'template_redirect', array($this, 'replace_front_page'));
			add_action( 'admin_menu', array($this,'add_menu_page'));
			add_action( 'admin_init', array($this,'register_settings'));
		}

		/**
		 * Define constants
		 */ 
		static private function define_constants()
		{
			define('MFP_VERSION', '1.0.0');
			define('MFP_DIR', plugin_dir_path(__FILE__));
			define('MFP_URI', plugin_dir_url( '/', __FILE__ ));
			define('MFP_FILE', __FILE__ );
		}

		/**
         * Localize
         */
		public function textdomain() {
		    $domain = 'mfp';
		    $locale = apply_filters('plugin_locale', get_locale(), $domain);
		    load_textdomain($domain, WP_LANG_DIR.'/mobile-front-page/'.$domain.'-'.$locale.'.mo');
		    load_plugin_textdomain($domain,FALSE,dirname(plugin_basename(__FILE__)).'/languages/');
		}

		/**
         * Load Files
         */
		public function load_files(){
			require_once ( MFP_DIR. '/inc/class-mobile-detect.php' );
		}

		/**
         * Add Menu Page
         */
		public function add_menu_page(){
			add_submenu_page('options-general.php', esc_html__('Mobile Front Page','mfp'), esc_html__('Mobile Front Page','mfp'),'manage_options','mobile-front-page', array($this, 'option_page'));
		}

		/**
         * Include Option page
         */
		public function option_page(){
			 require_once ( MFP_DIR. '/inc/admin.php' );
		}

		/**
         * Register Settings
         */
		public function register_settings() { 
		  register_setting( 'mfp-group', 'iphone_page');
		  register_setting( 'mfp-group', 'android_phone_page' );
		  register_setting( 'mfp-group', 'ipad_page' );
		  register_setting( 'mfp-group', 'android_tablet_page' );
		}

		/**
         * Add Menu Page
         */
	    public static function page_selector($opt_name,$value){
			$pages = get_pages();
			$selected = '';

			$html = '<select name="'.$opt_name.'">';
	    	$html .= '<option>'.esc_html__('Please select','mfp').'</option>';

			foreach ( $pages as $page ) {
				$selected = ($page->ID == $value)? 'selected':'';
				$html .= '<option value="' . $page->ID . '" '.$selected.'>'.get_the_title($page->ID).'</option>';
			}

			$html .= '</select>';
			echo $html;
	    }

		/**
         * Save the settings
         */
		public static function save_settings(){
			if(isset($_POST['action']) && $_POST['action'] == 'save'){
				if(isset($_POST['iphone_page'])){
					update_option('iphone_page', esc_attr($_POST['iphone_page']));
				}
				if(isset($_POST['android_phone_page'])){
					update_option('android_phone_page', esc_attr($_POST['android_phone_page']));
				}
				if(isset($_POST['ipad_page'])){
					update_option('ipad_page', esc_attr($_POST['ipad_page']));
				}
				if(isset($_POST['iphone_page'])){
					update_option('android_tablet_page', esc_attr($_POST['android_tablet_page']));
				}
		    }
		}

		/**
         * Check the devices and make redirection
         */
		public function replace_front_page(){
			$detect = new MFP_Mobile_Detect;
			$page_id = '';

			// Phone
			if ($detect->isMobile() && !$detect->isTablet()) {
			  	// For iOS
			  	if( $detect->isiOS() ){
			  		$page_id = get_option('iphone_page');
				}

				// For Android OS
				if( $detect->isAndroidOS() ){
					$page_id = get_option('android_phone_page');
				}
			}

			// Tablet
			if ($detect->isTablet()) {
			     // For iOS
			  	if( $detect->isiOS() ){
			  		$page_id = get_option('ipad_page');
				}

				// For Android OS
				if( $detect->isAndroidOS() ){
					$page_id = get_option('android_tablet_page');
				}
			}

			 if($detect->isMobile() && isset($page_id) && trim($page_id) !== '' && is_home() || is_front_page()){
			    wp_redirect(get_permalink($page_id), 302);
			    die();
			 }
		}

		
	}

	MobileFrontPage::instance();
}
