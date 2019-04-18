<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Seriously Simple Podcasting Series Home Widget
 *
 * @author 		Pedro Candeias
 * @package 	SeriouslySimplePodcasting
 * @category 	SeriouslySimplePodcasting/Shortcodes
 * @since 		1.15.0
 */
class SSP_Shortcode_SS_Podcast_Home_Episodes {

	/**
	 * Load ss_podcast shortcode
	 * @param  array  $params  Shortcode attributes
	 * @return string          HTML output
	 */
	function shortcode ( $params ) {

		$defaults = array(
			'echo'         => true,
			'title'        => '',
			'images' 			 => 'yes',
			'content'			 => 'episodes',
			'cols' 				 => '1/2',
			'limit' 			 => '4',
		);

		$args = shortcode_atts( $defaults, $params, 'ss_podcast_home_episodes' );

		// Make sure we return and don't echo.
		$args['echo'] = false;

		return ss_podcast_home_episodes( $args );
	}

}

add_action( 'get_podcast', 'ss_podcast_home_episodes' );

if ( ! function_exists( 'ss_podcast_home_episodes' ) ) {
	/**
	 * Display or return HTML-formatted podcast data.
	 *
	 * @param  mixed $args Arguments
	 *
	 * @since  1.0.0
	 * @return string
	 */
	function ss_podcast_home_episodes( $args = '' ) {
		global $post, $ss_podcasting;

		$defaults = array(
			'echo'         => true,
			'link_title'   => true,
			'title'        => '',
		  'limit' 			 => '4',
		);

		$podcasts_size_map = array(
			'1/1' => 'col-lg-12 col-xs-12',
			'1/2' => 'col-lg-6 col-xs-12',
			'2/2' => 'col-lg-12 col-xs-12',
			'1/3' => 'col-lg-4  col-xs-12',
			'2/3' => 'col-lg-8  col-xs-12',
			'3/3' => 'col-lg-12  col-xs-12',
			'1/4' => 'col-lg-3  col-xs-12',
			'2/4' => 'col-lg-6 col-xs-12',
			'3/4' => 'col-lg-9 col-xs-12',
			'4/4' => 'col-lg-12 col-xs-12',
			'1/5' => 'col-lg-5ths col-xs-12',
			'2/5' => 'col-lg-10ths col-xs-12',
			'3/5' => 'col-lg-15ths col-xs-12',
			'4/5' => 'col-lg-20ths col-xs-12',
			'5/5' => 'col-lg-12 col-xs-12',
			'1/6' => 'col-lg-2 col-xs-12',
			'2/6' => 'col-lg-4 col-xs-12',
			'3/6' => 'col-lg-6 col-xs-12',
			'4/6' => 'col-lg-8 col-xs-12',
			'5/6' => 'col-lg-10 col-xs-12',
			'6/6' => 'col-lg-12 col-xs-12'
		);
			$args['cols'] = preg_replace( '#[^0-9\/]+#', '',	$args['cols'] );
		if ( array_key_exists( 	$args['cols'], $podcasts_size_map ) ) {
			$podcasts_size_class = $podcasts_size_map[ $args['cols'] ];
		}

		$args = wp_parse_args( $args, $defaults );

		// Allow child themes/plugins to filter here
		$args = apply_filters( 'ssp_podcast_args', $args );
		$html = '';

		do_action( 'ssp_podcast_before', $args );

		// The Query
		$query = ss_get_podcast( $args );

		// The Display
		if ( ! is_wp_error( $query ) && is_array( $query ) &&  count( $query ) > 0 ) {
			$html .= '<div class="posts-box posts-box-8">' . "\n";
			$html .= '<div class="posts-wrapper row row-eq-height">' . "\n";
			// Begin templating logic.
			$tpl .= '<div class="col '.$podcasts_size_class.' mas-item">';
			$tpl .= '<article class="post-summary post-format-audio clearfix">';
			$tpl .='%%SERIESLINK%%';
			$tpl .= '<div class="post-image">%%LINKIMAGE%%</div><!-- .post-image -->';
			$tpl .= '<div class="post-details">';
			$tpl .='%%LINKTITLE%%';
			$tpl .='%%PLAYER%%';
			$tpl .='%%META%%';
			$tpl .= '</div><!-- .post-details -->';
			$tpl .= '</article>';
			$tpl .= '</div>';

			// Begin templating logic.
			$tpl = apply_filters( 'ssp_podcast_item_template', $tpl, $args );
			$i = 0;
			$limit = $args['limit'];
			foreach ( $query as $post ) {
				if ($i == $limit) { break; }
				  else {
					//var_dump($post);
					if ( ! is_object( $post ) ) {
						continue;
				}
				$template = $tpl;
				$i ++;
				setup_postdata( $post );
				$title = esc_attr(get_the_title());
				$link = get_the_permalink($post->ID);
				$linkimage = get_the_permalink($post->ID);
				$image = get_the_post_thumbnail( $post->ID, 'noticiasup-md', '' );
				$seriesarray = wp_get_post_terms( $post->ID, 'series');
				$seriesname = $seriesarray[0]->name;
				$seriesslug = $seriesarray[0]->slug;
				$serieslink = '<div class="series-title"><a href="'.site_url().'/series/'.$seriesslug.'" title="'.$seriesname.'">'.$seriesname.'</a></div>';
				$episodemeta = $ss_podcasting->episode_meta( $post->ID, 'widget' );
				$meta = '<div class="post-meta">'.$ss_podcasting->episode_meta( $post->ID, 'shortcode' ).'</div>';
				$linktitle = '<h2 class="post-title"><a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '">' . $title .'</a></h2>';
				$linkimage = '<a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '"><div class="overlay trs"><span class="fa fa-podcast"></span></div>'.$image.'</a>';
				$file = $ss_podcasting->get_enclosure( $post->ID );
				$player_style = (string) get_option( 'ss_podcasting_player_style', '' );
				$player = $ss_podcasting->load_media_player( $file, $post->ID, 'larger' );
				$template = str_replace( '%%PLAYER%%', $player, $template );
				$template = str_replace( '%%SERIESLINK%%', $serieslink, $template );
				$template = str_replace( '%%LINK%%', $link, $template );
				$template = str_replace( '%%LINKIMAGE%%', $linkimage, $template );
				$template = str_replace( '%%IMAGE%%', $image, $template );
				$template = str_replace( '%%LINKTITLE%%', $linktitle, $template );
				$template = str_replace( '%%META%%', $meta, $template );
				$html .= $template;
				}
			}

			//	$i = 0;
			//		foreach ( $query as $series ) {
			//			if ( ! is_object( $series ) ) {
			//				continue;
			//			}
			//			$template = $tpl;
			//			$i ++;
			//			$class = 'podcast';
			//		$title = $series->title;
			//		if ( true == $args['link_title'] ) {
			//			$title = '<a href="' . esc_url( $series->url ) . '" title="' . esc_attr( $title ) . '">' . $title . '</a>';
			//		}
			//		$template = str_replace( '%%CLASS%%', $class, $template );
			//		$template = str_replace( '%%TITLE%%', $title, $template );
			//		$meta     = $series->count . __( ' episodes', 'seriously-simple-podcasting' );
			//		$template = str_replace( '%%META%%', $meta, $template );
			//			$html .= $template;
			//		}

			$html .= '</div></div>' . "\n";
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

$GLOBALS['ssp_shortcodes']['ss_podcast_home_episodes'] = new SSP_Shortcode_SS_Podcast_Home_Episodes ();
