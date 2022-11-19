<?php
/**
 * CodeIgniter Gamelang
 *
 * An open source codeigniter management system
 *
 * @package 	CodeIgniter Gamelang
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link		https://github.com/tokoder/gamelang
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
		add_filter('admin_head', array($this, 'globals'), 0);
		add_filter('admin_head', array($this, 'admin_head'), 99);

		// Views files.
		add_filter( 'admin_views_path', function () {
			return get_theme_path( 'templates/' );
		} );
    }

	/**
	 * globals
	 *
	 * Method for adding JS global before anything else.
	 *
	 * @access 	public
	 * @param 	string 	$output 	StyleSheets output.
	 * @return 	void
	 */
	public function globals($output)
	{
		// Default configuration.
		$config = array(
			'siteURL'     => site_url(),
			'baseURL'     => base_url(),
			'adminURL'    => admin_url(),
			'currentURL'  => current_url(),
			'ajaxURL'     => ajax_url(),
			'tokenName'   => config_item('csrf_token_name'),
			'tokenCookie' => config_item('csrf_cookie_name'),
			'lang'        => get_instance()->lang->lang_lists(get_instance()->session->language),
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
		$output .= 'var gamelang = window.gamelang = window.gamelang || {};';
		$output .= ' gamelang.config = '.json_encode($config).';';
		$output .= ' gamelang.i18n = gamelang.i18n || {};';
		$output .= ' gamelang.i18n.default = '.json_encode($lines).';';
		$output .= '</script>';

		return $output;
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
		return $output;
	}

}

// ------------------------------------------------------------------------
endif; // End of class Admin_theme.
// ------------------------------------------------------------------------

// Initialize class.
$Admin_theme = new Admin_theme();