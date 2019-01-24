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
define('DB_NAME', 'mocambique');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '8j>pE?bG)W1;Am|VqK=cw)4GtG&B*Uz jxjy^Gykxg]cB``]9`M5(]u^,T/EdD3O');
define('SECURE_AUTH_KEY',  'D$sEQdxPcm9B}acY5XfI+m} %QR2+^S1G6LU$j+[-D3{lmn%RDl)si}u{2LI&LZ%');
define('LOGGED_IN_KEY',    'D4 XY~]_gTWOoN71oR<cPW*uTV_%jMlhq&c97VeQNd`N&B<j)Wah=.&|QP`B|lq-');
define('NONCE_KEY',        'jh/G;Xzj|L@|7pVns 3u E3e$&|)4{[w+/yc&a{`hsih{[;0cOt{w,L@2I{7a9u#');
define('AUTH_SALT',        'oj-e1|5L(^k$yBnit=N0*p,eoVty`&X|ju_UyB82gqzEiGr)hE2n|L/Jz1y%Fj`=');
define('SECURE_AUTH_SALT', 'THl(dq#5P2HG?Xt=[d41^s#M#~$L^Bg F]&3q!5.LS!&HQd0RNXdKIcWqK;O;8Kf');
define('LOGGED_IN_SALT',   '<G9#*!NM@6)`9Fd5U}UE%K Ef?k@s|jDZeWSiHTT]lb5vii}vi$z2O|DhNN33[0T');
define('NONCE_SALT',       'sD]Q{~Jgn+X8mv;K]]T4KD7`Yi3jscKvEYU/Tqj^=-+^mV@fkU%H9g90X-,B|m%^');

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
