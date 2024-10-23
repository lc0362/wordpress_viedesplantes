<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'Jq.BW$B3@9t9m Ytk3S<MPM&jjj Ca}hQ!vSC=l1gJQ%Ons2{]OC?wbN(*R&9Zrm' );
define( 'SECURE_AUTH_KEY',   'z4#QYmphDQ=?8 mqiR4B53?H@.C<C4oVAs<%;i7mu<A}Ly[}m@jZ|jP;N=5~Qi-.' );
define( 'LOGGED_IN_KEY',     '/]6?8q^S.62V)qMUEK]Cvd2Vx}/1J<#R5>ss ?j=qT`l7G^OlUcBgGos=>:-.,=U' );
define( 'NONCE_KEY',         '92`d@>FukOM<aP9|K(F%c1o$ei8CJ~=kZ$&fZR3Bhhtuo,X$!#k3xo -|EEfl1=s' );
define( 'AUTH_SALT',         '$+/Tb}=wsl<d{lF~|a*mx(bmD:^VZboDr`ZCDE}-y&/UnRmRJCu`A6G U4-,T!F#' );
define( 'SECURE_AUTH_SALT',  'a@t8[V?>)Vs_CJ*OUP{We .qr*^uYStQg#x*CvQW q;8c6I/aTaOulM29Py puOW' );
define( 'LOGGED_IN_SALT',    'c={iy;T6nt;b7c<~tjg0/L Y?A-}%j4S0(u=e*t_3tH 2JqC+BX,E= -5H;TSCFL' );
define( 'NONCE_SALT',        'UQl4MhkW!@Er6%j0yU}kQav:q*ol]ZC}>8znZlVje|Km~z{>),u  yyg<Ccf)LyR' );
define( 'WP_CACHE_KEY_SALT', 'kIm(EQRp]Ze%C]NW9pIe2xpK3eR0_]s>y|H27ob?uy)Hx0m98>D33*F/Fgj^FZow' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
