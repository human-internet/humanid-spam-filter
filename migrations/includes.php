<?php

namespace humanid_spam_filter;

/**
 * Add migration files to be included
 */


add_filter( 'hidsf_includes_filter', function ( $includes ): array {
	$migrations = [
		HIDSF_MIGRATIONS_DIR . '/users.php',//
	];

	return array_merge( $includes, $migrations );
} );