<?php

namespace humanid_spam_filter;

use Exception;
use function cli\render;

class VerificationModule extends Module {
	/**
	 * @since v1.0.0
	 */
	protected function addActions() {
		parent::addActions();
		add_action( 'comment_post', [ $this, 'saveComment' ] );
		add_action( 'wp_footer', [ $this, 'addModal' ] );
		add_action( 'comment_form_after_fields', [ $this, 'addHumanIDField' ] );
		add_action( 'comment_form_logged_in_after', [ $this, 'addHumanIDField' ] );

		add_action( 'wp_ajax_nopriv_hidsf_get_login_url', [ $this, 'getLoginUrl' ] );
		add_action( 'wp_ajax_hidsf_get_login_url', [ $this, 'getLoginUrl' ] );


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

		add_filter( 'preprocess_comment', [ $this, 'validateComment' ] );

	}

	/**
	 * Add human id to the comment
	 * @since v1.0.0
	 */
	public function saveComment( $comment_id ) {
		if ( ( isset( $_POST['human_id_key'] ) ) && ( $_POST['human_id_key'] != '' ) ) {
			$human_id_key = wp_filter_nohtml_kses( $_POST['human_id_key'] );
			$human_id     = get_option( $human_id_key );
			add_comment_meta( $comment_id, 'human_id', $human_id );
			$user = User::where( 'human_id', '=', $human_id )->get();
			if ( sizeof( $user ) == 0 ) {
				$user           = new User();
				$user->human_id = $human_id;
				$user->blocked  = 0;
				$user->save();
			}
			delete_option( $human_id_key );
		}
	}

	/**
	 * Validates a comment, blocks submission if the human id is blocked
	 * @since v1.0.0
	 */
	public function validateComment( $comment_data ) {
		if ( ! isset( $_POST['human_id_key'] ) ) {

			wp_die( __( '<strong>Error</strong>: Please verify your humanity. <p><a href="javascript:history.back()">« Back</a></p>', HIDSF_TEXT_DOMAIN ) );
		}
		$human_id_key = wp_filter_nohtml_kses( $_POST['human_id_key'] );
		$human_id     = get_option( $human_id_key, '' );
		if ( trim( $human_id ) == '' ) {
			wp_die( __( '<strong>Error</strong>: Please verify your humanity. <p><a href="javascript:history.back()">« Back</a></p>', HIDSF_TEXT_DOMAIN ) );
		}

		$user = User::where( 'human_id', '=', $human_id )->get();
		if ( sizeof( $user ) > 0 ) {
			$user = $user[0];
			if ( $user->blocked == 1 ) {
				wp_die( __( '<strong>Error</strong>: You are not allowed to post a comment. <p><a href="javascript:history.back()">« Back</a></p>', HIDSF_TEXT_DOMAIN ) );
			}
		}

		return $comment_data;
	}

	/**
	 * @since v1.0.0
	 */
	public function addModal() {
		$this->renderContent( 'popup' );
	}

	/**
	 * Get the web login url
	 * @since v1.0.0
	 */
	public function getLoginUrl() {
		$client_secret = get_option( 'hidsf_client_secret', '' );
		$client_id     = get_option( 'hidsf_client_id', '' );

		$resp = wp_remote_post( 'https://core.human-id.org/v0.0.3/server/users/web-login', [
			'headers' => [
				"Content-Type"  => 'application/json',
				"client-id"     => $client_id,
				"client-secret" => $client_secret,
			],
		] );
		if ( gettype( $resp ) == 'object' ) {
			wp_send_json_error( __( "An error occurred. Please try again", HIDSF_TEXT_DOMAIN ), 400 );
		} else {
			$body = json_decode( $resp['body'] );
			if ( $body->success ) {
				echo $body->data->webLoginUrl;
			} else {
				wp_send_json_error( $body->message->data, 400 );
			}
		}
		wp_die();
	}

	/**
	 * @since v1.0.0
	 */
	public function addHumanIDField() {
		echo '<input id="human_id_key" name="human_id_key" size="30" type="hidden"/>';
	}

	/**
	 * Generates a random key
	 * @throws Exception
	 * @since v1.0.0
	 */
	public static function generateRandomKey( $length = 10 ): string {
		$keys      = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$randomKey = '';
		for ( $i = 0; $i < $length; $i ++ ) {
			$index     = random_int( 0, strlen( $keys ) - 1 );
			$randomKey .= $keys[ $index ];
		}

		return $randomKey;
	}
}
