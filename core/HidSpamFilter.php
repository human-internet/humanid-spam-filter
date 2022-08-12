<?php

namespace hid_spam_filter;

use KMMenuPage;

class HidSpamFilter {
	private $blocked;
	private static $version;

	public function __construct() {
		// do something here
		self::$version = '1.0.0';
		$this->blocked = get_option( "kmcfmf_messages_blocked_today" );
	}

	/**
	 * Todo: Add Description
	 * @since    1.0.0
	 * @access   public
	 */
	private function addOptions() {

		$option_names = array(
			'hidsf_is_permalink_refreshed',
			'hidsf_messages_blocked_today',
		);

		foreach ( $option_names as $option_name ) {
			if ( get_option( $option_name ) == false ) {
				// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
				$deprecated = null;
				$autoload   = 'no';
				add_option( $option_name, 0, $deprecated, $autoload );
			}
		}
	}

	/**
	 * @since v1.0.0
	 * Returns the version number of the plugin
	 */
	public static function getVersion() {
		return self::$version;
	}


	/**
	 * @since v1.0.0
	 * Starts the plugin
	 */
	public function start() {
		// runs the plugin
		$this->addActions();
		$this->initModules();
		$this->addOptions();
		$this->addMenuPage();
	}

	/**
	 * @since v1.0.0
	 * Adds actions
	 */
	public function addActions() {
		add_action( 'admin_enqueue_scripts', [ $this, 'addAdminScripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'addScripts' ] );
	}

	/**
	 * @since v1.0.0
	 * Adds stylesheets and scripts on the client side
	 */
	public function addScripts() {
		wp_enqueue_script( 'hidjs', plugins_url( 'assets/js/app.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.0.0', true );
		wp_enqueue_style( 'hidcss', plugins_url( '/assets/css/app.css', dirname( __FILE__ ) ), '', '1.0.0' );
	}

	/**
	 * @since v1.0.0
	 * Adds stylesheets and scripts on the admin side
	 */
	public function addAdminScripts() {
		wp_enqueue_style( 'hidcss', plugins_url( '/assets/css/app.css', dirname( __FILE__ ) ), '', '1.0.0' );
	}

	/**
	 * @since v1.0.0
	 * Adds the admin menu page
	 */
	public function addMenuPage() {
		$menu_title = 'HID Spam Filter';
		if ( $this->blocked > 0 ) {
			$menu_title .= " <span class='update-plugins count-1'><span class='update-count'>$this->blocked </span></span>";
		}

		$menu_page      = new KMMenuPage( array(
			'page_title' => 'HID Spam Filter',
			'menu_title' => $menu_title,
			'capability' => 'read',
			'menu_slug'  => 'humanid-spam-filter',
			'icon_url'   => 'dashicons-filter',
			'position'   => null,
			'function'   => null
		) );
		$sub_menu_pages = apply_filters( 'hidsf_sub_menu_pages_filter', [] );
		foreach ( $sub_menu_pages as $sub_menu_page ) {
			$menu_page->add_sub_menu_page( $sub_menu_page );
		}
		$menu_page->run();
	}

	/**
	 * @since v1.0.0
	 * Initialise modules
	 */
	public function initModules() {
		foreach ( Module::getModules( HIDSF_MODULE_DIR, false ) as $dir ) {
			$module_name = 'hid_spam_filter\\' . rtrim( $dir, ".php " );
			$module = new $module_name();
			$module->run();
		}
	}


}