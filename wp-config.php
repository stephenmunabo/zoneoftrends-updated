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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'zot_base' );

/** MySQL database username */
define( 'DB_USER', 'zot_man' );

/** MySQL database password */
define( 'DB_PASSWORD', 'thisisthezot' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '-O_g48n4;Pzz.~5*a[}GZE}8#{jaRy7>tt>*w~md!pa%^9~XknD8Y8$eGo-S.z:n' );
define( 'SECURE_AUTH_KEY',  'UHlo}Y@of98,.DIq7@dc2p(F-.J-H?Ia!?z(tOMy.2Z#hWQ|4 VY!t322}<C4`yN' );
define( 'LOGGED_IN_KEY',    'OZaAx%c$*3d-l<b,s*D5HtHQK-]s|BAQ5,9.%>_n-;4lk5|tTb x|.C>;fZ0OE}q' );
define( 'NONCE_KEY',        'a~/kn)UwVFu&Bxh7Ye,T|sb:S5G@W}%g(0$SSiR%Z7zQ<YxE}A.Aq!27|K~hMf!a' );
define( 'AUTH_SALT',        'WFV{!DJi_@P_.qxd$QW]jfE9Cd`b/3LCoXpkNA AhKEpagL~(.q&B*m|[=eI:}*n' );
define( 'SECURE_AUTH_SALT', '3VuVyOUt4o!iPp{~?V44<1i#Tpyoi5qAT`9ai%]V,iI*h4>B{q}xvSpszzXu7K[k' );
define( 'LOGGED_IN_SALT',   '?N-ZHIC^9#w[;b5l)j,HS[z#y*./NumpKh,&M?l0R$.1w^?3k[ke`#H:HpMP?=?:' );
define( 'NONCE_SALT',       '5N+q%!?3f-Z~b9%-38O}3umN{e=D;pc4&3KmhX&J=QVCD_xu9eLl;yQdz/XAyK:~' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
