<?php

class WP_Scanner_Scan {

	/**
	 * @var WP_Scanner
	 */
	protected $wp_scanner;

	/**
	 * WP_Scanner_Scan constructor.
	 */
	public function __construct() {
		$this->wp_scanner = wp_scanner();

		add_action( 'wp_ajax_nopriv_wpscanner_scan', array( $this, 'scan_response' ) );
	}

	/**
	 * Scan results
	 */
	public function scan_response() {
		$this->wp_scanner->api->verify();

		wp_send_json_success( $this->get_results() );
	}

	/**
	 * Get results
	 *
	 * @return array
	 */
	public function get_results() {
		$results = array(
			'database'   => array(
				'prefix'  => $this->database_prefix(),
				'version' => $this->database_version(),
			),
			'filesystem' => array(
				'permissions' => $this->permissions(),
			),
			'object'     => array(
				'enabled' => $this->object_cache(),
			),
			'php'        => array(
				'software' => $this->php_software(),
				'version'  => $this->php_version(),
			),
			'server'     => array(
				'software' => $this->server_software(),
			),
			'users'      => array(
				'admin' => $this->admin_account(),
			),
			'wordpress'  => array(
				'checksums' => $this->checksums(),
				'debug'     => $this->debug(),
				'file_edit' => $this->file_edit(),
				'updates'   => $this->updates(),
				'version'   => $this->wordpress_version(),
			),
		);

		return $results;
	}

	/**
	 * Database prefix
	 *
	 * @return bool
	 */
	protected function database_prefix() {
		global $wpdb;

		if ( 'wp_' === $wpdb->base_prefix ) {
			return true;
		}

		return false;
	}

	/**
	 * Database version
	 *
	 * @return string
	 */
	protected function database_version() {
		global $wpdb;

		return $wpdb->db_version();
	}

	/**
	 * Permissions
	 */
	protected function permissions() {
		$base    = trailingslashit( ABSPATH );
		$uploads = wp_upload_dir();

		$paths = array(
			'/'                    => $base,
			'/wp-admin/'           => $base . 'wp-admin',
			'/wp-admin/js/'        => $base . 'wp-admin/js',
			'/wp-includes/'        => $base . 'wp-includes',
			'/wp-content/'         => defined( WP_CONTENT_DIR ) ? WP_CONTENT_DIR : $base . 'wp-content',
			'/wp-content/plugins/' => defined( WP_PLUGIN_DIR ) ? WP_PLUGIN_DIR : $base . 'wp-content/plugins',
			'/wp-content/themes/'  => get_theme_root(),
			'/wp-content/uploads/' => $uploads['basedir'],
			'.htaccess'            => $base . '.htaccess',
			'wp-config.php'        => file_exists( $base . 'wp-config.php' ) ? $base . 'wp-config.php' : trailingslashit( dirname( $base ) ) . 'wp-config.php',
		);

		$results = array();

		foreach ( $paths as $key => $path ) {
			$results[ $key ] = substr( sprintf( '%o', fileperms( $path ) ), -4 );
		}

		return $results;
	}

	/**
	 * Object cache
	 *
	 * @return bool
	 */
	protected function object_cache() {
		global $_wp_using_ext_object_cache;

		if ( empty( $_wp_using_ext_object_cache ) ) {
			return false;
		}

		return true;
	}

	/**
	 * PHP software
	 *
	 * @return string
	 */
	protected function php_software() {
		if ( defined( 'HHVM_VERSION' ) ) {
			return 'HHVM';
		}

		return 'PHP';
	}

	/**
	 * PHP version
	 *
	 * @return string
	 */
	protected function php_version() {
		return phpversion();
	}

	/**
	 * Server software
	 *
	 * @return string
	 */
	protected function server_software() {
		return $_SERVER['SERVER_SOFTWARE'];
	}

	/**
	 * Admin account
	 *
	 * @return bool
	 */
	protected function admin_account() {
		global $wpdb;

		$sql = "SELECT COUNT(*)
		        FROM {$wpdb->users}
		        WHERE user_login = %s";

		return (bool) $wpdb->get_var( $wpdb->prepare( $sql, 'admin' ) );
	}

	/**
	 * Checksums
	 *
	 * @return bool|array
	 */
	protected function checksums() {
		global $wp_version, $wp_local_package;

		$args = array(
			'version' => $wp_version,
			'locale'  => ! empty( $wp_local_package ) ? $wp_local_package : 'en_US',
		);

		$response = wp_remote_get( 'https://api.wordpress.org/core/checksums/1.0/?' . http_build_query( $args ) );

		if ( is_wp_error( $response ) || ! isset( $response['body'] ) ) {
			return false;
		}

		$body = json_decode( $response['body'], true );

		if ( ! isset( $body['checksums'] ) || false === $body['checksums'] ) {
			return false;
		}

		$results = array();

		foreach ( $body['checksums'] as $file => $checksum ) {
			$path = ABSPATH . $file;

			if ( 'wp-content' === substr( $file, 0, 10 ) ) {
				// Skip theme/plugin files
				continue;
			}

			if ( ! file_exists( $path ) ) {
				$results['missing'][] = $file;

				continue;
			}

			$md5 = md5_file( $path );

			if ( $md5 !== $checksum ) {
				$results['modified'][] = $file;
			}
		}

		return $results;
	}

	/**
	 * Debug
	 *
	 * @return bool
	 */
	protected function debug() {
		if ( ! defined( 'WP_DEBUG' ) || false === WP_DEBUG ) {
			return false;
		}

		if ( ! defined( 'WP_DEBUG_DISPLAY' ) || false === WP_DEBUG_DISPLAY ) {
			return false;
		}

		return true;
	}

	/**
	 * File edit
	 *
	 * @return bool
	 */
	protected function file_edit() {
		if ( defined( 'DISALLOW_FILE_EDIT' ) && true === DISALLOW_FILE_EDIT ) {
			return true;
		}

		return false;
	}

	/**
	 * Updates
	 *
	 * @return int
	 */
	protected function updates() {
		if ( ! function_exists( 'get_plugin_updates' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			require_once ABSPATH . 'wp-admin/includes/update.php';
		}

		return count( get_plugin_updates() );
	}

	/**
	 * WordPress version
	 *
	 * @return string
	 */
	protected function wordpress_version() {
		global $wp_version;

		return $wp_version;
	}

}