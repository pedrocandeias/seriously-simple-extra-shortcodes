<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Seriously Simple Podcasting Series subscribe Widget
 *
 * @author 		Pedro Candeias
 * @package 	SeriouslySimplePodcasting
 * @category 	SeriouslySimplePodcasting/Shortcodes
 * @since 		1.15.0
 */
class SSP_Shortcode_SS_Podcast_Subscribe {

	/**
	 * Load ss_podcast shortcode
	 * @param  array  $params  Shortcode attributes
	 * @return string          HTML output
	 */
	function shortcode ( $params ) {

		$defaults = array(
			'itunes'   => 'true',
			'google'   => 'true',
			'spotify'  => 'true'
		);

		$args = shortcode_atts( $defaults, $params, 'ss_podcast_subscribe' );

		// Make sure we return and don't echo.
		$args['echo'] = false;

		return ss_podcast_subscribe( $args );
	}

}

add_action( 'get_podcast', 'ss_podcast_subscribe' );

if ( ! function_exists( 'ss_podcast_subscribe' ) ) {
	/**
	 * Display or return HTML-formatted podcast data.
	 *
	 * @param  mixed $args Arguments
	 *
	 * @since  1.0.0
	 * @return string
	 */
	function ss_podcast_subscribe( $atts = '' ) {
		global $post, $ss_podcasting;

		$defaults = array(
			'itunes'   => 'true',
			'google'   => 'true',
			'spotify'  => 'true'
		);

		$seriesarray = wp_get_post_terms( $post->ID, 'series');
		$episode_id = intval( $wp_query->query_vars['podcast_episode'] );
		$series = get_the_terms( $episode_id, 'series' );
		$episode_series = !empty( $series ) && isset( $series[0] ) ? $series[0]->term_id : false;
		$seriesname = $seriesarray[0]->name;
		$seriesdesc = $seriesarray[0]->description;
		$seriesslug = $seriesarray[0]->slug;
		$series_id = $seriesarray[0]->term_id;
		$share_url_array = array();

		if( $itunes_share_url = get_option( 'ss_podcasting_itunes_url_' . $episode_series ) ){
			$share_url_array['iTunes'] = $itunes_share_url;
			//$meta_display .= $meta_sep . '<a href="' . esc_url( $itunes_share_url ) . '" title="' . __( 'View on iTunes', 'seriously-simple-podcasting' ) . '" class="podcast-meta-itunes">' . __( 'iTunes', 'seriously-simple-podcasting' ) . '</a>';
		}

		if( $google_play_share_url = get_option( 'ss_podcasting_google_play_url_' . $episode_series ) ){
			$share_url_array['Google Play'] = $google_play_share_url;
			//$meta_display .= $meta_sep . '<a href="' . esc_url( $google_play_share_url ) . '" title="' . __( 'View on Google Play', 'seriously-simple-podcasting' ) . '" class="podcast-meta-itunes">' . __( 'Google Play', 'seriously-simple-podcasting' ) . '</a>';
		}

		$itunes_url = get_option( 'ss_podcasting_itunes_url', '' );
		$google_play_url = get_option( 'ss_podcasting_google_play_url', '' );
		$spotify_url = get_option( 'ss_podcasting_spotify_url', '' );

		if ( is_array( $series) ) {
			if ( isset( $series[0] ) ) {
				if ( false !== get_option( 'ss_podcasting_itunes_url_' . $series_id ) ) {
					$itunes_url = get_option( 'ss_podcasting_itunes_url_' . $series_id, '' );
				}
				if ( false !== get_option( 'ss_podcasting_google_play_url_' . $series_id ) ) {
					$google_play_url = get_option( 'ss_podcasting_google_play_url_' . $series_id, '' );
				}
				if ( false !== get_option( 'ss_podcasting_spotify_url_' . $series_id ) ) {
					$spotify_url = get_option( 'ss_podcasting_spotify_url_' . $series_id, '' );
				}
			}
		}
		$subscribe_array = array(
			'itunes_url' => $itunes_url,
			'google_play_url' => $google_play_url,
			'spotify_url' => $spotify_url
		);
		$subscribe_urls = apply_filters( 'ssp_episode_subscribe_details', $subscribe_array, $episode_id, $context );
		foreach( $subscribe_urls as $key => $data ){
			if( !$data ){
				continue;
			}
			if( $subscribe_display ){
				$subscribe_display .= $meta_sep;
			}
			switch( $key ) {
				case 'itunes_url':
					$subscribe_display .= '<a href="' . esc_url( $data ) . '" target="_blank" title="' . apply_filters( 'ssp_subscribe_link_name_itunes', __( 'iTunes', 'seriously-simple-podcasting' ) ) . '" class="podcast-meta-subscribe podcast-meta-itunes"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 502.2" style="enable-background:new 0 0 512 502.2;" xml:space="preserve" class="podcast-icon">
	<g  class="podcast-icon-color">
	<path d="M330.9,433.3c0-6.7,0-13.4,3.4-16.8c0-10.1,6.7-13.4,13.4-16.8c53.8-26.9,90.8-74,104.2-134.5
		c13.4-60.5-3.4-114.3-40.3-158c-30.3-37-67.2-60.5-114.3-67.2c-60.5-10.1-111,3.4-154.7,40.3c-37,30.3-60.5,70.6-67.2,117.7
		c-10.1,57.2,6.7,111,43.7,154.7c16.8,20.2,37,33.6,60.5,47.1c6.7,3.4,13.4,6.7,10.1,13.4c0,3.4,0,10.1,3.4,13.4
		c3.4,6.7,0,6.7-3.4,6.7c-20.2-6.7-40.3-20.2-60.5-33.6c-40.3-33.6-67.2-74-77.3-124.4c-6.7-26.9-6.7-50.4-3.4-77.3
		c6.7-40.3,20.2-74,43.7-104.2c30.3-40.3,74-67.2,121-80.7c23.5-3.4,50.4-6.7,74-3.4c40.3,3.4,77.3,20.2,111,47.1
		c40.3,33.6,63.9,74,77.3,124.4c13.4,63.9,0,124.4-37,178.2c-26.9,37-60.5,60.5-100.9,77.3C334.2,433.3,334.2,433.3,330.9,433.3
		L330.9,433.3z"/>
	<path d="M209.6,332.4c0-6.7,0-13.4,0-16.8c0-10.1,6.7-20.2,16.8-23.5c10.1-6.7,20.2-10.1,33.6-10.1c16.8,0,30.3,3.4,43.7,13.4
		c6.7,3.4,13.4,13.4,13.4,23.5c0,23.5,0,47.1-3.4,74c-3.4,23.5-6.7,43.7-10.1,67.2c-3.4,13.4-6.7,26.9-23.5,30.3
		c-16.8,6.7-30.3,3.4-43.7-6.7c-6.7-10.1-10.1-16.8-10.1-26.9c-3.4-20.2-6.7-43.7-10.1-63.9C212.9,372.8,209.6,352.6,209.6,332.4
		L209.6,332.4z"/>
	<path d="M405.8,224.8c0,50.4-20.2,90.8-60.5,121c-3.4,0-3.4,3.4-6.7,3.4c0-3.4,0-3.4,0-6.7c0-16.8,3.4-30.3,16.8-43.7
		c20.2-20.2,26.9-50.4,23.5-77.3c-3.4-53.8-43.7-100.9-97.5-111c-63.9-13.4-121,30.3-134.5,90.8c-10.1,40.3,3.4,77.3,33.6,107.6
		c3.4,3.4,6.7,6.7,3.4,13.4c0,6.7,0,13.4,0,20.2c0,3.4,0,3.4,0,3.4h-3.4c-33.6-23.5-53.8-53.8-60.5-90.8
		c-6.7-40.3,0-80.7,26.9-114.3c23.5-33.6,57.2-53.8,97.5-57.2c40.3-6.7,77.3,3.4,111,30.3c30.3,23.5,47.1,53.8,53.8,94.1
		C405.8,211.4,405.8,221.5,405.8,224.8L405.8,224.8z"/>
	<path d="M263.4,157.6c13.4,0,26.9,3.4,37,13.4c10.1,10.1,13.4,23.5,13.4,37c0,30.3-23.5,50.4-50.4,50.4s-50.4-20.2-50.4-50.4
		c0-13.4,6.7-26.9,13.4-37C236.5,164.3,249.9,157.6,263.4,157.6L263.4,157.6z"/>
	<g>
		<path d="M330.9,433.3c0-6.7,0-13.4,3.4-16.8c0-10.1,6.7-13.4,13.4-16.8c53.8-26.9,90.8-74,104.2-134.5
			c13.4-60.5-3.4-114.3-40.3-158c-30.3-37-67.2-60.5-114.3-67.2c-60.5-10.1-111,3.4-154.7,40.3c-37,30.3-60.5,70.6-67.2,117.7
			c-10.1,57.2,6.7,111,43.7,154.7c16.8,20.2,37,33.6,60.5,47.1c6.7,3.4,13.4,6.7,10.1,13.4c0,3.4,0,10.1,3.4,13.4
			c3.4,6.7,0,6.7-3.4,6.7c-20.2-6.7-40.3-20.2-60.5-33.6c-40.3-33.6-67.2-74-77.3-124.4c-6.7-26.9-6.7-50.4-3.4-77.3
			c6.7-40.3,20.2-74,43.7-104.2c30.3-40.3,74-67.2,121-80.7c23.5-3.4,50.4-6.7,74-3.4c40.3,3.4,77.3,20.2,111,47.1
			c40.3,33.6,63.9,74,77.3,124.4c13.4,63.9,0,124.4-37,178.2c-26.9,37-60.5,60.5-100.9,77.3C334.2,433.3,334.2,433.3,330.9,433.3
			L330.9,433.3z"/>
		<path d="M209.8,332.4c0-6.7,0-13.4,0-16.8c0-10.1,6.7-20.2,16.8-23.5c10.1-6.7,20.2-10.1,33.6-10.1c16.8,0,30.3,3.4,43.7,13.4
			c6.7,3.4,13.4,13.4,13.4,23.5c0,23.5,0,47.1-3.4,74c-3.4,23.5-6.7,43.7-10.1,67.2c-3.4,13.4-6.7,26.9-23.5,30.3
			c-16.8,6.7-30.3,3.4-43.7-6.7c-6.7-10.1-10.1-16.8-10.1-26.9c-3.4-20.2-6.7-43.7-10.1-63.9C213.2,372.8,209.8,352.6,209.8,332.4
			L209.8,332.4z"/>
		<path d="M408.2,224.8c0,50.4-20.2,90.8-60.5,121c-3.4,0-3.4,3.4-6.7,3.4c0-3.4,0-3.4,0-6.7c0-16.8,3.4-30.3,16.8-43.7
			c20.2-20.2,26.9-50.4,23.5-77.3c-3.4-53.8-43.7-100.9-97.5-111c-63.9-13.4-121,30.3-134.5,90.8c-10.1,40.3,3.4,77.3,33.6,107.6
			c3.4,3.4,6.7,6.7,3.4,13.4c0,6.7,0,13.4,0,20.2c0,3.4,0,3.4,0,3.4h-3.4c-33.6-23.5-53.8-53.8-60.5-90.8
			c-6.7-40.3,0-80.7,26.9-114.3c23.5-33.6,57.2-53.8,97.5-57.2c40.3-6.7,77.3,3.4,111,30.3c30.3,23.5,47.1,53.8,53.8,94.1
			C408.2,211.4,408.2,221.5,408.2,224.8L408.2,224.8z"/>
		<path d="M263.6,157.6c13.4,0,26.9,3.4,37,13.4c10.1,10.1,13.4,23.5,13.4,37c0,30.3-23.5,50.4-50.4,50.4s-50.4-20.2-50.4-50.4
			c0-13.4,6.7-26.9,13.4-37C236.7,164.3,250.2,157.6,263.6,157.6L263.6,157.6z"/>
	</g>
	</g>
	</svg><span>' . apply_filters( 'ssp_subscribe_link_name_itunes', __( 'iTunes', 'seriously-simple-podcasting' ) ) . '</span></a>';
				break;

				case 'google_play_url':
					$subscribe_display .= '<a href="' . esc_url( $data ) . '" target="_blank" title="' . apply_filters( 'ssp_subscribe_link_name_google_play', __( 'Google Play', 'seriously-simple-podcasting' ) ) . '" class="podcast-meta-subscribe podcast-meta-google"><svg version="1.1" id="Camada_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" class="podcast-icon">
			 <g class="podcast-icon-color">
		<path d="M226.1,168.1v179.5h59.8V168.1H226.1z"/>
		<ellipse transform="matrix(3.327798e-02 -0.9994 0.9994 3.327798e-02 -197.6316 274.0204)" cx="42.8" cy="239.2" rx="29.9" ry="29.9"/>
		<circle cx="42.8" cy="272.8" r="29.9"/>
		<path d="M13.2,239.2H73v33.7H13.2V239.2z"/>
		<circle cx="469.2" cy="272.8" r="29.9"/>
		<circle cx="469.2" cy="239.2" r="29.9"/>
		<path d="M499.1,272.8h-59.8v-33.7h59.8V272.8z"/>
		<circle cx="147.5" cy="340.1" r="29.9"/>
		<circle cx="147.5" cy="373.8" r="29.9"/>
		<path d="M117.6,340.1h59.8v33.7h-59.8V340.1z"/>
		<circle cx="147.5" cy="138.2" r="29.9"/>
		<circle cx="147.5" cy="246.7" r="29.9"/>
		<path d="M117.6,138.2h59.8v112.2h-59.8V138.2z"/>
		<circle cx="364.5" cy="171.9" r="29.9"/>
		<circle cx="364.5" cy="138.2" r="29.9"/>
		<path d="M394.4,171.9h-59.8v-33.7h59.8C394.4,138.2,394.4,171.9,394.4,171.9z"/>
		<circle cx="256" cy="74.6" r="29.9"/>
		<circle cx="256" cy="41" r="29.9"/>
		<path d="M285.9,74.6h-59.8V41h59.8V74.6z"/>
		<circle cx="256" cy="471" r="29.9"/>
		<circle cx="256" cy="437.4" r="29.9"/>
		<path d="M285.9,471h-59.8v-33.7h59.8V471z"/>
		<circle cx="364.5" cy="373.8" r="29.9"/>
		<circle cx="364.5" cy="265.3" r="29.9"/>
		<path d="M394.4,373.8h-59.8V265.3h59.8C394.4,265.3,394.4,373.8,394.4,373.8z"/>
		<circle cx="256" cy="168.1" r="29.9"/>
		<circle cx="256" cy="343.9" r="29.9"/>
	</g>
	</svg><span>' . apply_filters( 'ssp_subscribe_link_name_google_play', __( 'Google Play', 'seriously-simple-podcasting' ) ) . '</span></a>';
				break;

				case 'spotify_url':
					$subscribe_display .= '<a href="' . esc_url( $data ) . '" target="_blank" title="' . apply_filters( 'ssp_subscribe_link_name_spotify', __( 'Spotify', 'seriously-simple-podcasting' ) ) . '" class="podcast-meta-subscribe podcast-meta-spotify"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 502.2" style="enable-background:new 0 0 512 502.2;" xml:space="preserve" class="podcast-icon">
	<g class="podcast-icon-color">
	<path d="M489.4,120.1c-92.3-49.7-193.3-76.8-298.8-76.8c-52.7,0-109.8,9-162.6,22.6C10.5,70.4,1.7,93,6.1,111
		c4.4,18.1,22,31.6,39.5,27.1c48.3-13.6,96.7-22.6,145-22.6c96.7,0,184.5,22.6,268,67.8c4.4,4.5,8.8,4.5,13.2,4.5
		c13.2,0,22-9,30.8-22.6C511.4,147.2,507,129.1,489.4,120.1L489.4,120.1z M190.7,336.9c-43.9,0-87.9,9-127.4,22.6
		c-13.2,4.5-22,31.6-17.6,45.2c4.4,13.6,17.6,22.6,30.8,18.1l0,0c35.1-13.6,74.7-18.1,114.2-18.1c65.9,0,131.8,18.1,188.9,49.7
		l13.2,4.5c8.8,0,17.6-4.5,22-13.6c4.4-13.6,4.4-40.7-8.8-49.7C335.6,359.5,265.3,336.9,190.7,336.9L190.7,336.9z M445.5,260.1
		c-79.1-40.7-167-63.3-254.8-63.3c-48.3,0-96.7,4.5-145,22.6c-13.2,0-22,27.1-17.6,45.2c4.4,18.1,17.6,27.1,35.1,22.6
		c43.9-13.6,87.9-18.1,131.8-18.1c79.1,0,158.2,18.1,228.5,58.7c4.4,4.5,8.8,4.5,13.2,4.5c8.8,0,22-18.1,26.4-27.1
		C463.1,287.2,458.7,269.2,445.5,260.1z"/>
	</g>
	</svg><span>'. apply_filters( 'ssp_subscribe_link_name_spotify', __( 'Spotify', 'seriously-simple-podcasting' ) ) . '</span></a>';
				break;
				default:
					$allowed_tags = array(
						'strong' => array(),
						'b' => array(),
						'em' => array(),
						'i' => array(),
						'a' => array(
							'href' => array(),
							'title' => array(),
							'target' => array(),
						),
					);
					$subscribe_display .= wp_kses( $data, $allowed_tags );
				break;
			}
		}


		$html = '';
		$query = ss_get_podcast( $args );
		// The Display
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {
			$html .= $args['before'] . "\n";
			$html .= '<div class="series-subscribe">' . "\n";
			$html .= '<h2 class="series-subscribe-title">Subscrever:</h2>'.$subscribe_display;
			$html .= '</div><!--/series-subscribe -->' . "\n";
			$html .= $args['after'] . "\n";
			wp_reset_postdata();
		}

		// Allow themes/plugins to filter here
		$html = apply_filters( 'ssp_podcast_html', $html, $query, $args );

		if ( ! $args['echo'] ) {
			return $html;
		}

		// Should only run if "echo" is set to true
		echo $html;

		do_action( 'ssp_podcast_after', $args );
	}
}

$GLOBALS['ssp_shortcodes']['ss_podcast_subscribe'] = new SSP_Shortcode_SS_Podcast_Subscribe ();
