<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'tim2020' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'tim2020' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'patat3Po1l' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

//// ** Réglages MySQL - Pour hébergement sur TIMUNIX. ** //
///** Nom de la base de données de WordPress. */
//define('DB_NAME', '20_rpni4_mngrant');
//
///** Utilisateur de la base de données MySQL. */
//define('DB_USER', '17_mngrant');
//
///** Mot de passe de la base de données MySQL. */
//define('DB_PASSWORD', '1761554');
//
///** Adresse de l'hébergement MySQL. */
//define('DB_HOST', 'localhost');
//
///** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
//define( 'DB_CHARSET', 'utf8mb4' );
//
///** Type de collation de la base de données.
// * N’y touchez que si vous savez ce que vous faites.
// */
//define('DB_COLLATE', '');


/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '.kkjl57RRrpbLI,*rLrS+JNv8[Pw+DZ$]dA*A!C.,`0:H:1dPq;*So|I^7aS >f>' );
define( 'SECURE_AUTH_KEY',  'aFL1W9Wmq_D^tIY65-}h(i7nL {x*0S#~cr2Qb>:e#UoY&T>L*mZi&DK_4KfW8:>' );
define( 'LOGGED_IN_KEY',    '(*ug/avh5|e_V(Ka3jvyJ</T@)1ua?[Cj$ kIXb(Cokh&W=$/yMWWa?qTNuvH4aA' );
define( 'NONCE_KEY',        '6Hd5<5D%&1tYZdi[q)t HQ_C1<-!xhhCU@3q28SMAO5_A$5_I8gRBrvzVx]zhS)0' );
define( 'AUTH_SALT',        '3MnPK K,i{(hdC^zDuM9}O|_=zFj8sfQFSalW`5U5j@p-6|A_mbm9y)jf3Lw6}Bm' );
define( 'SECURE_AUTH_SALT', 'm:WL#8zax[Q=}AgF&x:p~wR6MPA/qguPi-Ax-MnwlBUW[!6:0]uOm:oJ;AA!a@qG' );
define( 'LOGGED_IN_SALT',   ')7oz`Q;#m7bN+0Gv>g@z&j)XY>(u:cg+Cm*yW FSpMt_xrEbe`QhQTv#c9wEVt{.' );
define( 'NONCE_SALT',       '7Yq{0{.r3{1&x+D$mlzP33Q+?d0,,SuX&wR>{[m&k2WEz1f?O/(q.mZ?/[&d?wp`' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'tim_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');

/** Pour empêcher les mises à jour automatiques, pour l'instant! */
define( 'WP_AUTO_UPDATE_CORE', false );

/** Pour empêcher les demandes des informations FTP */
define('FS_METHOD', 'direct');
