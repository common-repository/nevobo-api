<?php

if ( !defined('ABSPATH') ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . 'FormSettings.php';
require_once plugin_dir_path( __FILE__ ) . 'FormTags.php';

class FormContainer {
	public $pluginsn = 'nvb_';
	
	public function Create( $active_tab ) {
		$settings = new FormSettings();
		$create = new FormCreate();

		$options = $settings->Fields();

		if( $active_tab == 'information' ) {
			include_once( plugin_dir_path( __FILE__ ) . 'PageInformation.php');
		} else {
			$content = '<form method="post">';
			$content .= '<table class="form-table" role="presentation"><tbody><tr>';
			
			settings_fields('nevobo-group');
			do_settings_sections('nevobo-group');

			foreach ( $options as $value ) {
				if( isset( $value['parent'] ) ) {
					$load = $value['parent'];
				} else {
					$load = $value['category'];
				}

				if( $active_tab == $load ) {
					switch ( $value['type'] ) {
						case 'sub-section-1';
							$content .= $create->Header1( $value );
						break;
				
						case 'sub-section-2';
							$content .= $create->Header2( $value );
						break;
				
						case 'sub-section-3';
							$content .= $create->Header3( $value );
						break;
				
						case 'text';
							$content .= $create->SectionText( $value );
						break;
				
						case 'array';
						$content .= $create->SectionArray( $value );
						break;
				
						case 'color-picker':
						$content .= $create->SectionColorPicker( $value );
						break;
							
						case 'select':
						$content .= $create->SectionSelect( $value );
						break;
					}
				}
			}
			
			$content .= '</tbody></table>';
			$content .= wp_nonce_field( $this -> pluginsn . 'submit', $this -> pluginsn . 'nonce_field' );
			$content .= get_submit_button();
			$content .= '</form>';

			return $content;
		}
	}
}