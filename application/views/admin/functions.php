<?php
/**
 * tokoder
 *
 * An Open-source online ordering and management system for store
 *
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link			https://github.com/tokoder/tokoder
 * @since		1.0.0
 */
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Admin theme functions.php file.
 *
 * This file is an example of how to use functions.php for your themes.
 * You can use a class or simply a list of functions to add your hooks.
 *
 * @category 	Themes
 * @author		Tokoder Team
 */
// ------------------------------------------------------------------------
if ( ! class_exists('Admin_theme', false)):
// ------------------------------------------------------------------------
class Admin_theme
{
    public function __construct()
    {
		add_filter('_admin_menu', array($this, '_admin_menu'), 0);

		// Views files.
		add_filter( 'admin_views_path', function () {
			return get_theme_path( 'templates/' );
		} );

		// Partials files.
		add_filter( 'admin_partials_path', function () {
			return get_theme_path( 'partials/' );
		} );

		// Views files.
		add_filter( 'admin_views_path', function () {
			return get_theme_path( 'templates/' );
		} );

		if ( get_instance()->input->is_ajax_request()) {
			return;
		}

		// Enqueue our assets.
		add_action('after_theme_setup', array( $this, 'after_theme_setup' ), 0 );
		add_filter('admin_head', array($this, 'admin_head'), 0);

		// Partials enqueue for caching purpose.
		add_action('enqueue_admin_partials', array( $this, 'enqueue_admin_partials' ) );

		// body_class.
		add_filter('admin_body_class', function ($args) {
			return array_clean(array_merge($args, ['d-flex flex-column min-vh-100']));
		} );
    }

	// ----------------------------------------------------------------------------

	public function _admin_menu($admin_menu)
	{
		// Settings menu
		$admin_menu[] = array(
			'parent' => '_setting_menu',
			'order'  => 0,
			'id'     => '_setting_general',
			'slug'   => admin_url('settings'),
			'name'   => __('general_settings'),
		);

		// users menu
		$admin_menu[] = array(
			'parent' => '_user_menu',
			'order'  => 0,
			'id'     => '_user_manager',
			'slug'   => admin_url('users'),
			'name'   => __('users_manage'),
		);

		// Extensions menu.
		foreach (['packages', 'themes', 'languages'] as $key => $value) {
			$admin_menu[] = array(
				'parent' => '_extension_menu',
				'order'  => 0,
				'id'     => 'extensions_'.$value,
				'slug'   => admin_url($value),
				'name'   => __('lang_'.$value),
			);
		}

		// Helps menu.
		$admin_menu[] = array(
			'parent' => '_help_menu',
			'order'  => 0,
			'id'     => '_help_about',
			'slug'   => admin_url('about'),
			'name'   => __('lang_system_information'),
		);
		$admin_menu[] = array(
			'parent' => '_help_menu',
			'order'  => 0,
			'id'     => '_help_documentation',
			'slug'   => prep_url('github.com/tokoder/tokoder/wiki'),
			'name'   => __('lang_documentation'),
			'attributes' => array(
				'target' =>'_blank',
				'class'  => (ENVIRONMENT == 'development' ? '': ' disabled')
			),
		);

		return $admin_menu;
	}

	// ----------------------------------------------------------------------------
	/**
	 * This method is triggered after theme was installed.
	 * @access 	public
	 */
	public function after_theme_setup()
	{
		add_styles('assets/vendor/bootstrap/css/bootstrap.css');
		add_styles('assets/vendor/fontawesome-free/css/all.css');
		add_styles('assets/vendor/sweetalert2/sweetalert2.css');
		add_styles('assets/vendor/bootnavbar/css/bootnavbar.css');
		add_styles(get_theme_path('style.css'));

		add_script('assets/vendor/jquery/jquery.js');
		add_script('assets/vendor/bootstrap/js/bootstrap.bundle.js');
		add_script('assets/vendor/lazysizes/lazysizes.min.js');
		add_script('assets/vendor/js-cookie/js.cookie.js');
		add_script('assets/vendor/sweetalert2/sweetalert2.js');
		add_script('assets/vendor/bootnavbar/js/bootnavbar.js');
		add_script('assets/vendor/bootbox/bootbox.js');
		add_script(get_theme_path('assets/js/admin.js'));
	}

	// ----------------------------------------------------------------------------

	/**
	 * We enqueue our partial views so they get cached.
	 * @access 	public
	 */
	public function enqueue_admin_partials() {
		add_partial( 'admin_header' );
		add_partial( 'admin_footer' );
	}

	// ------------------------------------------------------------------------

	/**
	 * admin_head
	 *
	 * Method for adding extra stuff to admin output before closing </head> tag.
	 *
	 * @access 	public
	 * @param 	string 	$output 	The admin head output.
	 * @return 	void
	 */
	public function admin_head($output)
	{
		add_ie9_support($output, (ENVIRONMENT !== 'development'));

		// Default configuration.
		$config = array(
			'baseURL'      => base_url(),
			'adminURL'     => admin_url(),
			'currentURL'   => current_url(),
			'ajaxURL'      => ajax_url(),
			'site_url'     => site_url(),
			'token_name'   => config_item('csrf_token_name'),
			'token_cookie' => config_item('csrf_cookie_name'),
			'lang'         => get_instance()->lang->lang_lists(get_instance()->session->language),
		);

		// Generic language lines.
		$lines = array(
			'activate'       => __('confirm_active'),
			'deactivate'     => __('confirm_deactivate'),
			'delete'         => __('confirm_delete'),
			'remove'         => __('confirm_delete_permanent'),
			'deleteselected' => __('confirm_delete_selected'),
			'disable'        => __('confirm_disable'),
			'enable'         => __('confirm_enable'),
			'install'        => __('confirm_install'),
			'restore'        => __('confirm_restore'),
			'upload'         => __('confirm_upload'),
		);

		$output .= '<script type="text/javascript">';
		$output .= 'var cg = window.cg = window.cg || {};';
		$output .= 'cg.config = '.json_encode($config).';';
		$output .= 'cg.i18n = cg.i18n || {};';
		$output .= 'cg.i18n.default = '.json_encode($lines).';';
		$output .= '</script>';
		return $output;
	}
}

// ------------------------------------------------------------------------
endif; // End of class Admin_theme.
// ------------------------------------------------------------------------

// Initialize class.
$Admin_theme = new Admin_theme();