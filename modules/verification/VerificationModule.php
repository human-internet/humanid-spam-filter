<?php

namespace humanid_spam_filter;

class VerificationModule extends Module {
	/**
	 * @since v1.0.0
	 */
	protected function addActions() {
		parent::addActions();
		add_action( 'comment_post', [ $this, 'saveComment' ] );
		add_action( 'wp_footer', [ $this, 'addModal' ] );
//		add_filter( 'preprocess_comment', [ $this, 'saveComment' ] );


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

		// Add verification popup
		add_action( 'init', function () {
			add_rewrite_rule( 'hid-verification', 'index.php?hid-verification=$matches[0]', 'top' );
		} );

		add_action( 'template_include', function ( $template ) {
			if ( get_query_var( 'hid-verification' ) == false || get_query_var( 'hid-verification' ) == '' ) {
				return $template;
			}

			return HIDSF_MODULE_DIR . '/verification/templates/popup.php';
		} );
	}

	/**
	 * @since v1.0.0
	 */
	protected function addFilters() {
		parent::addFilters();
		add_filter( 'query_vars', function ( $query_vars ) {
			$query_vars[] = 'hid-verification';

			return $query_vars;
		} );
		add_filter( 'query_vars', function ( $query_vars ) {
			$query_vars[] = 'hid-verification-failed';

			return $query_vars;
		} );
		add_filter( 'query_vars', function ( $query_vars ) {
			$query_vars[] = 'hid-verification-successful';

			return $query_vars;
		} );
	}

	/**
	 * Add human id to the comment
	 * @since v1.0.0
	 */
	public function saveComment( $comment_id ) {
		add_comment_meta( $comment_id, 'human_id', '2xcv43' );
	}
}
