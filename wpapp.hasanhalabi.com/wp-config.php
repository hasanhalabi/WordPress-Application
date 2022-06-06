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
/**
 * The name of the database for WordPress
 */
define ( 'DB_NAME', 'hasan_wpapp_sample' );

/**
 * MySQL database username
 */
define ( 'DB_USER', 'wwwuser' );

/**
 * MySQL database password
 */
define ( 'DB_PASSWORD', 'WwwU$sr#202105' );

/**
 * MySQL hostname
 */
define ( 'DB_HOST', 'localhost' );

/**
 * Database Charset to use in creating database tables.
 */
define ( 'DB_CHARSET', 'utf8' );

/**
 * The Database Collate type.
 * Don't change this if in doubt.
 */
define ( 'DB_COLLATE', 'utf8_general_ci' );
define( 'Powered_BY_NAME','Hasan Halabi - WP Applications' );
define( 'Powered_BY_URL','https://github.com/hasanhalabi/WordPress-Application');
define ('Footer_System_Name','WP Application Sample - Lawyers Office Management System');
define('HIDE_LOGIN_DESC',true);
/**
 * #@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define ( 'AUTH_KEY', 'vyfa75>u!4F(@15zQ#R;MMWGoL9r[^roSCmnw3YvQF}VfcZs0*rw^&eGRVymhm0v' );
define ( 'SECURE_AUTH_KEY', '_EgaOtd^{1Zf0py,+X-!8>=S7&F]9>n-,m=tMr>3t#$9-%@d)},heKo-{B!UOh`^' );
define ( 'LOGGED_IN_KEY', 'nyq&.4]d9+m3%q{dJ3@/5<U={-I{=bS8UFBBVdGD}gyuKM*ea3h=iP.x|sN,5=jU' );
define ( 'NONCE_KEY', 'ty}JkFauvYc@6Gz9<pGO!?|r0 `|{|Ks-]owpN^N1iwfda9z_acT,(EeANbpJG?d' );
define ( 'AUTH_SALT', '}1~;;AUI7) )f-=SMdk V~8Bt?I?QGTk$o|?gMbJzDQ[JSP=a+7-f|g:6?+E:raH' );
define ( 'SECURE_AUTH_SALT', '%6,maWg!bZdxA/Kz~`1^9U]>}>]f|}TPxs^+#LKs^)iorZ/T4]Za,},P)4,~NOO@' );
define ( 'LOGGED_IN_SALT', '[tNZQ/8B?Z&+10$hJ|gfRLNFmS_uV;e;pIam#?a#l$/+D^&V/NB#hh&CMtP8D%3J' );
define ( 'NONCE_SALT', ';PeL+Qb<8f:Nh#g1:^Y:|aTMNK07<(&Q7*+3UJX&6s9$i:P=Vl<[J$uN_o^9ic$H' );

/**
 * #@-
 */

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'jomiz_';

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
define ( 'WP_DEBUG', false ); 
define ( 'HH_USE_HORIZONTAL_MENU', true );


/* That's all, stop editing! Happy blogging. */

/**
 * Absolute path to the WordPress directory.
 */
if (! defined ( 'ABSPATH' ))
	define ( 'ABSPATH', dirname ( __FILE__ ) . '/' );

/**
 * Sets up WordPress vars and included files.
 */
require_once (ABSPATH . 'wp-settings.php');
//set_time_limit(300);
/**
* increase query speed
* /
