<?php

if ( !defined('ABSPATH') ) {
	die;
}

/*
 * Plugin Name:       Nevobo API
 * Plugin URI:        https://nl.wordpress.org/plugins/nevobo/
 * Author:            Daan van Deventer
 * Author URI:        https://www.studiovandeventer.nl/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * 
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

class NevoboGetStreams {
	function LoadURL( $feed ) {
		$curl = curl_init();

		curl_setopt_array($curl, Array(
			CURLOPT_URL            => "$feed",
			CURLOPT_USERAGENT      => 'Wordpress Nevobo API cURL 1.1',
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_ENCODING       => 'UTF-8'
		));

		$data = curl_exec($curl);

		// Check HTTP status code
		if (!curl_errno($curl)) {
			switch ($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
				case 200: # OK
					$xml = new SimpleXMLElement( $data, LIBXML_NOCDATA );
					break;
				case 404: # ERROR 404
					$xml = NULL;
					throw new Exception( __( 'The following feed could not been found: ', 'nevobo-api' ) . $feed . '.' );
					break;
				default: # Default
					$xml = NULL;
					throw new Exception( __( 'Unexpected HTTP code ', 'nevobo-api' ) . $http_code );
			}
		}

		curl_close($curl);

		return $xml;
	}

	function GetStand( $feed ) {
		try {
			$xml = $this -> LoadURL( $feed );
			
			$array = NULL;
			$arrayCount = 0;

			$xml -> registerXPathNamespace( 'stand', 'https://www.nevobo.nl/competitie' );
		
			$number = $xml -> xpath( '//stand:nummer' );
			$team = $xml -> xpath( '//stand:team' );
			$games = $xml -> xpath( '//stand:wedstrijden' );
			$points = $xml -> xpath( '//stand:punten' );
			$setswin = $xml -> xpath( '//stand:setsvoor' );
			$setslose = $xml -> xpath( '//stand:setstegen' );
			$pointswin = $xml -> xpath( '//stand:puntenvoor' );
			$pointslose = $xml -> xpath( '//stand:puntentegen' );
	
			foreach ($number as $numbers) {
				$array[$arrayCount] = [
					"number" => "$number[$arrayCount]",
					"team" => "$team[$arrayCount]",
					"games" => "$games[$arrayCount]",
					"points" => "$points[$arrayCount]",
					"setswin" => "$setswin[$arrayCount]",
					"setslose" => "$setslose[$arrayCount]",
					"pointswin" => "$pointswin[$arrayCount]",
					"pointslose" => "$pointslose[$arrayCount]",
				];
				$arrayCount++;
			}
		}
		catch (Exception $ex) {
			echo '<div class="notice notice-error">Nevobo API: ' . $ex->getMessage() . '</div>';
			$array = NULL;
		}

		return json_encode($array, JSON_PRETTY_PRINT);
	}
	
	function GetProgramma( $feed ) {
		try {
			$xml = $this -> LoadURL( $feed );
			
			$array = NULL;
			$arrayCount = 0;
			
			foreach ($xml->channel->item as $item) {
				preg_match('#: ([a-zA-Z0-9 ].*) - ([a-zA-Z0-9 ].*)#', $item->title, $title);
				preg_match('#Wedstrijd: (.*), Datum: (.*), (.*), Speellocatie: (.*), (.*), ([^\s]+)  (.*)#', $item->description, $description);
					
				if( empty( $title[0] ) || empty( $description[5] ) ) {
					continue;
				}

				$city = ucfirst( strtolower( $description[7] ) );
				$array[$arrayCount] = [
					"date" => "$item->pubDate",
					"home" => "$title[1]",
					"out" => "$title[2]",
					"location" => "$description[4]",
					"city" => "$city",
					"adress" => "$description[5] $description[6] $description[7]",
					"link" => "$item->link",
					"code" => "$description[1]",
				];
				$arrayCount++;
			}
		}
		catch (Exception $ex) {
			echo '<div class="notice notice-error">Nevobo API: ' . $ex->getMessage() . '</div>';
			$array = NULL;
		}
		
		return json_encode($array, JSON_PRETTY_PRINT);
	}

	function GetResultaten( $feed ) {
		try {
			$xml = $this -> LoadURL( $feed );
			
			$array = NULL;
			$arrayCount = 0;

			foreach ($xml->channel->item as $item) {
				
				if( preg_match("/\bVervallen\b/i", $item->title, $title) ) {
					preg_match('#: ([a-zA-Z0-9 ].*): ([a-zA-Z0-9 ].*) - ([a-zA-Z0-9 ].*)#', $item->title, $title);
								
					$array[$arrayCount] = [
						"date" => "$item->pubDate",
						"home" => "$title[2]",
						"out" => "$title[3]",
						"result" => "Vervallen",
						"sets" => "-",
						"link" => "$item->link"
					];
				} else {
					preg_match('#Wedstrijd: ([\w\W\s\S\d\D]+) - ([\w\W\s\S\d\D]+), Uitslag: ([\d])-([\d]), Setstanden: ([\w\W\s\S\d\D]+)#', $item->description, $description);

					if( empty( $description[0] ) ) {
						continue;
					}
					$result = $description[3] . '-' . $description[4];	

					$array[$arrayCount] = [
						"date" => "$item->pubDate",
						"home" => "$description[1]",
						"out" => "$description[2]",
						"result" => "$result",
						"sets" => "$description[5]",
						"link" => "$item->link"
					];
				}

				$arrayCount++;
			}
		}
		catch (Exception $ex) {
			echo '<div class="notice notice-error">Nevobo API: ' . $ex->getMessage() . '</div>';
			$array = NULL;
		}
		
		return json_encode($array, JSON_PRETTY_PRINT);
    }
}

?>