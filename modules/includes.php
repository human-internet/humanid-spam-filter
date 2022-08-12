<?php

namespace humanid_spam_filter;

/**
 * Automatically include all modules
 */

add_filter( 'hidsf_includes_filter', function ( $includes ) {
	$modules  = Module::getModules( HIDSF_MODULE_DIR );

	return array_merge( $includes, $modules );
} );
