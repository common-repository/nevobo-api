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

include_once plugin_dir_path( __FILE__ ) . '/StreamGet.php';

class NevoboPrintStreams {
	public function NevoboShowFeed( $data ) {
		if( !empty( $data['id'] ) ) {
			$url = explode( PHP_EOL, get_option( 'nvb_shortcode-text' ) );
			if( !empty( $url ) ) {
				foreach ( $url as $line ) {
					preg_match( "/(.*) (.*)/", trim( $line ), $match );
					//echo "<br/><br/>ID: '" . $data['id'] . "'<br/>FULL: '" . $match[0] . "'<br/>PID: '" . $match[1] . "'<br/>PURL: '" . $match[2] . "'<br/><br/>";
					if( ($match[1] == $data['id']) && !empty( $match[2] ) ) {
						$data['feed'] = $match[2];
						$FeedSwitch = substr(strrchr($match[2], '/'), 1);
					}
				}
			}
		} else {
			$FeedSwitch = strtolower( substr( strrchr( $data['feed'], "/" ), 1 ) );
		}

		$content = '';
		$content .= '<!-- Start Nevobo API -->';
		$content .= '<!-- https://wordpress.org/plugins/nevobo-api/ -->';
		$content .= '<div class="nevobofeed">';
	
		if( $data['style'] === '1' ) {
			if( $FeedSwitch === 'stand.rss' ) {
				$content .= $this->ShowStand( $data );
			} elseif( $FeedSwitch === 'resultaten.rss' ) {
				$content .= $this->ShowResultatenDiv( $data );
			} elseif( $FeedSwitch === 'programma.rss' ) {
				$content .= $this->ShowProgrammaDiv( $data);
			} else {
				$content .= __( 'Please insert a correct RSS URL.', 'nevobo-api' );
				$content .= ' Error: [' . $data['id'] . ']';
			}
		} else {
			if( $FeedSwitch === 'stand.rss' ) {
				$content .= $this->ShowStand( $data );
			} elseif( $FeedSwitch === 'resultaten.rss' ) {
				$content .= $this->ShowResultaten( $data );
			} elseif( $FeedSwitch === 'programma.rss' ) {
				$content .= $this->ShowProgramma( $data);
			} else {
				$content .= __( 'Please insert a correct RSS URL.', 'nevobo-api' );
				$content .= ' Error: [' . $data['id'] . ']';
			}
		}
	
		$content .= '</div>';
		$content .= '<!-- End Nevobo API -->';
		$content .= '';
	
		return $content;
	}

	private function ShowStand( $data ) {

		// Load Defaults
		if( $data['aantal'] === '0' ) {
			$data['aantal'] = get_option( 'nvb_table-maxrow' );
		}

		$content = '<table class="nevobotable ' . $data['css_style'] . '">';
		$content .= '<thead><tr>';
		$content .= '<th scope="col">' . __( '#', 'nevobo-api' ) . '</th>';
		$content .= '<th scope="col">' . __( 'Team', 'nevobo-api' ) . '</th>';
		$content .= '<th scope="col">' . __( 'G', 'nevobo-api' ) . '</th>';
		$content .= '<th scope="col">' . __( 'P', 'nevobo-api' ) . '</th>';

		if( $data['setpunten'] === '1' ) {
			$content .= '<th scope="col">' . __( 'W', 'nevobo-api' ) . '</th>';
			$content .= '<th scope="col">' . __( 'L', 'nevobo-api' ) . '</th>';
			$content .= '<th scope="col">' . __( '+', 'nevobo-api' ) . '</th>';
			$content .= '<th scope="col">' . __( '-', 'nevobo-api' ) . '</th>';
		}

		$content .= '</tr></thead>';
		$content .= '<tbody>';
		
		$GetStreams = new NevoboGetStreams();
		$array = json_decode( $GetStreams->GetStand( $data['feed'] ) );

		$c = 0;

		if( !empty( $array ) ) {
			foreach ( $array as $row ) {
				if ( $c > $data['aantal'] - 1 ) {
					break;
				}

				if( !empty( $data['vereniging'] ) ) {
					if( stristr( $row->team, $data['vereniging'] ) ) {
						$content .= '<tr style="color:' . $data['highlight_color'] . '">';
					} else {
						$content .= '<tr>';
					}
				}
				$content .= '<td>' . $row->number . '</td>';
				$content .= '<td>' . $row->team . '</td>';
				$content .= '<td>' . $row->games . '</td>';
				$content .= '<td>' . $row->points . '</td>';

				if( $data['setpunten'] === '1' ) {
					$content .= '<td>' . $row->setswin . '</td>';
					$content .= '<td>' . $row->setslose . '</td>';
					$content .= '<td>' . $row->pointswin . '</td>';
					$content .= '<td>' . $row->pointslose . '</td>';
				}

				$content .= '</tr>';
				$c++;
			}
		} else {
			$content .= '<tr><td>';
			$content .= __( 'The table is not available.', 'nevobo-api' );
			$content .= '</td></tr>';
		}
		
		$content .= '</tbody>';
		$content .= '</table>';

		return $content;
	}

