<?php

class WP_Scanner_API {

	/**
	 * @var WP_Scanner
	 */
	protected $wp_scanner;

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * WP_Scanner_API constructor.
	 */
	public function __construct() {
		$this->wp_scanner = wp_scanner();
		$this->url        = 'https://app.wpscanner.io';

		if ( defined( 'WP_SCANNER_API_BASE' ) ) {
			$this->url = WP_SCANNER_API_BASE;
		}

		add_action( 'wp_ajax_wp_scanner_refresh_status', array( $this, 'refresh_status' ) );
	}

	/**
	 * Post
	 *
	 * @param string      $endpoint
	 * @param array       $data
	 * @param null|string $secret
	 *
	 * @return array|WP_Error
	 */
	public function post( $endpoint, $data, $secret = null ) {
		$data['timestamp'] = time();

		$url  = trailingslashit( $this->url ) . ltrim( $endpoint, '/' );
		$body = array(
			'hash' => $this->hash( $data, $secret ),
			'data' => $data,
		);

		return wp_remote_post( $url, array( 'body' => $body ) );
	}

	/**
	 * Hash
	 *
	 * @param array $data
	 * @param null  $secret
	 *
	 * @return bool|string
	 */
	protected function hash( $data = null, $secret = null ) {
		if ( is_null( $data ) ) {
			$data              = isset( $_POST['data'] ) ? $_POST['data'] : array();
			$data['timestamp'] = isset( $_POST['data']['timestamp'] ) ? (int) $_POST['data']['timestamp'] : 0;
		}

		if ( is_null( $secret ) ) {
			$secret = $this->wp_scanner->settings->get( 'secret' );
		}

		return hash_hmac( 'sha256', json_encode( $data ), $secret );
	}

	/**
	 * Activate
	 *
	 * @param string $key
	 * @param string $secret
	 *
	 * @return bool|WP_Error
	 */
	public function activate( $key, $secret ) {
		$endpoint = 'api/v1/site/activate/' . $key;
		$data     = array(
			'site_url'  => home_url(),
			'admin_url' => admin_url(),
		);

		$response = $this->post( $endpoint, $data, $secret );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = json_decode( $response['body'], true );

		if ( ! isset( $response['response']['code'] ) || 200 !== $response['response']['code'] ) {
			$code    = 1;
			$message = __( 'Unable to activate site.', 'wp-scanner' );

			if ( isset( $body['error']['code'] ) ) {
				$code = $body['error']['code'];
			}

			if ( isset( $body['error']['message'] ) ) {
				$message = $body['error']['message'];
			}

			return new WP_Error( $code, $message );
		}

		return true;
	}

	/**
	 * Refresh status
	 */
	public function refresh_status() {
		check_admin_referer( 'wp_scanner_refresh', 'nonce' );

		$key      = $this->wp_scanner->settings->get( 'key' );
		$secret   = $this->wp_scanner->settings->get( 'secret' );
		$activate = $this->activate( $key, $secret );

		if ( is_wp_error( $activate ) ) {
			$this->wp_scanner->settings->delete( 'active' );

			wp_send_json_error();
		}

		$this->wp_scanner->settings->add( 'active', true );

		wp_send_json_success();
	}

	/**
	 * Verify
	 */
	public function verify() {
		$this->verify_key();
		$this->verify_timestamp();
		$this->verify_secret();
	}

	/**
	 * Verify key
	 */
	protected function verify_key() {
		$key = isset( $_POST['data']['key'] ) ? $_POST['data']['key'] : 0;

		if ( $key !== $this->wp_scanner->settings->get( 'key' ) ) {
			$this->error( 201, __( 'Bad authentication data', 'wp-scanner' ) );
		}
	}

	/**
	 * Verify timestamp
	 */
	protected function verify_timestamp() {
		$timestamp = isset( $_POST['data']['timestamp'] ) ? (int) $_POST['data']['timestamp'] : 0;

		if ( $timestamp > time() + 180 || $timestamp < time() - 180) {
			$this->error( 202, __( 'Time sync error', 'wp-scanner' ) );
		}
	}

	/**
	 * Verify secret
	 */
	protected function verify_secret() {
		$hash      = isset( $_POST['hash'] ) ? $_POST['hash'] : '';
		$real_hash = $this->hash();

		if ( $hash !== $real_hash ) {
			$this->error( 203, __( 'Bad authentication data', 'wp-scanner' ) );
		}
	}

	/**
	 * Error
	 *
	 * @param int    $code
	 * @param string $message
	 */
	protected function error( $code, $message ) {
		$args = array(
			'code'    => $code,
			'message' => $message,
		);

		wp_send_json_error( $args );
	}

}