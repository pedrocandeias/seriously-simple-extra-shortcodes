<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSP_Extra_Shortcodes {

	/**
	 * The single instance of SSP_Extra_Shortcodes.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;


	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct ( $file = '', $version = '1.0.0', $db_version = '1.0.0' ) {
		global $wpdb;
		// Load plugin constants
		$this->_version = $version;
		$this->_db_version = $db_version;
		$this->_token = 'ssp_extra_shortcodes';
		$this->_table = $wpdb->prefix . $this->_token;
		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );
		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		add_action( 'init', array( $this, 'register_shortcodes' ), 1 );
		// Register widgets
		add_action( 'widgets_init', array( $this, 'register_widgets' ), 1 );

	} // End __construct ()



	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'seriously-simple-extra-shortcodes', false, basename( $this->dir ) . '/languages/' );
	} // End load_localisation ()

	/**
	 * Register plugin widgets
	 * @return void
	 */
	public function register_widgets () {

		$widgets = array(
			'seriesextras' => 'SeriesExtras' /* ,
			'seriesplaylist' => 'SeriesPlaylist' */
 		);
		foreach ( $widgets as $id => $name ) {
			require_once( $this->dir . '/includes/widgets/class-ssp-widget-' . $id . '.php' );
			register_widget( 'SSP_Widget_' . $name );
		}

	}

	/**
	 * Main SSP_Extra_Shortcodes Instance
	 *
	 * Ensures only one instance of SSP_Extra_Shortcodes is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see SSP_Extra_Shortcodes()
	 * @return SSP_Extra_Shortcodes SSP_Extra_Shortcodes instance
	 */

	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version);
		}
		return self::$_instance;

	} // End instance ()

	/**
	 * Register plugin shortcodes
	 * @return void
	 */
	public function register_shortcodes () {

		$shortcodes = array(
			'ss_podcast_home',
			'ss_podcast_series',
			'ss_podcast_subscribe',
			'ss_podcast_home_episodes'
		);

		foreach ( $shortcodes as $shortcode ) {
			require_once( $this->dir . '/includes/shortcodes/class-ssp-shortcode-' . $shortcode . '.php' );
			add_shortcode( $shortcode, array( $GLOBALS['ssp_shortcodes'][ $shortcode ], 'shortcode' ) );
		}

	}

}