	private function ShowStandDiv( $data ) {}

	private function ShowResultaten( $data ) {
		$GetStreams = new NevoboGetStreams();

		// Load Defaults
		if( $data['aantal'] === '0' ) {
			$data['aantal'] = get_option( 'nvb_result-maxrow' );
		}
		
		$content = '<table class="nevobotable ' . $data['css_style'] . '">';
		$content .= '<thead><tr>';
		$content .= '<th scope="col">' . __( 'Date', 'nevobo-api' ) . '</th>';
		$content .= '<th scope="col">' . __( 'Game', 'nevobo-api' ) . '</th>';
		$content .= '<th scope="col">' . __( 'Result', 'nevobo-api' ) . '</th>';
		
		if( $data['sets'] === '1' ) {
			$content .= '<th scope="col">' . __( 'Sets', 'nevobo-api' ) . '</th>';
		}
		
		$content .= '</tr></thead>';
		$content .= '<tbody>';
		
		$array = json_decode( $GetStreams->GetResultaten( $data['feed'] ) );

		$c = 0;
		if( !empty( $array ) ) {
			foreach ($array as $row) {
				if ($c > $data['aantal'] - 1) {
					break;
				}

				date_default_timezone_set( 'Europe/Amsterdam' );
				$nvb_date = $row->date;
				if( empty( $nvb_date) ) continue;
				$nvb_date = date( $data['datumtijd'], strtotime( $nvb_date ) );

				$content .= '<tr>';
				$content .= '<td>' . $nvb_date . '</td>';
				
				if( !empty( $data['vereniging'] ) ) {
					if (stristr( $row->home, $data['vereniging'] ) ) {
						$content .= '<td><span style="color:' . $data['highlight_color'] . '">' . $row->home . '</span> - ' . $row->out . '</td>';
					} elseif (stristr( $row->out, $data['vereniging'] ) ) {
						$content .= '<td>' . $row->home . ' - <span style="color:' . $data['highlight_color'] . '">' . $row->out . '</span></td>';
					} else {
						$content .= '<td>' . $row->home . ' - ' . $row->out . '</td>';
					}
				} else {
						$content .= '<td>' . $row->home . ' - ' . $row->out . '</td>';
				}
				
				if( isset( $row->result ) ) {
					$content .= '<td>' . $row->result . '</td>';
				} else {
					$content .= '<td>' . __( 'Unknown', 'nevobo-api' ) . '</td>';
				}
				
				if ($data['sets'] === '1' ) {
					$content .= '<td>' . $row->sets . '</td>';
				}

				$content .= '</tr>';

				$c++;
			}
		} else {
			if( $data['sets'] == '1' ) {
				$colspan = '4';
			} else {
				$colspan = '3';
			}
			$content .= '<tr><td colspan="' . $colspan . '">';
			$content .= __( 'No results are known.', 'nevobo-api' );
			$content .= '</td></tr>';
		}
					
		$content .= '</tbody></table>';
		
		return $content;
	}

