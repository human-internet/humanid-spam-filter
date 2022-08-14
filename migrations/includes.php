<?php

namespace humanid_spam_filter;

/**
 * Add migration files to be included
 */


add_filter( 'hidsf_includes_filter', function ( $includes ): array {
	$migrations = [
		HIDSF_MIGRATIONS_DIR . '/users.php',//
	];

	$includes = array_merge( $includes, $migrations );

	return $includes;
} );