<?php

namespace hid_spam_filter;

class VerificationModule extends Module {
	protected function addActions() {
		parent::addActions();

		// Add verification success redirect link
		add_action( 'init', function () {
			add_rewrite_rule( 'hid-verification-successful', 'index.php?hid-verification-successful=$matches[0]', 'top' );
		} );

		add_action( 'template_include', function ( $template ) {
			if ( get_query_var( 'hid-verification-successful' ) == false || get_query_var( 'hid-verification-successful' ) == '' ) {
				return $template;
			}

			return HIDSF_MODULE_DIR . '/verification/templates/success.php';
		} );

		// Add verification failed redirect link
		add_action( 'init', function () {
			add_rewrite_rule( 'hid-verification-failed', 'index.php?hid-verification-failed=$matches[0]', 'top' );
		} );

		add_action( 'template_include', function ( $template ) {
			if ( get_query_var( 'hid-verification-failed' ) == false || get_query_var( 'hid-verification-failed' ) == '' ) {
				return $template;
			}

			return HIDSF_MODULE_DIR . '/verification/templates/failure.php';
		} );
	}
}