	private function ShowResultatenDiv( $data ) {

		// Load Defaults
		if( $data['aantal'] === '0' ) {
			$data['aantal'] = get_option( 'nvb_result-maxrow' );
		}
		
		$GetStreams = new NevoboGetStreams();
		$array = json_decode( $GetStreams->GetResultaten( $data['feed'] ) );
		
		$content = '';

		$c = 0;
		if( !empty( $array ) ) {
			foreach ($array as $row) {
				if ($c > $data['aantal'] - 1) {
					break;
				}

				date_default_timezone_set( 'Europe/Amsterdam' );
				$nvb_date = $row->date;
				if( empty( $nvb_date) ) continue;
				$nvb_date = date( "l d F", strtotime( $nvb_date ) );

				$content .= '
				<div class="row matchrow" data-clickable-history="" data-load-more-item="">
					<div class="small-12 medium-12 large-3 column">
						<div class="match-detail-container text-left small-only-text-center medium-only-text-center">
							<div class="match-detail-content">
								<a class="title" href="' . $row->link . '" data-clickable-link="" target="_blank">' . $nvb_date . '</a>
							</div>
						</div>
					</div>';

				$content .= '
					<div class="small-12 medium-12 large-6 column">
						<div class="match-detail-container text-center">
							<div class="match-detail-content">
								<div class="match-detail">
									<div class="match-detail-name match-left bold">
										<span>' . $row->home . '</span>
									</div>
									<div class="match-label background-orange">' . $row->result . '</div>
									<div class="match-detail-name match-right">
										<span>' . $row->out . '</span>
									</div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>';

				if ($data['sets'] === '1' ) {
					$content .= '
						<div class="small-12 medium-12 large-3 column">
							<div class="match-detail-container text-right small-only-text-center medium-only-text-center">
								<div class="match-detail-content">
									<div class="match-detail-bottom">
										<span class="title">Sets: ' . $row->sets . '</span>
									</div>
								</div>
							</div>
						</div>
					</div>';
				} else {
					$content .= '
						<div class="small-12 medium-12 large-3 column">
							<div class="match-detail-container text-right small-only-text-center medium-only-text-center">
								<div class="match-detail-content">
									<div class="match-detail-bottom"></div>
								</div>
							</div>
						</div>
					</div>';
				}

				$c++;
			}
		} else {
			$content .= '<div class="row matchrow" data-clickable-history="" data-load-more-item="">';
			$content .= __( 'No results are known.', 'nevobo-api' );
			$content .= '</div>';
		}
			
		return $content;
	}

