<?php

namespace humanid_spam_filter;

use KMMenuPage;

class HidSpamFilter {
	private string $blocked;
	private static string $version;

	public function __construct() {
		$this->blocked = get_option( "hidsf_blocked_today" );
		self::$version = HIDSF_VERSION;
	}

	/**
	 * Register plugins options
	 * @since    1.0.0
	 */
	private function addOptions() {

		$option_names = array(
			'hidsf_is_permalink_updated',
			'hidsf_blocked_today',
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
	public static function getVersion(): string {
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
		wp_enqueue_style( 'humanid_spam_filter_css', plugins_url( '/assets/css/app.css', dirname( __FILE__ ) ), '', HIDSF_VERSION );

		wp_enqueue_script( 'humanid_spam_filter_js', plugins_url( 'assets/js/app.js', dirname( __FILE__ ) ), array( 'jquery' ), HIDSF_VERSION, true );

		wp_localize_script( 'humanid_spam_filter_js', 'hid_ajax_object',
			[
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
			] );
	}

	/**
	 * @since v1.0.0
	 * Adds stylesheets and scripts on the admin side
	 */
	public function addAdminScripts() {
		wp_enqueue_script( 'humanid_spam_filter_js', plugins_url( 'assets/js/admin.js', dirname( __FILE__ ) ), array( 'jquery' ), HIDSF_VERSION, true );
		wp_enqueue_style( 'humanid_spam_filter_css', plugins_url( '/assets/css/app.css', dirname( __FILE__ ) ), '', HIDSF_VERSION );
	}

	/**
	 * @since v1.0.0
	 * Adds the admin menu page
	 */
	public function addMenuPage() {
		$menu_title = 'humanID Setup';
		if ( $this->blocked > 0 ) {
			$menu_title .= " <span class='update-plugins count-1'><span class='update-count'>$this->blocked </span></span>";
		}

		$menu_page      = new KMMenuPage( array(
			'page_title' => 'humanID Setup',
			'menu_title' => $menu_title,
			'capability' => 'read',
			'menu_slug'  => 'humanid-spam-filter',
			'icon_url'   => HIDSF_ASSET_URL . '/images/humanid_white.png',
			'position'   => 100,
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
			$module_name = 'humanid_spam_filter\\' . rtrim( $dir, ".php " );
			$module      = new $module_name();
			$module->run();
		}
	}


}