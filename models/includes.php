<?php

namespace humanid_spam_filter;

/**
 * Add models to be included
 */

function addModels( $includes ): array {
	$models = [
		HIDSF_MODELS_DIR . '/User.php',//
	];

	return array_merge( $includes, $models );
}

add_filter( 'hidsf_includes_filter', 'humanid_spam_filter\\addModels' );