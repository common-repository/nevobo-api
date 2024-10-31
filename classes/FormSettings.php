<?php

if ( !defined('ABSPATH') ) {
	die;
}

class FormSettings extends FormContainer {
	public $pluginsn = 'nvb_';

	public function Fields() {
		$options = array( 
			// General Options
			array('name' => __( 'General', 'nevobo-api' ),
					'type' => 'sub-section-1',
					'category' => 'default-options',
			),
			array('name' => __( 'Club', 'nevobo-api' ),
					'desc' => __( 'Enter the club name to give it a different color.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'algemeen-vereniging',
					'type' => 'text',
					'parent' => 'default-options',
					'std' => '',
			),
			array('name' => __( 'Location', 'nevobo-api' ),
					'desc' => __( 'Enter the location of the home base. This will be used for the shortcut with Google Maps.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'algemeen-locatie',
					'type' => 'text',
					'parent' => 'default-options',
					'std' => '',
			),
			array('name' => __( 'Highlight color', 'nevobo-api' ),
					'desc' => __( 'Pick the color for the highlights.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'algemeen-highlight',
					'type' => 'color-picker',
					'parent' => 'default-options',
					'std' => '#444444',
			),
			array('name' => __( 'Icon color', 'nevobo-api' ),
					'desc' => __( 'Pick the color for the icons.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'algemeen-icon',
					'type' => 'color-picker',
					'parent' => 'default-options',
					'std' => '#444444',
			),
			array('name' => __( 'HTML Style', 'nevobo-api' ),
					'desc' => __( 'Want to use TABLE instead of DIV?', 'nevobo-api' ),
					'id' => $this->pluginsn . 'algemeen-htmlstyle',
					'type' => 'select',
					'options' => array(
						0 =>  __( 'Table Style', 'nevobo-api' ),
						1 => __( 'Nevobo Style', 'nevobo-api' ),
					),
					'parent' => 'default-options',
					'std' => '0',
			),
			array('name' => __( 'CSS Style', 'nevobo-api' ),
					'desc' => __( 'Enter the default style for on the website.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'algemeen-style',
					'type' => 'text',
					'parent' => 'default-options',
					'std' => '',
			),
			array('name' => __( 'Date format', 'nevobo-api' ),
					'desc' => __( 'Enter the default date format for the website.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'algemeen-datumtijd',
					'type' => 'text',
					'parent' => 'default-options',
					'std' => 'd F H:i',
			),
			// Table Options
			array('name' => __( 'Ranking', 'nevobo-api' ),
					'type' => 'sub-section-1',
					'category' => 'feed-options',
			),
			array('name' => __( 'Club name length', 'nevobo-api' ),
					'desc' => __( 'Specify the maximum length of the club name.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'table-maxname',
					'type' => 'text',
					'parent' => 'feed-options',
					'std' => '30',
			),
			array('name' => __( 'Visible rows', 'nevobo-api' ),
					'desc' => __( 'Specify the number of lines.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'table-maxrow',
					'type' => 'text',
					'parent' => 'feed-options',
					'std' => '8',
			),
			array('name' => __( 'Set positions', 'nevobo-api' ),
					'desc' => __( 'Make the set positions visible?', 'nevobo-api' ),
					'id' => $this->pluginsn . 'table-sets',
					'type' => 'select',
					'options' => array(
						0 => __( 'Invisible', 'nevobo-api' ),
						1 =>  __( 'Visible', 'nevobo-api' ),
					),
					'parent' => 'feed-options',
					'std' => '0',
			),
			// Fixture Options
			array('name' => __( 'Programs', 'nevobo-api' ),
					'type' => 'sub-section-1',
					'parent' => 'feed-options',
			),
			array('name' => __( 'Club name length', 'nevobo-api' ),
					'desc' => __( 'Specify the maximum length of the club name.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'fixture-maxname',
					'type' => 'text',
					'parent' => 'feed-options',
					'std' => '30',
			),
			array('name' => __( 'Visible rows', 'nevobo-api' ),
					'desc' => __( 'Specify the number of lines.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'fixture-maxrow',
					'type' => 'text',
					'parent' => 'feed-options',
					'std' => '8',
			),
			array('name' => __( 'Sports complex name', 'nevobo-api' ),
					'desc' => __( 'Want to show the sports complex name?', 'nevobo-api' ),
					'id' => $this->pluginsn . 'fixture-location',
					'type' => 'select',
					'options' => array(
						0 => __( 'Invisible', 'nevobo-api' ),
						1 =>  __( 'Visible', 'nevobo-api' ),
					),
					'parent' => 'feed-options',
					'std' => '0',
			),
			array('name' => __( 'Sports complex city', 'nevobo-api' ),
					'desc' => __( 'Want to show the sports complex location?', 'nevobo-api' ),
					'id' => $this->pluginsn . 'fixture-city',
					'type' => 'select',
					'options' => array(
						0 => __( 'Invisible', 'nevobo-api' ),
						1 =>  __( 'Visible', 'nevobo-api' ),
					),
					'parent' => 'feed-options',
					'std' => '0',
			),
			array('name' => __( 'Ical', 'nevobo-api' ),
					'desc' => __( 'Want to show the ical link?', 'nevobo-api' ),
					'id' => $this->pluginsn . 'fixture-ical',
					'type' => 'select',
					'options' => array(
						0 => __( 'Invisible', 'nevobo-api' ),
						1 =>  __( 'Visible', 'nevobo-api' ),
					),
					'parent' => 'feed-options',
					'std' => '0',
			),
			array('name' => __( 'Navigation', 'nevobo-api' ),
					'desc' => __( 'Want to show the navigation icon?', 'nevobo-api' ),
					'id' => $this->pluginsn . 'fixture-route',
					'type' => 'select',
					'options' => array(
						0 => __( 'Invisible', 'nevobo-api' ),
						1 =>  __( 'Visible', 'nevobo-api' ),
					),
					'parent' => 'feed-options',
					'std' => '0',
			),
			// Result Options
			array('name' => __( 'Results', 'nevobo-api' ),
					'type' => 'sub-section-1',
					'parent' => 'feed-options',
			),
			array('name' => __( 'Club name length', 'nevobo-api' ),
					'desc' => __( 'Specify the maximum length of the club name.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'result-maxname',
					'type' => 'text',
					'parent' => 'feed-options',
					'std' => '30',
			),
			array('name' => __( 'Visible rows', 'nevobo-api' ),
					'desc' => __( 'Specify the number of lines.', 'nevobo-api' ),
					'id' => $this->pluginsn . 'result-maxrow',
					'type' => 'text',
					'parent' => 'feed-options',
					'std' => '8',
			),
			array('name' => __( 'Set positions', 'nevobo-api' ),
					'desc' => __( 'Make the set positions visible?', 'nevobo-api' ),
					'id' => $this->pluginsn . 'result-sets',
					'type' => 'select',
					'options' => array(
						0 => __( 'Invisible', 'nevobo-api' ),
						1 =>  __( 'Visible', 'nevobo-api' ),
					),
					'parent' => 'feed-options',
					'std' => '0',
			),
			// Shortcode
			array('name' => __( 'Define shortcodes', 'nevobo-api' ),
					'type' => 'sub-section-1',
					'category' => 'shortcodes',
			),
			array('name' => __( 'RSS shortcodes', 'nevobo-api' ),
					'desc' => __( 'Make your own RSS shortcode. Max one on each row. Use: "YOURSHORTNAME URL.rss"', 'nevobo-api' ),
					'id' => $this->pluginsn . 'shortcode-text',
					'type' => 'array',
					'parent' => 'shortcodes',
					'std' => 'HS2-table https://api.nevobo.nl/export/poule/regio-west/H1F/stand.rss'
			),
			// Informations
			array('name' => __( 'Information', 'nevobo-api' ),
					'type' => 'page',
					'category' => 'information',
			),
		);

		return $options;
	}
}

?>