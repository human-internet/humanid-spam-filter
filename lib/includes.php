<?php

/**
 * Add libraries to be included
 */


add_filter( 'kmcf7_includes_filter', function ( $includes ) {
	$files = [
		KMCF7MS_LIB_DIR . '/wordpress_tools/KMMenuPage.php', //
		KMCF7MS_LIB_DIR . '/wordpress_tools/KMSubMenuPage.php', //
		KMCF7MS_LIB_DIR . '/wordpress_tools/KMSetting.php', //
	];

	$includes = array_merge( $includes, $files );

	return $includes;
} );