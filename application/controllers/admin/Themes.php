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
 * Themes - Admin Controller
 *
 * This controller allow admins to manage site themes.
 *
 * @subpackage 	Admin
 * @author		Tokoder Team
 */
class Themes extends CG_Controller_Admin {

	/**
	 * __construct
	 *
	 * Load needed files.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		// Default page title and icon.
		$this->data['page_icon']  = 'paint-brush';
		$this->data['page_title'] = __('lang_themes');

		// Add our head string.
		add_filter('admin_head', array($this, '_admin_head'));
	}

	// ------------------------------------------------------------------------

	/**
	 * index
	 *
	 * Method for updating site global settings.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		// Get themes stored in database and in folder.
		$themes = $this->themes->list_themes(true);

		// Format some elements before final output.
		foreach ($themes as $folder => &$t)
		{
			$t['actions'] = array();

			// Activation button.
			if ($folder !== config_item('theme'))
			{
				$t['actions'][] = html_tag('a', array(
					'href' => esc_url(nonce_admin_url(
						'themes?action=activate&theme='.$folder,
						'theme-activate_'.$folder
					)),
					'role' => 'button',
					'class' => 'btn btn-info btn-sm theme-activate me-2',
					'data-name' => $t['name'],
				), __('lang_activate'));
			}
			else {
				$t['actions'][] = html_tag('a', array(
					'href'  => admin_url('themes/settings/'.$folder),
					'class' => 'btn btn-default btn-xs btn-icon ml-2',
					'aria-label' => sprintf(__('lang_settings_%s'), $folder),
				), fa_icon('cogs').__('lang_settings'));
			}

			// Details button.
			$t['actions'][] = html_tag('a', array(
				'href'  => admin_url('themes?theme='.$folder),
				'class' => 'btn btn-primary btn-sm theme-details',
			), __('lang_DETAILS'));
		}

		/**
		 * Handle themes actions.
		 */
		$action = $this->input->get('action', true);
		$theme = $this->input->get('theme', true);

		if (($action && in_array($action, array('activate', 'details', 'delete')))
			&& ($theme && isset($themes[$theme]))
			&& check_nonce_url("theme-{$action}_{$theme}")
			&& method_exists($this, '_'.$action))
		{
			return call_user_func_array(array($this, '_'.$action), array($theme));
		}

		// Displaying a single theme details?
		if (null !== $theme && isset($themes[$theme]))
		{
			$get   = $theme;
			$theme = $themes[$theme];

			// The theme has a URI?
			$theme['name_uri'] = $theme['name'];
			if ( ! empty($theme['theme_uri'])) {
				$theme['name_uri'] = html_tag('a', array(
					'href'   => $theme['theme_uri'],
					'target' => '_blank',
					'rel'    => 'nofollow',
				), $theme['name']);
			}

			// Does the license have a URI?
			if ( ! empty($theme['license_uri'])) {
				$theme['license'] = html_tag('a', array(
					'href'   => $theme['license_uri'],
					'target' => '_blank',
					'rel'    => 'nofollow',
				), $theme['license']);
			}

			// Does the author have a URI?
			if ( ! empty($theme['author_uri'])) {
				$theme['author'] = html_tag('a', array(
					'href'   => $theme['author_uri'],
					'target' => '_blank',
					'rel'    => 'nofollow',
				), $theme['author']);
			}

			// Did the user provide a support email address?
			if ( ! empty($theme['author_email'])) {
				$theme['author_email'] = html_tag('a', array(
					'href'   => "mailto:{$theme['author_email']}?subject=".rawurlencode("Theme Support: {$theme['name']}"),
					'target' => '_blank',
					'rel'    => 'nofollow',
				), $theme['author_email']);
			}

			// Actions buttons.
			$theme['action_activate'] = null;
			$theme['action_delete'] = null;
			if (true !== $theme['enabled'])
			{
				$theme['action_activate'] = html_tag('a', array(
					'href' => esc_url(nonce_admin_url(
						'themes?action=activate&theme='.$get,
						'theme-activate_'.$get
					)),
					'role' => 'button',
					'data-name' => $get,
					'class' => 'btn btn-primary btn-sm theme-activate',
				), __('lang_activate'));

				$theme['action_delete'] = html_tag('a', array(
					'href' => esc_url(nonce_admin_url(
						'themes?action=delete&theme='.$get,
						'theme-delete_'.$get
					)),
					'role' => 'button',
					'data-name' => $get,
					'class' => 'btn btn-danger btn-sm theme-delete float-end',
				), __('lang_delete'));
			}
		}
		else
		{
			$theme = null;
		}