	private function ShowProgramma( $data ) {
		$GetStreams = new NevoboGetStreams();

		// Load Defaults
		if( $data['aantal'] === '0' ) {
			$data['aantal'] = get_option( 'nvb_fixture-maxrow' );
		}

		$content = '<table class="nevobotable ' . $data['css_style'] . '">';
		$content .= '<thead><tr>';
		$content .= '<th scope="col">' . __( 'When', 'nevobo-api' ) . '</th>';
		$content .= '<th scope="col">' . __( 'Game', 'nevobo-api' ) . '</th>';
		
		if( ( $data['plaats'] === '1' ) || ( $data['sporthal'] === '1' ) ) {
			$content .= '<th scope="col">' . __( 'Location', 'nevobo-api' ) . '</th>';
		}
		if( $data['nevobo_maps'] === '1' ) {
			$content .= '<th scope="col">' . __( 'Navigation', 'nevobo-api' ) . '</th>';
		}
		
		$content .= '</tr></thead>';
		$content .= '<tbody>';
	
		$array = json_decode( $GetStreams -> GetProgramma( $data['feed'] ) );
		
		$c = 0;
		if( !empty( $array ) ) {
			foreach ($array as $row) {
				if ($c > $data['aantal'] - 1) {
					break;
				}

				date_default_timezone_set( 'Europe/Amsterdam' );
				$nvb_date = $row->date;
				if( empty( $nvb_date ) ) continue;
				$nvb_date = date( $data['datumtijd'], strtotime( $nvb_date ) );

				$content .= '<tr>';
				$content .= '<td>' . $nvb_date . '</td>';

				if( !empty( $data['vereniging'] ) ) {
					if( stristr( $row->home, $data['vereniging'] ) ) {
						$content .= '<td><span style="color:' . $data['highlight_color'] . '">' . $row->home . '</span> - ' . $row->out . '</td>';
					} elseif( stristr( $row->out, $data['vereniging'] ) ) {
						$content .= '<td>' . $row->home . ' - <span style="color:' . $data['highlight_color'] . '">' . $row->out . '</span></td>';
					} else {
						$content .= '<td>' . $row->home . ' - ' . $row->out . '</td>';
					}
				} else {
						$content .= '<td>' . $row->home . ' - ' . $row->out . '</td>';
				}

				if( ( $data['plaats'] === '1' ) && ( $data['sporthal'] === '1' ) ) {
					$content .= '<td>' . $row->location . ' ' . __( 'in', 'nevobo-api' ) . ' ' . $row->city . '</td>';
				} elseif( ( $data['plaats'] === '0' ) && ( $data['sporthal'] === '1' ) ) {
					$content .= '<td>' . $row->location . '</td>';
				} elseif( ( $data['plaats'] === '1' ) && ( $data['sporthal'] === '0' ) ) {
					$content .= '<td>' . $row->city . '</td>';
				}

				if( $data['nevobo_maps'] === '1' ) {
					$route = "https://maps.google.com?saddr=" . $data['maps_home'] . "&daddr=" . $row->adress;
					$content .= '<td><a href="' . $route . '" target="_blank"><span style="color:' . $data['icon_color'] . '"><i class="fas fa-map-marked-alt fa-fw" aria-hidden="true"></i></span></a></td>';
				}

				$content .= '</tr>';
				$c++;
			}
		} else {
			if( ( $data['plaats'] === '1' ) || ( $data['sporthal'] === '1' ) && ( $data['nevobo_maps'] === '1' ) ) {
				$colspan = '4';
			} elseif( ( $data['plaats'] === '1' ) || ( $data['sporthal'] === '1' ) && ( $data['nevobo_maps'] !== '1' ) ) {
				$colspan = '3';
			} else {
				$colspan = '2';
			}
			$content .= '<tr><td colspan="' . $colspan . '">';
			$content .= __( 'There are no matches scheduled.', 'nevobo-api' );
			$content .= '</td></tr>';
		}
		
		$content .= '</tbody></table>';

		if( $data['ical'] === '1' ) {
			$icalfeed = str_replace( '.rss', '.ics', $data['feed'] );
			if( stristr( $icalfeed, 'poule' ) ) {
				$content .= '<div class="nevobotable"><span style="color:' . $data['icon_color'] . '"><i class="fas fa-calendar-alt fa-fw" aria-hidden="true"></i></span> <a href="' . $icalfeed . '">' . __( 'Add the full program of the poule to your calendar.', 'nevobo-api' ) . '</a></div>';
			} else {
				$content .= '<div class="nevobotable"><span style="color:' . $data['icon_color'] . '"><i class="fas fa-calendar-alt fa-fw" aria-hidden="true"></i></span> <a href="' . $icalfeed . '">' . __( 'Add the full program of the team to your calendar.', 'nevobo-api' ) . '</a></div>';
			}
		}

		return $content;
	}

