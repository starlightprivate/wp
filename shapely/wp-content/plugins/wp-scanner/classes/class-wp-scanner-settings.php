<?php

class WP_Scanner_Settings {

	/**
	 * @var WP_Scanner
	 */
	protected $wp_scanner;

	/**
	 * @var string
	 */
	public $group;

	/**
	 * @var string
	 */
	public $section;

	/**
	 * @var string
	 */
	public $option_name;

	/**
	 * @var mixed|void
	 */
	public $settings;

	/**
	 * WP_Scanner_Settings constructor.
	 */
	public function __construct() {
		$this->wp_scanner  = wp_scanner();
		$this->group       = 'wp_scanner';
		$this->section     = 'wp_scanner_api';
		$this->option_name = 'wp_scanner_settings';
		$this->settings    = get_option( $this->option_name );

		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register settings page
	 */
	public function register_settings_page() {
		$hook_suffix = add_submenu_page( 'options-general.php', 'WP Scanner', 'WP Scanner', 'manage_options', 'wp-scanner', array(
			$this,
			'render_settings_page',
		) );

		if ( false !== $hook_suffix ) {
			add_action( 'load-' . $hook_suffix, array( $this, 'assets' ) );
		}
	}

	/**
	 * Assets
	 */
	public function assets() {
		$version = $this->wp_scanner->version;
		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// JS
		$src = plugins_url() . '/wp-scanner/assets/js/script' . $suffix . '.js';
		wp_enqueue_script( 'wp-scanner-script', $src, array( 'jquery' ), $version, true );

		wp_localize_script( 'wp-scanner-script', 'wpScanner', array(
			'strings' => array(
				'connected'    => __( 'Connected', 'wp-scanner' ),
				'disconnected' => __( 'Disconnected', 'wp-scanner' ),
			),
			'nonces'  => array(
				'refresh' => wp_create_nonce( 'wp_scanner_refresh' ),
			),
		) );
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		$this->wp_scanner->render_view( 'settings-page' );
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting( $this->group, $this->option_name, array( $this, 'settings_callback' ) );

		add_settings_section( $this->section, __( 'API', 'wp-scanner' ), array( $this, 'render_api_section' ), $this->group );

		add_settings_field( 'key', __( 'Public Key', 'wp-scanner' ), array( $this, 'render_key_field' ), $this->group, $this->section );
		add_settings_field( 'secret', __( 'Secret Key', 'wp-scanner' ), array( $this, 'render_secret_field' ), $this->group, $this->section );
		add_settings_field( 'status', __( 'Status', 'wp-scanner' ), array( $this, 'render_status_field' ), $this->group, $this->section );
	}

	/**
	 * Settings callback
	 *
	 * @param array $input
	 *
	 * @return array
	 */
	public function settings_callback( $input ) {
		$input = array_map( 'trim', $input );
		$input = array_filter( $input );

		if ( isset( $input['delete'] ) && '1' === $input['delete'] ) {
			$input = array();
		}

		if ( isset( $input['key'] ) && isset( $input['secret'] ) ) {
			$activate = $this->wp_scanner->api->activate( $input['key'], $input['secret'] );

			if ( is_wp_error( $activate ) ) {
				$message = $activate->get_error_message() . ' (#' . $activate->get_error_code() . ')';

				add_settings_error( 'secret', $activate->get_error_code(), $message );
			} else {
				$input['active'] = true;
			}
		}

		return $input;
	}

	/**
	 * Get field name
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function get_field_name( $key ) {
		return $this->option_name . '[' . $key . ']';
	}

	/**
	 * Render api section
	 */
	public function render_api_section() {
		//
	}

	/**
	 * Render key field
	 */
	public function render_key_field() {
		$this->wp_scanner->render_view( 'key-field' );
	}

	/**
	 * Render secret field
	 */
	public function render_secret_field() {
		$this->wp_scanner->render_view( 'secret-field' );
	}

	/**
	 * Render status field
	 */
	public function render_status_field() {
		$this->wp_scanner->render_view( 'status-field' );
	}

	/**
	 * Get setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function get( $key, $default = '' ) {
		$this->settings = apply_filters( 'wp_scanner_get_setting', $this->settings, $key );

		if ( isset( $this->settings[ $key ] ) ) {
			return $this->settings[ $key ];
		}

		return $default;
	}

	/**
	 * Get masked secret
	 *
	 * @return string
	 */
	public function get_masked_secret() {
		$secret = $this->get( 'secret' );

		if ( ! $secret ) {
			return $secret;
		}

		return str_repeat( '*', strlen( $secret ) );
	}

	/**
	 * Is secret set
	 *
	 * @return bool
	 */
	public function is_secret_set() {
		if ( ! $this->get( 'secret' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Add
	 *
	 * @param string $key
	 * @param mixed  $data
	 */
	public function add( $key, $data ) {
		$this->settings[ $key ] = $data;

		update_option( $this->option_name, $this->settings );
	}

	/**
	 * Delete
	 *
	 * @param string $key
	 */
	public function delete( $key ) {
		unset( $this->settings[ $key ] );

		update_option( $this->option_name, $this->settings );
	}
}