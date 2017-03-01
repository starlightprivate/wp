<?php
/*
Plugin Name: WP Scanner
Plugin URI: https://wpscanner.io
Description: WordPress performance and security monitoring.
Author: A5hleyRich
Version: 1.0.2
Author URI: https://wpscanner.io
Text Domain: wp-scanner
Domain Path: /languages/

// Copyright (c) 2016 WP Scanner. All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Scanner
 *
 * @return WP_Scanner
 */
function wp_scanner() {
	require_once dirname( __FILE__ ) . '/classes/class-wp-scanner.php';

	$version = '1.0.2';

	return WP_Scanner::get_instance( __FILE__, $version );
}

// Initialize the plugin
wp_scanner();
