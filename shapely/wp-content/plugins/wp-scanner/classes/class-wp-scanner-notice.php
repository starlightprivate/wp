<?php

class WP_Scanner_Notice {

    /**
     * @var WP_Scanner
     */
    protected $wp_scanner;

    /**
     * WP_Scanner_API constructor.
     */
    public function __construct() {
        $this->wp_scanner = wp_scanner();

        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
        add_action( 'network_admin_notices', array( $this, 'admin_notices' ) );
    }

    /**
     * Admin notices
     */
    public function admin_notices() {
        if ( $this->wp_scanner->settings->is_secret_set() ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $screen = get_current_screen();

        if ( $screen->id === 'settings_page_wp-scanner' ) {
            return;
        }

        $link    = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=wp-scanner' ), __( 'API keys', 'wp-scanner' ) );
        $message = sprintf( __( 'Enter your %s to get started.', 'wp-scanner' ), $link );

        $this->wp_scanner->render_view( 'notice', compact( 'message' ) );
    }

}