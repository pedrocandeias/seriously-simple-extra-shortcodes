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
class SSP_Shortcode_SS_Podcast_Home {

	/**
	 * Load ss_podcast shortcode
	 * @param  array  $params  Shortcode attributes
	 * @return string          HTML output
	 */
	function shortcode ( $params ) {

		$defaults = array(
			'echo'         => true,
			'link_title'   => true,
			'title'        => '',
			'content'      => 'series',
			'images' 			 => 'yes',
			'cols' 				 => '1/2',
			'series'       => '',
			'before'       => '',
			'after'        => '',
			'before_title' => '',
			'after_title'  => ''
		);

		$args = shortcode_atts( $defaults, $params, 'ss_podcast_home' );

		// Make sure we return and don't echo.
		$args['echo'] = false;

		return ss_podcast_home( $args );
	}

}

add_action( 'get_podcast', 'ss_podcast_home' );

if ( ! function_exists( 'ss_podcast_home' ) ) {
	/**
	 * Display or return HTML-formatted podcast data.
	 *
	 * @param  mixed $args Arguments
	 *
	 * @since  1.0.0
	 * @return string
	 */
	function ss_podcast_home( $atts = '' ) {
		global $post, $ss_podcasting;

		$defaults = array(
			'echo'         => true,
			'link_title'   => true,
			'title'        => '',
			'content'      => 'series',
			'images' 			 => 'yes',
			'cols' 				 => '1/2',
			'series'       => '',
			'before'       => '',
			'after'        => '',
			'before_title' => '',
			'after_title'  => ''
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
		$atts['cols'] = preg_replace( '#[^0-9\/]+#','', $atts['cols'] );
		if ( array_key_exists( $atts['cols'], $podcasts_size_map ) ) {
			$podcasts_size_class = $podcasts_size_map[ $atts['cols'] ];
		}
		$atts = wp_parse_args( $atts, $defaults );
		$atts = apply_filters( 'ssp_podcast_args', $atts );
		$html = '';
		do_action( 'ssp_podcast_before', $atts );
		// Query
		$query = ss_get_podcast( $args );
		// The Display
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {
			$html .= $args['before'] . "\n";
			$html .= '<div class="posts-wrapper row row-eq-height">' . "\n";
			// Begin templating logic.
			$tpl .= '<div class="col '. $podcasts_size_class.' mas-item">';
			$tpl .= '<article class="post-summary post-format-video clearfix">';
			$tpl .= '<div class="post-image">%%LINKIMAGE%%</div><!-- .post-image -->';
			$tpl .= '<div class="post-details">';
			$tpl .='%%TITLE%%';
			$tpl .='%%META%%';
			$tpl .= '</div><!-- .post-details --></article>';
			$tpl .= '</div>';
			$tpl = apply_filters( 'ssp_podcast_item_template', $tpl, $args );
				$i = 0;
				$args = array(
						 'type'                     => 'podcast',
						 'child_of'                 => 0,
						 'parent'                   => '',
						 'orderby'                  => 'name',
						 'order'                    => 'ASC',
						 'hide_empty'               => 1,
						 'hierarchical'             => 1,
						 'taxonomy'                 => 'series',
						 'pad_counts'               => false );
					 		$podcastsSeries = get_categories($args);
							$query_args = ssp_episodes( 999, $series->slug, true, 'widget' );
							$qry = new WP_Query( apply_filters( 'ssp_widget_series_episodes_args', $query_args ) );
							//var_dump($podcastsSeries);
						 foreach ($podcastsSeries as $series) {



								$seriesID =  $series->term_id;
								$seriesTermsMeta = get_term_meta( $seriesID, $key = 'podcast_series_image_settings', $single = false );
								$imageAttributes = wp_get_attachment_image_src( $attachment_id = $seriesTermsMeta[0]);
								$image = wp_get_attachment_image($attachment_id = $seriesTermsMeta[0],'noticiasup-md','','');
								$template = $tpl;
								$i ++;
								$class = 'podcast';
								$title = $series->name;
								$url =  get_site_url().'/series/'.$series->slug;
								$meta = $series->count;
								$description = $series->description;
								$linkTitle = '<h2 class="post-title"><a href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '">' . $title .'</a></h2>';
								$linkImage = '<a href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '"><div class="overlay trs"><span class="fa fa-podcast"></span></div>'.$image.'</a>';
								$podcastMeta = '<ul class="post-meta clearfix"><li class="post-comments"><span class="fa fa-audio"></span>'.$meta.' episódios</li></ul>';
								$pocastDescription =	'<p class="post-excerpt">'.$description.'</p>';
								$template = str_replace( '%%TITLE%%', $linkTitle, $template );
								$template = str_replace('%%LINKIMAGE%%', $linkImage, $template);
								$template = str_replace('%%DESCRIPTION%%', $pocastDescription, $template);
								$template = str_replace( '%%META%%', $podcastMeta, $template );
								$html .= $template;
						 }
			$html .= '</div><!--/.ss_podcast-->' . "\n";
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

$GLOBALS['ssp_shortcodes']['ss_podcast_home'] = new SSP_Shortcode_SS_Podcast_Home ();
