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
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Controller
 *
 * @subpackage 	Admin
 * @author		Tokoder Team
 */
class Welcome extends CG_Controller_Admin
{
	/**
	 * Class constructor.
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Main admin panel page.
	 * @access 	public
	 * @return 	void
	 */
	public function index()
	{
		// EDIT THIS METHOD TO SUIT YOUR NEEDS.
		if ($this->auth->is_admin()) {
			add_action('admin_index_stats', array($this, '_stats'), 0);
		}

		// Set page title and render view.
		$this->themes
			->render($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * Collect all regular status.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function _stats()
	{
		$this->load->helper('number');

		$output = '<div class="row">';
		$output .= '<div class="col-xs-6 col-sm-6 col-md-3">';

		// Users count.
		$users_count = $this->users->count();
		$boxes[] = info_box(
			number_format_short($users_count),
			__('lang_users'),
			'users',
			admin_url('users'),
			'primary'
		);

		// Languages count.
		$langs_count = count(config_item('languages'));
		$boxes[] = info_box(
			number_format_short($langs_count),
			__('lang_languages'),
			'globe',
			admin_url('languages'),
			'success'
		);

		// Packages count.
		$packages_count = count($this->router->list_packages());
		$boxes[] = info_box(
			number_format_short($packages_count),
			__('lang_packages'),
			'boxes',
			admin_url('packages'),
			'warning'
		);

		// Themes count.
		$themes_count = count($this->themes->list_themes());
		$boxes[] = info_box(
			number_format_short($themes_count),
			__('lang_themes'),
			'paint-brush',
			admin_url('themes'),
			'danger'
		);

		$output .= implode('</div><div class="col-xs-6 col-sm-6 col-md-3">', $boxes);

		$output .= '</div>';
		$output .= '</div>';
		echo $output;
	}
}