		// Pass all variables to view.
		$this->data['themes'] = $themes;
		$this->data['theme']  = $theme;

		// Set page title and render view.
		$this->themes
			->set_title(__('lang_themes'))
			->render($this->data);

	}

	// ------------------------------------------------------------------------

	/**
	 * Display theme's settings page.
	 * @access 	public
	 * @param 	string 	$theme 	the theme's name.
	 * @return 	void
	 */
	public function settings($theme = null)
	{
		// Get the theme first.
		$theme = $this->themes->get_theme_details($theme);

		// The theme does not exists?
		if ( ! $theme)
		{
			set_alert(__('themes_ERROR_theme_MISSING'), 'error');
			redirect('admin/themes');
			exit;
		}

		// Disabled? It needs to be enabled first.
		if ( ! $theme['enabled'])
		{
			set_alert(__('themes_ERROR_SETTINGS_DISABLED'), 'error');
			redirect('admin/themes');
			exit;
		}

		require_once($theme['full_path'].'/functions.php');

		// It does not have a settings page?
		if ( ! has_filter('theme_settings_'.$theme['name']))
		{
			set_alert(__('themes_ERROR_SETTINGS_MISSING'), 'error');
			redirect('admin/themes');
			exit;
		}

		// Add link to theme's help and donation.
		if ( ! empty($theme['theme_uri']))
		{
			$this->data['page_help'] = $theme['theme_uri'];
		}

		if ( ! empty($theme['donation_uri']))
		{
			$this->data['page_donate'] = $theme['donation_uri'];
		}

		$this->data['page_title'] = sprintf(__('themes_%s_SETTINGS'), $theme['name']);
		$this->data['theme']      = $theme;

		// Set page title and render view.
		$this->themes
			->set_title($this->data['page_title'])
			->render($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * install
	 *
	 * Method for installing themes from future server or upload ZIP themes.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function install()
	{
		// We prepare form validation.
		$this->prep_form();

		// Set page title and load view.
		$this->themes
			->set_title(__('lang_INSTALL'))
			->render($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * upload
	 *
	 * Method for uploading themes using ZIP archives.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function upload()
	{
		// We check CSRF token validity.
		if (true !== check_nonce('upload-theme'))
		{
			set_alert(__('error_csrf'), 'error');
			redirect(admin_url('themes/install'));
			exit;
		}

		// Did the user provide a valid file?
		if (empty($_FILES['themezip']['name']))
		{
			set_alert(__('lang_ERROR_file_not_exists'), 'error');
			redirect(admin_url('themes/install'));
			exit;
		}

		// Load our file helper and make sure the "unzip_file" function exists.
		$this->load->helper('file');
		if ( ! function_exists('unzip_file'))
		{
			set_alert(__('lang_ERROR_unzip'), 'error');
			redirect(admin_url('themes/install'));
			exit;
		}

		// Load upload library.
		$this->load->library('upload', array(
			'upload_path'   => get_upload_path('temp'),
			'allowed_types' => 'zip',
		));

		// Error uploading?
		if (false === $this->upload->do_upload('themezip')
			OR ! class_exists('ZipArchive', false))
		{
			set_alert($this->upload->display_errors('', ''), 'warning');
			redirect(admin_url('themes/install'));
			exit;
		}

		// Prepare data for later use.
		$data = $this->upload->data();

		// Catch the upload status and delete the temporary file anyways.
		$status = unzip_file($data['full_path'], APPPATH.'themes/');
		@unlink($data['full_path']);

		// Successfully installed?
		if (true === $status)
		{
			set_alert(__('lang_SUCCESS_UPLOAD'), 'success');
			redirect(admin_url('themes'));
			exit;
		}

		// Otherwise, the theme could not be installed.
		set_alert(__('lang_ERROR_UPLOAD'), 'error');
		redirect(admin_url('themes/install'));
		exit;
	}

	// ------------------------------------------------------------------------
	// Quick-access methods.
	// ------------------------------------------------------------------------

	/**
	 * Method for activating the given themes.
	 *
	 * @access 	protected
	 * @param 	string 	$folder
	 * @return 	void
	 */
	protected function _activate($folder)
	{
		$themes = $this->themes->list_themes(true);
		$db_theme  = $this->options->get('theme');

		// Successfully updated?
		if (false !== $db_theme->update('value', $folder))
		{
			// Delete other themes stored options.
			foreach (array_keys($themes) as $_name)
			{
				if ($folder !== $_name)
				{
					$this->options->delete('theme_images_'.$_name);
					$this->options->delete('theme_menus_'.$_name);
				}
			}

			set_alert(sprintf(__('lang_success_activate_%s'), $folder), 'success');
			redirect(admin_url('themes'));
			exit;
		}

		set_alert(sprintf(__('lang_error_activate_%s'), $folder), 'error');
		redirect(admin_url('themes'));
		exit;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for deleting the given theme.
	 *
	 * @access 	protected
	 * @param 	string 	$folder
	 * @return 	void
	 */
	protected function _delete($folder)
	{
		$themes = $this->themes->list_themes(true);
		$db_theme  = $this->options->get('theme');

		// We cannot delete the current theme.
		if ($folder === $db_theme->value)
		{
			set_alert(__('lang_error_delete_active'), 'error');
			redirect(admin_url('themes'));
			return;
		}

		unset($themes[$folder]);

		function_exists('directory_delete') OR $this->load->helper('directory');

		if (false !== directory_delete($this->themes->themes_path($folder))
			&& false !== $db_theme->update('value', $folder))
		{
			delete_option('theme_images_'.$folder);
			delete_option('theme_menus_'.$folder);

			set_alert(sprintf(__('lang_success_delete_%s'), $folder), 'success');
			redirect(admin_url('themes'));
			exit;
		}

		set_alert(sprintf(__('lang_error_delete_%s'), $folder), 'error');
		redirect(admin_url('themes'));
		exit;
	}

	// ------------------------------------------------------------------------
	// Private methods.
	// ------------------------------------------------------------------------

	/**
	 * Method for adding some JS lines to the head part.
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	string
	 */
	public function _admin_head($output)
	{
		// Add lines.
		$lines = array(
			'activate' => __('confirm_activate'),
			'delete'   => __('confirm_delete'),
			'install'  => __('confirm_INSTALL'),
			'upload'   => __('confirm_UPLOAD'),
		);
		$output .= '<script type="text/javascript">';
		$output .= 'cg.i18n.themes = '.json_encode($lines).';';
		$output .= '</script>';

		return $output;
	}

	// ------------------------------------------------------------------------

	/**
	 * _subhead
	 *
	 * Add some buttons to dashboard subhead section.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	protected function _subhead()
	{
		add_action('admin_subhead', function() {

			if ('install' === $this->router->fetch_method())
			{
				// Upload theme button.
				echo html_tag('button', array(
					'role' => 'button',
					'class' => 'btn btn-success btn-sm btn-icon me-2',
					'data-bs-toggle' => 'collapse',
					'data-bs-target' => '#theme-install'
				), fa_icon('upload').__('lang_upload'));

				// Back button.
				$this->_btn_back();
			}
			elseif ('index' === $this->router->fetch_method())
			{
				// Add theme button.
				echo html_tag('a', array(
					'href' => admin_url('themes/install'),
					'class' => 'btn btn-success btn-sm btn-icon'
				), fa_icon('plus-circle').__('lang_add'));
			}
			else {
				// Back button.
				$this->_btn_back();
			}
		});
	}

}