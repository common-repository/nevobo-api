<?php

if ( !defined('ABSPATH') ) {
	die;
}

/*
 * Plugin Name:       Nevobo API
 * Plugin URI:        https://nl.wordpress.org/plugins/nevobo/
 * Description:       Deze plugin verwerkt de RSS van de Nevobo volleybal competitie op je website.
 * Version:           1.2.2
 * Requires at least: 5.2
 * Requires PHP:      5.6
 * Author:            Daan van Deventer
 * Author URI:        https://www.studiovandeventer.nl/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       nevobo-api
 * Domain Path:       /languages

   Nevobo API is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.
 
   Nevobo API is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU General Public License for more details.
 
   You should have received a copy of the GNU General Public License
   along with Nevobo API. If not, see License File.
*/

require_once plugin_dir_path( __FILE__ ) . '/classes/StreamRender.php';
require_once plugin_dir_path( __FILE__ ) . '/classes/FormContainer.php';

class MakeNevoboAPI {
    protected $pluginPath;
	protected $pluginUrl;
	
	public $pluginln = 'Nevobo API';
	public $pluginsn = 'nvb_';
     
    public function __construct() {
		add_shortcode('nevobo',  array( $this, 'NevoboShortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'NevoboStyles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'NevoboScripts' ) );
		add_action( 'admin_menu', array( $this, 'NevoboAdminMenu' ) );
		add_action( 'admin_init', array( $this, 'NevoboRegisterSettings' ) );
	}
     
    public function __destruct() {}

	public function NevoboShortcode( $atts ) {		
		$atts = shortcode_atts(
			array(
				'feed' => '0',
				'vereniging' => get_option( $this -> pluginsn . 'algemeen-vereniging' ),
				'aantal' => '0',
				'maps_home' => get_option( $this -> pluginsn . 'algemeen-locatie' ),
				'datumtijd' => get_option( $this -> pluginsn . 'algemeen-datumtijd', 'd F H:i' ),
				'highlight_color' => get_option( $this -> pluginsn . 'algemeen-highlight', '#444444' ),
				'icon_color' => get_option( $this -> pluginsn . 'algemeen-icon', '#444444' ),
				'css_style' => get_option( $this -> pluginsn . 'algemeen-style' ),
				'style' => get_option( $this -> pluginsn . 'algemeen-htmlstyle' ),
				'naamlengte_stand' => get_option( $this -> pluginsn . 'table-maxname', '30' ),
				'naamlengte_prog' => get_option( $this -> pluginsn . 'table-fixture', '30' ),
				'setpunten' => get_option( $this -> pluginsn . 'table-sets', '0' ),
				'sporthal' => get_option( $this -> pluginsn . 'fixture-location', '0' ),
				'plaats' => get_option( $this -> pluginsn . 'fixture-city', '0' ),
				'ical' => get_option( $this -> pluginsn . 'fixture-ical', '0' ),
				'nevobo_maps' => get_option( $this -> pluginsn . 'fixture-route', '0' ),
				'naamlengte_uitslag' => get_option( $this -> pluginsn . 'table-result', '30' ),
				'sets' => get_option( $this -> pluginsn . 'result-sets', '0' ),
				'id' => '0',
				'type' => '0',
		), $atts, 'nevobo');
		
		$NevoboPrintRSS = new NevoboPrintStreams();
		return $NevoboPrintRSS -> NevoboShowFeed( $atts );
	}

	public function NevoboStyles() {
		wp_enqueue_style( 'nevobo_feed_style', plugins_url( '/public/css/style.css?v=2604', __FILE__ ), array(), null, 'all' );
		wp_enqueue_script('nvb_fontawesome', 'https://kit.fontawesome.com/2e9f61356f.js', array(), false, false);
	}
	
	public function NevoboScripts() {
		wp_enqueue_script('nvb_wp_connect', plugins_url('admin/js/wp_connect.js', __FILE__), array('wp-color-picker'), false, true);
	}
	
	public function NevoboAdminMenu() {
		add_menu_page(
			$this -> pluginln,
			$this -> pluginln,
			'manage_options',
			'nevobo-settings',
			array( $this, 'nvb_settings_page' ),
			plugin_dir_url(__FILE__) . 'admin/images/' . $this -> pluginsn . 'icon_admin.png',
			200
		);
	}
	
