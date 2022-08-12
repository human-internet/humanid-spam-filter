<?php

namespace humanid_spam_filter;

/**
 * Add migration files to be included
 */


add_filter( 'hidsf_includes_filter', function ( $includes ): array {
	$models = [
		HIDSF_MIGRATIONS_DIR . '/migrations.php',//
	];

	$includes = array_merge( $includes, $models );

	return $includes;
} );