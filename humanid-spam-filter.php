<?php
/**
 * @link              www.human-id.org
 * @since             1.0.0
 * @package           humanid_spam_filter
 *
 * @wordpress-plugin
 * Plugin Name: humanID – Anti-Spam Comment Filter || Stop junk comments & Protect your users' privacy. 100% open source.
 * Plugin URI: https://github.com/human-internet/humanid-spam-filter
 * Description: Replace ReCAPTCHA with a faster, user-friendly solution and block spammers & bots permanently
 * Version: 2.1.0
 * Author: humanID
 * Author URI: https://human-id.org/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: humanid-spam-filter
 * Domain Path: /languages
 */

namespace humanid_spam_filter;

use KMEnv;
use WordPressTools;

defined( 'ABSPATH' ) or die( 'Giving To Cesar What Belongs To Caesar' );

require 'constants.php';
require HIDSF_CORE_DIR . '/HidSpamFilter.php';
require HIDSF_CORE_DIR . '/Module.php';

/**
 * Shows an error message on the admin dashboard
 * @since v1.0.0
 */
function HIDSFErrorNotice( $message = '' ) {
	if ( trim( $message ) != '' ):
		?>
        <div class="error notice is-dismissible">
            <p><strong>Human ID Spam Filter: </strong><?php echo esc_html( $message ) ?></p>
        </div>
	<?php
	endif;
}

add_action( 'admin_notices', 'humanid_spam_filter\\HIDSFErrorNotice', 10, 1 );

/***
 * loads classes / files
 * @since v1.0.0
 ***/
function HIDSFLoader(): bool {
	$error = false;

	// scan directories for requires.php files
	foreach ( scandir( __DIR__ ) as $dir ) {
		if ( strpos( $dir, '.' ) === false && is_dir( __DIR__ . '/' . $dir ) && is_file( __DIR__ . '/' . $dir . '/requires.php' ) ) {
			require_once __DIR__ . '/' . $dir . '/requires.php';
		}
	}

	$requires = apply_filters( 'hidsf_requires_filter', [] );

	foreach ( $requires as $file ) {
		if ( ! $filepath = file_exists( $file ) ) {
			HIDSFErrorNotice( sprintf( __( 'Error locating <b>%s</b> for inclusion', KMCF7MS_TEXT_DOMAIN ), $file ) );
			$error = true;
		} else {
			require_once $file;
		}
	}


	// scan directories for includes.php files
	foreach ( scandir( __DIR__ ) as $dir ) {
		if ( strpos( $dir, '.' ) === false && is_dir( __DIR__ . '/' . $dir ) && is_file( __DIR__ . '/' . $dir . '/includes.php' ) ) {
			require_once __DIR__ . '/' . $dir . '/includes.php';
		}
	}

	$includes = apply_filters( 'hidsf_includes_filter', [] );

	foreach ( $includes as $file ) {
		if ( ! $filepath = file_exists( $file ) ) {
			HIDSFErrorNotice( sprintf( __( 'Error locating <b>%s</b> for inclusion', KMCF7MS_TEXT_DOMAIN ), $file ) );
			$error = true;
		} else {
			include_once $file;
		}
	}

	return $error;
}

/**
 * Starts the spam filter
 * @since v1.0.0
 */
function HIDSFStart() {
	$wordpress_tools = new WordPressTools( __FILE__ );
	$wordpress_tools->migration_manager->runMigrations();

	$spam_filter = new HidSpamFilter();
	$spam_filter->start();
}


if ( ! HIDSFLoader() ) {
	HIDSFStart();
}


// remove options upon deactivation

register_deactivation_hook( __FILE__, 'humanid_spam_filter\\HIDSFDeactivation' );

/**
 * Set of actions to be performed on deactivation
 * @since v1.0.0
 */
function HIDSFDeactivation() {
	delete_option( 'hidsf_is_permalink_updated' );
	// set options to remove here
}


register_uninstall_hook( __FILE__, 'humanid_spam_filter\\HIDSFUninstall' );

/**
 * Set of actions to be performed on uninstallation
 * @since v1.0.0
 */
function HIDSFUninstall() {
	global $wpdb;

	delete_option( 'hidsf_is_permalink_updated' );
	$instance = WordPressTools::getInstance( __FILE__ );
	$instance->migration_manager->dropAll();

	//query the wp options table and delete all options that start with hidsf_
	$query = $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE 'hidsf_%'" );
	$wpdb->query( $query );


	// todo; drop migrations table
	$env        = ( new KMEnv( __FILE__ ) )->getEnv();
	$table_name = $wpdb->prefix . trim( $env['TABLE_PREFIX'] ) . 'migrations';
	$query      = $wpdb->prepare( "DROP TABLE IF EXISTS {$table_name}" );
	$wpdb->query( $query );
}

register_activation_hook( __FILE__, 'humanid_spam_filter\\HIDSFActivation' );

/**
 * Set of actions to be performed on activation
 * @since v1.0.0
 */
function HIDSFActivation() {
	// set options to add here
}

// todo: for future use
load_plugin_textdomain( HIDSF_TEXT_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages' );