<?php
/**
 * @link              www.human-id.org
 * @since             1.0.0
 * @package           humanid_spam_filter
 *
 * @wordpress-plugin
 * Plugin Name: Human ID Spam Filter
 * Plugin URI: https://github.com/human-internet/wordpress-spam-filter
 * Description: [Short description]
 * Version: 1.0.0
 * Author: Kofi Mokome
 * Author URI: www.kofimokome.stream
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: humanid-spam-filter
 * Domain Path: /languages
 */

namespace humanid_spam_filter;

defined( 'ABSPATH' ) or die( 'Giving To Cesar What Belongs To Caesar' );

require 'constants.php';
require HIDSF_CORE_DIR . '/HidSpamFilter.php';
require HIDSF_CORE_DIR . '/Module.php';
require HIDSF_CORE_DIR . '/Migration.php';
require HIDSF_CORE_DIR . '/Model.php';
require HIDSF_CORE_DIR . '/Validator.php';


/**
 * Scan directories for files to include
 * @since v1.0.0
 */
foreach ( scandir( __DIR__ ) as $dir ) {
	if ( strpos( $dir, '.' ) === false && is_dir( __DIR__ . '/' . $dir ) && is_file( __DIR__ . '/' . $dir . '/includes.php' ) ) {
		require __DIR__ . '/' . $dir . '/includes.php';
	}
}

/**
 * Shows an error message on the admin dashboard
 * @since v1.0.0
 */
function HIDSFErrorNotice( $message = '' ) {
	if ( trim( $message ) != '' ):
		?>
        <div class="error notice is-dismissible">
            <p><strong>Human ID Spam Filter: </strong><?php echo $message ?></p>
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

	$includes = apply_filters( 'hidsf_includes_filter', [] );

	foreach ( $includes as $file ) {
		if ( ! $filepath = file_exists( $file ) ) {
			HIDSFErrorNotice( sprintf( __( 'Error locating <b>%s</b> for inclusion', HIDSF_TEXT_DOMAIN ), $file ) );
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
	Migration::runUpdateMigrations();
	//todo: create redirect urls
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
	// set options to remove here
}


register_uninstall_hook( __FILE__, 'humanid_spam_filter\\HIDSFUninstall' );

/**
 * Set of actions to be performed on uninstallation
 * @since v1.0.0
 */
function HIDSFUninstall() {
	Migration::dropAll();
}

register_activation_hook( __FILE__, 'humanid_spam_filter\\HIDSFActivation' );

/**
 * Set of actions to be performed on activation
 * @since v1.0.0
 */
function HIDSFActivation() {
	Migration::runMigrations();

}

// todo: for future use
load_plugin_textdomain( HIDSF_TEXT_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages' );