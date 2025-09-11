<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'cda_wp_db' );

/** Database username */
define( 'DB_USER', 'cda_wp_db' );

/** Database password */
define( 'DB_PASSWORD', 'cda_wp_db' );

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
define( 'AUTH_KEY',         'VRxl1aGEcAI>YxN=7J1XZLxGgC;6]9utputc^QBue+FE6Sy{ZU$5_(#Q:0O*PC5>' );
define( 'SECURE_AUTH_KEY',  'mh4$<ir:%<4M!J17;)|lced8LlhKps`ojlOWiH)+5xvRB<5a(6bc!@12$[#/+>1o' );
define( 'LOGGED_IN_KEY',    '[ssk^fxUt9?6)?}PkJICB_WWVa9zXA+_9K7+J7v-@s_KC9SArv)Dcd^(@Xq(p6*F' );
define( 'NONCE_KEY',        'OMn9f,,C.b$J`vAkk(ysb:/NiUCj,^`+t[8XT~t(9BLe[25z-#$_!7H& 1ZCo Ne' );
define( 'AUTH_SALT',        '0wQ}<Vf_<2}W#A_yC8]hH>~cX`+,WbD(m0kgOM]Hn)oMnSzpk*.gBTZU;4i!{ sE' );
define( 'SECURE_AUTH_SALT', '7Bz:ki<@(LQ7RPkS7,,#P8q#h*giXkTD HWzO2pYoUV*[git<f~zTB(|hykusZRD' );
define( 'LOGGED_IN_SALT',   '$O6]R(j#p*#iNqKmT:seGh1pO|OM{imaz7#g=Y8G|pelOR5B+.z+v[pu->tM7Wo?' );
define( 'NONCE_SALT',       '&FiQBr&s=W$(r9l/AQZqZh`W2?zo.C#F{f;?/=%x6>;Am:o~+XXB7k9qc++F&m1M' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
// debug ON
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

// wp-config.php
define( 'ACF_PRO_LICENSE', 'b3JkZXJfaWQ9ODIxNDR8dHlwZT1kZXZlbG9wZXJ8ZGF0ZT0yMDE2LTA1LTIzIDEwOjQ5OjA0' );
