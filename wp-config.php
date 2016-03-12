<?php
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
define('DB_PASSWORD', 'nguyen!@');

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
define('AUTH_KEY',         'Ghy]-Q,V0z2y/rw?HbSoUPzsQ{B@Q5,`EX3S>Kv_GVSLjC2l{?BD7CHk$.6szjK}');
define('SECURE_AUTH_KEY',  '{%Q>y80`aFc-[vTl`9GDkT2e| 8mzPTeM)%lIMlxR O>}yX;+k|F<PWGCZ;uBErY');
define('LOGGED_IN_KEY',    'T=u^:)|J3]K4mX>`PXa^ULSF$.hne+GtRq.B5&3!FB>nRw3!7`DF3yjR%G5O&IIR');
define('NONCE_KEY',        'lLgV5YB+u~/u*0}|q/y c)Up5&_P9q&0+N!(mgss+KahE%*bDhcW}5K]o3nHC45v');
define('AUTH_SALT',        '~i;L+J${=7^TT*BI&#|PZVzH93qj)qXljW&1=c4S|mDVF)p2N^0Q5RJ#L*P =1gM');
define('SECURE_AUTH_SALT', '!i%)ve46s4_v*+zNULxpjG6j!GFZ-S6*!B2ftuXWhB7FjW<CogeFtfCAs^WZrS^G');
define('LOGGED_IN_SALT',   '/ qE|y*w7TCoQ|F8iOkc|nB&!R:-E@t.F4pt+R;6du7>.c?Idm f>+>~G|N[Jwp8');
define('NONCE_SALT',       'y;-6b]?![koJ$lQ~T->I%df=(=pmm5RgEi--j@YQyX!]%0#FK:;(^yZ5w2$:Za:%');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ruounho_';

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
define('WP_DEBUG', true);
ini_set('log_errors',TRUE);
ini_set('error_reporting', E_ALL);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
define('FS_METHOD', 'direct');
