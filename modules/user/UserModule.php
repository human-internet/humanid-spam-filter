<?php

namespace humanid_spam_filter;

use KMSubMenuPage;
use KMValidator;
use WordPressTools;

$comment_id = ''; // used in comment.php

class UserModule extends Module {

	/**
	 * Add a custom column to the WordPress comment page
	 * @since v1.0.0
	 */
	public function addCustomColumnToCommentsPage( $columns ) {
		$columns['human_id'] = __( 'humanID', HIDSF_TEXT_DOMAIN );

		return $columns;
	}

	/**
	 * Show the custom column on the WordPress comment page
	 * @since v1.0.0
	 */
	public function showCustomColumnOnCommentsPage( $column, $id ) {
		global $comment_id;
		if ( 'human_id' == $column ) {
			$comment_id = $id;

			$wordpress_tools = WordPressTools::getInstance( __FILE__ );
			$wordpress_tools->renderView( 'users.comment' );
		}
	}

	/**
	 * @since v1.0.0
	 * Adds users page
	 */
	function addSubMenuPage( $sub_menu_pages ) {
		$menu_title = __( "Users", HIDSF_TEXT_DOMAIN );

		$users_page = new KMSubMenuPage(
			array(
				'page_title' => $menu_title,
				'menu_title' => $menu_title,
				'capability' => 'manage_options',
				'menu_slug'  => 'humanid-spam-filter-users',
				'function'   => array(
					$this,
					'usersPageContent'
				)
			) );

		array_push( $sub_menu_pages, $users_page );

		return $sub_menu_pages;
	}

	/**
	 * @since v1.0.0
	 * Displays content on users page
	 */
	public function usersPageContent() {
		$wordpress_tools = WordPressTools::getInstance( __FILE__ );
		$wordpress_tools->renderView( 'users.index' );
	}

	/**
	 * Blocks/unblock a user from commenting
	 * @since v1.0.0
	 */
	public function updateUser() {
		$validator = KMValidator::make( [
			'human_id' => "required",
			'status'   => "required|bool",
		], $_POST );

		if ( $validator->validate() ) {

			$human_id = sanitize_text_field( $_POST['human_id'] );
			$status   = sanitize_text_field( $_POST['status'] == 'true' );
			$user     = User::where( 'human_id', '=', $human_id )->get();

			if ( sizeof( $user ) > 0 ) {
				$user          = $user[0];
				$user->blocked = $status;
				$user->save();

				echo json_encode( __( "User updated", HIDSF_TEXT_DOMAIN ) );
			} else {
				wp_send_json_error( __( 'Invalid humanID', HIDSF_TEXT_DOMAIN ), 400 );
			}
		}
		wp_die();
	}

	protected function addActions() {
		parent::addActions();
		add_action( 'wp_ajax_hidsf_update_user', [ $this, 'updateUser' ] );
	}

	/**
	 * @since v1.0.0
	 */
	protected function addFilters() {
		parent::addFilters();
//		add_filter( 'hidsf_sub_menu_pages_filter', [ $this, 'addSubMenuPage' ] );

		add_filter( 'manage_comments_custom_column', [ $this, 'showCustomColumnOnCommentsPage' ], 10, 2 );
		add_filter( 'manage_edit-comments_columns', [ $this, 'addCustomColumnToCommentsPage' ] );
	}
}