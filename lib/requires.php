<?php

/**
 * Add libraries to be included
 */


add_filter( 'hidsf_includes_filter', function ( $includes ) {
	$plugin_path = plugin_dir_path( __FILE__ );

	$files = [
		$plugin_path . 'wordpress_tools/WordPressTools.php', //
	];

	return array_merge( $includes, $files );
} );