	public function NevoboRegisterSettings() {		
		$settingsArray = array (
			array( $this->pluginsn . 'algemeen-vereniging', '', ),
			array( $this->pluginsn . 'algemeen-locatie', '', ),
			array( $this->pluginsn . 'algemeen-highlight', '#444444', ),
			array( $this->pluginsn . 'algemeen-icon', '#444444', ),
			array( $this->pluginsn . 'algemeen-htmlstyle', '0', ),
			array( $this->pluginsn . 'algemeen-style', '', ),
			array( $this->pluginsn . 'algemeen-datumtijd', 'd F H:i', ),
			array( $this->pluginsn . 'table-maxname', '30', ),
			array( $this->pluginsn . 'table-maxrow', '12', ),
			array( $this->pluginsn . 'table-sets', '1', ),
			array( $this->pluginsn . 'fixture-maxname', '30', ),
			array( $this->pluginsn . 'fixture-maxrow', '6', ),
			array( $this->pluginsn . 'fixture-location', '1', ),
			array( $this->pluginsn . 'fixture-city', '1', ),
			array( $this->pluginsn . 'fixture-ical', '1', ),
			array( $this->pluginsn . 'fixture-route', '1', ),
			array( $this->pluginsn . 'result-maxname', '30', ),
			array( $this->pluginsn . 'result-maxrow', '6', ),
			array( $this->pluginsn . 'result-sets','1', ),
			array( $this->pluginsn . 'shortcode-text', '', ),
		);
	
		foreach ( $settingsArray as $setting ) {
			register_setting( 'nevobo-group', $setting[0] );
			if( FALSE === get_option( $setting[0] ) && FALSE === update_option( $setting[0] , FALSE ) ) {
				add_option( $setting[0], $setting[1] );
			}
		}
	}
	
	private function NevoboPostForm() {
		$data = $_POST;
		$content = '';

		if ( !empty( $data ) && ( !isset( $data[$this -> pluginsn . 'nonce_field'] ) || !wp_verify_nonce( $data[$this -> pluginsn . 'nonce_field'], $this -> pluginsn . 'submit' ) ) ) {
			$content .= '<div class="notice notice-warning is-dismissible"><p>' . __( 'The nonce did not verify!', 'nevobo-api' ) . '</p></div>';
 		} elseif( !empty( $data ) ) {
			$remove = array( 'submit', '_wp_http_referer', 'nvb_nonce_field' );

			foreach( $remove as $value ) {
				unset( $data[$value] );
			}

			foreach ($data as $key => $value) {
				if( !empty ( $key ) && isset( $data[ $key ] ) ) {
					update_option( $key, sanitize_textarea_field( $value ) );
				}
			}
			
			$content .= '<div class="notice notice-success is-dismissible"><p>' . __( 'The changes have been made!', 'nevobo-api' ) . '</p></div>';
		} else {
		//	$content .= '<div class="notice notice-warning is-dismissible"><p>' . __( 'There is no data received!', 'nevobo-api' ) . '</p></div>';
		}

		return $content;
	}
	
	function nvb_settings_page() {		
		echo '<div class="wrap"><h1>' . $this -> pluginln . ' ' . __( 'Settings', 'nevobo-api' ) . '</h1>';
			
		if( isset( $_GET['tab'] ) ) {
			$active_tab = $_GET['tab'];
		} else {
			$active_tab = 'default-options';
		}
	
		echo '<h2 class="nav-tab-wrapper">';
		echo '<a href="?page=nevobo-settings&tab=default-options" class="nav-tab ';
		if( $active_tab == 'default-options' ) {
			echo 'nav-tab-active';
		}
		echo '">' . __('General', 'nevobo-api') . '</a>';
		echo '<a href="?page=nevobo-settings&tab=feed-options" class="nav-tab ';
		if( $active_tab == 'feed-options' ) {
			echo 'nav-tab-active';
		}
		echo '">' . __('Feed Setup', 'nevobo-api') . '</a>';
		echo '<a href="?page=nevobo-settings&tab=shortcodes" class="nav-tab ';
		if( $active_tab == 'shortcodes' ) {
			echo 'nav-tab-active';
		}
		echo '">' . __('Shortcodes', 'nevobo-api') . '</a>';
		echo '<a href="?page=nevobo-settings&tab=information" class="nav-tab ';
		if( $active_tab == 'information' ) {
			echo 'nav-tab-active';
		}
		echo '">' . __('Information', 'nevobo-api') . '</a>';
		echo '</h2>';

		settings_errors();

		echo $this->NevoboPostForm();

		$settings = new FormContainer();
		echo $settings->Create( $active_tab );
		echo '</div>';
	}
}
$NevoboStartPlugin = new MakeNevoboAPI();

?>
