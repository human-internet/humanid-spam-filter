<?php

namespace humanid_spam_filter;

use KMSetting;
use KMSubMenuPage;
use WordPressTools;

class DashboardModule extends Module {
	private string $blocked;

	public function __construct() {
		parent::__construct();
		$this->blocked = get_option( "hidsf_blocked_today" );
	}

	/**
	 * @since v1.0.0
	 * Adds dashboard page
	 */
	function addSubMenuPage( $sub_menu_pages ) {
		$menu_title = 'hID Spam Filter';
		if ( $this->blocked > 0 ) {
			$menu_title .= " <span class='update-plugins count-1'><span class='update-count'> $this->blocked </span></span>";
		}

		$dashboard_page = new KMSubMenuPage(
			array(
				'page_title' => "humanID Spam Filter",
				'menu_title' => $menu_title,
				'capability' => 'manage_options',
				'menu_slug'  => 'humanid-spam-filter',
				'function'   => array(
					$this,
					'dashboardPageContent'
				)
			) );

		array_push( $sub_menu_pages, $dashboard_page );

		return $sub_menu_pages;
	}

	/**
	 * @since v1.0.0
	 * Displays content on dashboard page
	 */
	public function dashboardPageContent() {
		$wordpress_tools = WordPressTools::getInstance( __FILE__ );
		$wordpress_tools->renderView( 'dashboard.index' );
	}

	/**
	 * Overrides parent run method
	 * @since v1.0.0
	 */
	public function run() {
		parent::run();
		$this->registerSettings();
		$this->checkIfPermalinkIsUpdated();
	}

	/**
	 * Registers the settings field for the client token and secret
	 * @since v1.0.0
	 */
	private function registerSettings() {

		// Check documentation here https://github.com/kofimokome/WordPress-Tools

		$settings = new KMSetting( 'humanid-spam-filter' );
		$settings->add_section( 'humanid-spam-filter' );
		$settings->add_field(
			array(
				'type'        => 'text',
				'id'          => 'hidsf_client_id',
				'label'       => __( 'Client ID', HIDSF_TEXT_DOMAIN ),
				'placeholder' => 'SERVER_XXXXXXXXXXXXXXXXXXXXXX'
			)
		);
		$settings->add_field(
			array(
				'type'        => 'text',
				'id'          => 'hidsf_client_secret',
				'label'       => __( 'Client Secret', HIDSF_TEXT_DOMAIN ),
				'placeholder' => 'e6-10mx7WaiYfQbZpZNAJHDp7dOLMxu'
			)
		);

		$settings->save();
	}

	/**
	 * Check if the "I have updated permalink" button is clicked
	 * @since v1.0.0
	 */
	private function checkIfPermalinkIsUpdated() {
		if ( isset( $_GET['updatePermalink'] ) ) {
			$updatePermalink = sanitize_text_field( $_GET['updatePermalink'] );
			if ( $updatePermalink == 'yes' ) {
				update_option( 'hidsf_is_permalink_updated', 1 );
			}
		}
	}

	/**
	 * @since v1.0.0
	 */
	protected function addFilters() {
		parent::addFilters();
		add_filter( 'hidsf_sub_menu_pages_filter', [ $this, 'addSubMenuPage' ] );
		// add actions here
	}
}