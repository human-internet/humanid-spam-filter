<?php

/**
 * Add libraries to be included
 */


add_filter( 'hidsf_includes_filter', function ( $includes ) {
	$files = [
		HIDSF_LIB_DIR . '/wordpress_tools/KMMenuPage.php', //
		HIDSF_LIB_DIR . '/wordpress_tools/KMSubMenuPage.php', //
		HIDSF_LIB_DIR . '/wordpress_tools/KMSetting.php', //
		HIDSF_LIB_DIR . '/plural/Plural.php', //
	];

	$includes = array_merge( $includes, $files );

	return $includes;
} );