<?php

class WP_Scanner {

	/**
	 * @var WP_Scanner
	 */
	protected static $instance;

	/**
	 * @var WP_Scanner_API
	 */
	public $api;

	/**
	 * @var WP_Scanner_Notice
	 */
	public $notice;

	/**
	 * @var WP_Scanner_Scan
	 */
	public $scan;

	/**
	 * @var WP_Scanner_Settings
	 */
	public $settings;

	/**
	 * @var string
	 */
	public $file_path;

	/**
	 * @var string
	 */
	public $dir_path;

	/**
	 * @var string
	 */
	public $basename;

	/**
	 * @var string
	 */
	public $version;

	/**
	 * Make this class a singleton
	 *
	 * Use this instead of __construct()
	 *
	 * @param string $plugin_file_path
	 * @param string $version
	 *
	 * @return WP_Scanner
	 */
	public static function get_instance( $plugin_file_path, $version ) {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WP_Scanner ) ) {
			self::$instance = new WP_Scanner();
			// Initialize the class
			self::$instance->init( $plugin_file_path, $version );
		}

		return self::$instance;
	}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * class via the `new` operator from outside of this class.
	 */
	protected function __construct() {
		// Singleton
	}

	/**
	 * As this class is a singleton it should not be clone-able
	 */
	protected function __clone() {
		// Singleton
	}

	/**
	 * As this class is a singleton it should not be able to be unserialized
	 */
	protected function __wakeup() {
		// Singleton
	}

	/**
	 * Initialize the class.
	 *
	 * @param string $plugin_file_path
	 * @param string $version
	 */
	protected function init( $plugin_file_path, $version ) {
		$this->file_path = $plugin_file_path;
		$this->dir_path  = rtrim( plugin_dir_path( $plugin_file_path ), '/' );
		$this->basename  = plugin_basename( $plugin_file_path );
		$this->version   = $version;

		$this->includes();
		$this->loader();
	}

	/**
	 * Includes
	 */
	protected function includes() {
		require_once dirname( $this->file_path ) . '/classes/class-wp-scanner-api.php';
		require_once dirname( $this->file_path ) . '/classes/class-wp-scanner-notice.php';
		require_once dirname( $this->file_path ) . '/classes/class-wp-scanner-scan.php';
		require_once dirname( $this->file_path ) . '/classes/class-wp-scanner-settings.php';
	}

	/**
	 * Loader
	 */
	protected function loader() {
		$this->api      = new WP_Scanner_API();
		$this->notice   = new WP_Scanner_Notice();
		$this->scan     = new WP_Scanner_Scan();
		$this->settings = new WP_Scanner_Settings();
	}

	/**
	 * Render view
	 *
	 * @param string $view
	 * @param array  $args
	 */
	public function render_view( $view, $args = array() ) {
		extract( $args );
		include $this->dir_path . '/views/' . $view . '.php';
	}

}