	private function ShowProgrammaDiv( $data ) {
		$GetStreams = new NevoboGetStreams();

		// Load Defaults
		if( $data['aantal'] === '0' ) {
			$data['aantal'] = get_option( 'nvb_fixture-maxrow' );
		}

		$content = '';
		$array = json_decode( $GetStreams -> GetProgramma( $data['feed'] ) );
		
		$c = 0;
		if( !empty( $array ) ) {
			foreach ($array as $row) {
				if ($c > $data['aantal'] - 1) {
					break;
				}

				date_default_timezone_set( 'Europe/Amsterdam' );
				$nvb_date = $row->date;
				if( empty( $nvb_date ) ) continue;
				$nvb_date = date( 'd F', strtotime( $nvb_date ) );
				$nvb_time = date( 'H:i', strtotime( $nvb_date ) );

				if( !empty( $data['vereniging'] ) ) {
					if( stristr( $row->home, $data['vereniging'] ) ) {
						$nvb_match = '
							<div class="match-detail-name bold match-left">
								<span>' . $row->home . '</span>
							</div>
							<div class="match-label background-orange">' . $nvb_time . '</div>
							<div class="match-detail-name match-right">
								<span>' . $row->out . '</span>
							</div>';
					} elseif( stristr( $row->out, $data['vereniging'] ) ) {
						$nvb_match = '
							<div class="match-detail-name match-left">
								<span>' . $row->home . '</span>
							</div>
							<div class="match-label background-orange">' . $nvb_time . '</div>
							<div class="match-detail-name bold match-right">
								<span>' . $row->out . '</span>
							</div>';
					} else {
						$nvb_match = '
							<div class="match-detail-name match-left">
								<span>' . $row->home . '</span>
							</div>
							<div class="match-label background-orange">' . $nvb_time . '</div>
							<div class="match-detail-name match-right">
								<span>' . $row->out . '</span>
							</div>';
					}
				} else {
					$nvb_match = '
						<div class="match-detail-name match-left">
							<span>' . $row->home . '</span>
						</div>
						<div class="match-label background-orange">' . $nvb_time . '</div>
						<div class="match-detail-name match-right">
							<span>' . $row->out . '</span>
						</div>';
				}

				if( ( $data['plaats'] === '1' ) && ( $data['sporthal'] === '1' ) ) {
					$nvb_location = $row->location . ' ' . __( 'in', 'nevobo-api' ) . ' ' . $row->city;
				} elseif( ( $data['plaats'] === '0' ) && ( $data['sporthal'] === '1' ) ) {
					$nvb_location = $row->location;
				} elseif( ( $data['plaats'] === '1' ) && ( $data['sporthal'] === '0' ) ) {
					$nvb_location = $row->city;
				} else {
					$nvb_location = '';
				}

				if( $data['nevobo_maps'] === '1' ) {
					$nvb_maps = '<a href="https://maps.google.com?saddr=' . $data["maps_home"] . '&daddr=' . $row->adress . '" target="_blank"><span style="color:' . $data['icon_color'] . '"><i class="fas fa-map-marked-alt fa-fw" aria-hidden="true"></i></span></a>';
				} else {
					$nvb_maps = '';
				}

				$content .= '
				<div class="row matchrow" data-clickable-history="" data-load-more-item="">
					<div class="small-12 medium-12 large-3 column">
						<div class="match-detail-container text-left small-only-text-center medium-only-text-center">
							<div class="match-detail-content">
								<a class="title" href="' . $row->link . '" data-clickable-link="">' . $nvb_date . '</a>
							</div>
						</div>
					</div>
				
					<div class="small-12 medium-12 large-6 column">
						<div class="match-detail-container text-center">
							<div class="match-detail-content">
								<div class="match-detail">
									' . $nvb_match . '
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="small-12 medium-12 large-3 column">
						<div class="match-detail-container text-right small-only-text-center medium-only-text-center">
							<div class="match-detail-content">
								<div class="match-detail-bottom">
									<span>' . $nvb_location . ' ' . $nvb_maps . '</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				';

				$c++;
			}
		} else {
			$content .= '<div class="row matchrow" data-clickable-history="" data-load-more-item="">';
			$content .= __( 'There are no matches scheduled.', 'nevobo-api' );
			$content .= '</div>';
		}
		
		if( $data['ical'] === '1' ) {
			$icalfeed = str_replace( '.rss', '.ics', $data['feed'] );
			if( stristr( $icalfeed, 'poule' ) ) {
				$content .= '<div class="nevobotable"><span style="color:' . $data['icon_color'] . '"><i class="fas fa-calendar-alt fa-fw" aria-hidden="true"></i></span> <a href="' . $icalfeed . '">' . __( 'Add the full program of the poule to your calendar.', 'nevobo-api' ) . '</a></div>';
			} else {
				$content .= '<div class="nevobotable"><span style="color:' . $data['icon_color'] . '"><i class="fas fa-calendar-alt fa-fw" aria-hidden="true"></i></span> <a href="' . $icalfeed . '">' . __( 'Add the full program of the team to your calendar.', 'nevobo-api' ) . '</a></div>';
			}
		}

		return $content;
	}
}

?>