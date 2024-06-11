<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'site2' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '@z36u68#& *w&GMfX)YhD.f{oyXhody#GJ/706nX6Cuooi9[NH||&V}3xJv=2h*Z' );
define( 'SECURE_AUTH_KEY',  '66$x1@gY}3bt`C,]H<))rk^fU`,R61CPl*?Ha ycXh#DWuO~`#,S.cE^6%JP|OQP' );
define( 'LOGGED_IN_KEY',    'FQ,o60#/Nx*t0*y3z:wVPvjLVK(+Sy*nKQ}+DguekR|KefH]aIb9T6a|-(@Z]w*X' );
define( 'NONCE_KEY',        ';$@+eBUo!/Y_3lBodQTQ;EJ&=DM9>xbfJ?WPk)92xK,j1-WPX@U{8:X@@V9/#=$e' );
define( 'AUTH_SALT',        'C-eE!`-e7rZ3Kelv+r,b[YwKqt~2qxwdtQMqVYqQkM5Eb6&={gXt#Na<5NG}`XVQ' );
define( 'SECURE_AUTH_SALT', '?z*} ]N(1q8]|MLuh$|V;j!aecyd:PD=mg0 {gMm:h{N`[Oxr/Pt.{QY9UUk(hcX' );
define( 'LOGGED_IN_SALT',   '_yJ6`}?<ArG+elN(C<z[4%ku=<vQ&98n9geC6@:JOT~yT(x?rw~d[j.jRd# 0a}9' );
define( 'NONCE_SALT',       'OCzyOd:?=XPo)7o).F}`4c$wlT7MpQgI=7@Z[Dd)0BFH/V|B7#[6Nf8fE5gZ+$<%' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
