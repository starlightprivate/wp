<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'FVzppX35Au');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '<W@stUCCy;ue{!&cL&MR|+E:6IYA2U]%fF(pk1tPJaa_Y?ru6j@dGfHc<BU%OrB`');
define('SECURE_AUTH_KEY',  'v,>w*H2AfAu]m~Q;1NN=L}^Cri3$!b)(OlRaoV117#7wp5)=zJ5EibiuZoakeD[`');
define('LOGGED_IN_KEY',    'Bu>SN9Ppop]i$%*c#R(f <7bGKU^T3A`vKeK5lA5{m{@zR:J|69y5s?9ll4<+q?S');
define('NONCE_KEY',        'chZrcdxjB@j^Gz2cPlU%FU{u{+qe+JJfvEd>S3gP&>qgE$i:>-Lkn88jt1$G[I>u');
define('AUTH_SALT',        ':nZ#=X3|{.0XRL/NHdOB!JBqJ%r7P$]%;U w.Rr[$%F?tP6P+%1zaqJ3|d/=W3<`');
define('SECURE_AUTH_SALT', 'OoG]~</$uA!8}xI>H8~2/YopdZ;,Difptl-m$:&:{.6QLB$$QhJ~:}}UA8o3c{=h');
define('LOGGED_IN_SALT',   'AMa|HG1Q<P!G}}5x)6@nf-aEVE o)WBl[t$Ab?}f>22w3 %UK.J(`f,5zS8t6c_C');
define('NONCE_SALT',       '}[+qOA=h~PqCPsxXaI1HkxZgTn+7zu&<xGM|{~*L&T+(@aQPX&/gZl`G?lUs^,>E');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
define('CONCATENATE_SCRIPTS', false);

