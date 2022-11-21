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
		add_action('admin_index_stats', array($this, '_stats'), 0);

		// Set page title and render view.
		$this->themes
			->set_title(__('lang_panel'))
			->render($this->data);
	}

    // -----------------------------------------------------------------------------

	/**
	 * Index Page for this controller.
	 */
	public function page_mising()
	{
		$this->themes
			->set_view('welcome/404')
			->render();
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
		$output = '<div class="row">';
		$output .= '<div class="col-xs-6 col-sm-6 col-md-3">';

		// Users count.
		$users_count = $this->users->count();
		if ($users_count > 0)
		{
			$boxes[] = info_box(
				$users_count,
				__('lang_users'),
				'users',
				admin_url('users'),
				'light'
			);
		}

		// Themes count.
		$themes_count = count($this->themes->list_themes());
		if ($themes_count > 0)
		{
			$boxes[] = info_box(
				$themes_count,
				__('lang_themes'),
				'paint-brush',
				admin_url('themes'),
				'danger'
			);
		}

		// Packages count.
		$packages_count = count($this->router->list_packages());
		if ($packages_count > 0)
		{
			$boxes[] = info_box(
				$packages_count,
				__('lang_packages'),
				'plug',
				admin_url('packages'),
				'warning'
			);
		}

		// Languages count.
		$langs_count = count($this->config->item('languages'));
		if ($langs_count >= 1)
		{
			$boxes[] = info_box(
				$langs_count,
				__('lang_languages'),
				'globe',
				admin_url('languages'),
				'success'
			);
		}

		$output .= implode('</div><div class="col-xs-6 col-sm-6 col-md-3">', $boxes);

		$output .= '</div>';
		$output .= '</div>';
		echo $output;
	}
}