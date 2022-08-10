<?php

namespace hid_spam_filter;

use KMSubMenuPage;

class DashboardModule extends Module {
	private $blocked;

	public function __construct() {
		parent::__construct();
		$this->blocked = get_option( "hidsf_messages_blocked_today" );
	}

	/**
	 * @since v1.0.0
	 * Adds settings submenu page
	 */
	function addSubMenuPage( $sub_menu_pages ) {
		$menu_title = 'HID Spam Filter';
		if ( $this->blocked > 0 ) {
			$menu_title .= " <span class='update-plugins count-1'><span class='update-count'>$this->blocked </span></span>";
		}

		$dashboard_page = new KMSubMenuPage(
			array(
				'page_title' => $menu_title,
				'menu_title' => $menu_title,
				'capability' => 'manage_options',
				'menu_slug'  => 'hid-spam-filter',
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
	 * Displays content on dashboard sub menu page
	 */
	function dashboardPageContent() {
		$this->renderContent( 'index' );
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