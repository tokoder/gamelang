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
 * Packages controller.
 *
 * Because we dropped the "Packages", we use this controller instead.
 * This way we reduce number of packages and make this feature default.
 *
 * @subpackage 	Admin
 * @author		Tokoder Team
 */
class Packages extends CG_Controller_Admin {

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
		$this->data['page_icon']  = 'plug';
		$this->data['page_title'] = __('lang_packages');

		// Add our head string.
		add_filter('admin_head', array($this, '_admin_head'));
	}

    // -----------------------------------------------------------------------------

	/**
	 * List reports.
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		$rules[] = array(
			'field' => '_nonce',
			'label' => 'Security',
			'rules' => 'required',
		);
		$this->prep_form($rules);

		if ($this->form_validation->run() == true)
		{
			if (true !== check_nonce('bulk-update-packages'))
			{
				set_alert(__('This action did not pass our security controls'), 'error');
				redirect(admin_url('packages'));
				exit;
			}

			$action = $this->input->post('action');
			$action = str_replace('-selected', '', $action);
			$selected = $this->input->post('selected', true);
			if (empty($selected))
			{
				set_alert(sprintf(__('Unable to %s package'), $action), 'error');
				redirect(admin_url('packages'));
				exit;
			}

			if (false !== $this->packages->{$action}($selected))
			{
				set_alert(sprintf(__('Selected package successfully %s'), $action), 'success');
				redirect(admin_url('packages'));
				exit;
			}

			set_alert(sprintf(__('Unable to %s package'), $action), 'error');
			redirect(admin_url('packages'));
			exit;
		}

		// Filter displayed packages.
		$filter = $this->input->get('status');
		if ( ! in_array($filter, array('active', 'inactive')))
		{
			$filter = null;
		}

		// Let's get our packages.
		$packages = $this->router->list_packages(true);
		foreach ($packages as $folder => &$p)
		{
			if (('active' === $filter && ! $p['enabled'])
				OR ('inactive' === $filter && $p['enabled']))
			{
				unset($packages[$folder]);
				continue;
			}

			// add details.
			$p['details'] = array();

			if ( ! empty($p['version'])) {
				$p['details'][] = sprintf(__('Version: %s'), $p['version']);
			}
			if ( ! empty($p['author'])) {
				$author = (empty($p['author_uri']))
					? $p['author']
					: sprintf(
						__('<a href="%s" target="_blank" rel="nofollow">%s</a>'),
						$p['author_uri'],
						$p['author']
					);
				$p['details'][] = sprintf(__('By %s'), $author);
			}
			if ( ! empty($p['license'])) {
				$license = empty($p['license_uri'])
					? $p['license']
					: sprintf(
						__('<a href="%s" target="_blank" rel="nofollow">%s</a>'),
						$p['license_uri'],
						$p['license']
					);
				$p['details'][] = sprintf(__('License: %s'), $license);
				// Reset license.
				$license = null;
			}
			if ( ! empty($p['package_uri'])) {
				$p['details'][] = sprintf(
					__('<a href="%s" target="_blank" rel="nofollow">%s</a>'),
					$p['package_uri'],
					__('lang_website')
				);
			}
			if ( ! empty($p['author_email'])) {
				$p['details'][] = sprintf(
					__('<a href="mailto:%s?subject=%s" target="_blank" rel="nofollow">%s</a>'),
					$p['author_email'],
					rawurlencode('Support: '.$p['name']),
					__('Support')
				);
			}

			// Add package actions.
			$p['actions'] = array();

			if (true === $p['enabled'])
			{
				$p['actions'][] = html_tag('button', array(
					'type' => 'button',
					'data-endpoint' => esc_url(nonce_admin_url(
						'packages?action=deactivate&package='.$folder,
						'package-deactivate_'.$folder
					)),
					'class' => 'btn btn-warning btn-sm btn-icon package-deactivate ms-2',
					'aria-label' => sprintf(__('Deactivate %s'), $p['name']),
				), fa_icon('times').__('lang_deactivate'));
			}
			else
			{
				$p['actions'][] = html_tag('button', array(
					'type' => 'button',
					'data-endpoint' => esc_url(nonce_admin_url(
						'packages?action=activate&package='.$folder,
						'package-activate_'.$folder
					)),
					'class' => 'btn btn-success btn-sm btn-icon package-activate ms-2',
					'aria-label' => sprintf(__('Activate %s'), $p['name']),
				), fa_icon('check').__('lang_activate'));
			}

			// add button settings
			if (true === $p['enabled'] && true === $p['has_setting'])
			{
				$p['actions'][] = html_tag('a', array(
					'href'  => admin_url('setting/'.$folder),
					'class' => 'btn btn-secondary btn-sm btn-icon ms-2',
					'aria-label' => sprintf(__('Settings %s'), $p['name']),
				), fa_icon('cogs').__('lang_settings'));
			}

			// add button delete
			if (true !== $p['enabled'])
			{
				$p['actions'][] = html_tag('button', array(
					'type' => 'button',
					'data-endpoint' => esc_url(nonce_admin_url(
						'packages?action=delete&package='.$folder,
						'package-delete_'.$folder
					)),
					'class' => 'btn btn-danger btn-sm btn-icon package-delete ms-2',
					'aria-label' => sprintf(__('Delete %s'), $p['name']),
				), fa_icon('trash').__('lang_delete'));
			}

			$headers = $this->router->package_header($folder);
			if ('active' !== $filter && $headers['enabled']) {
				unset($packages[$folder]);
			}
			elseif($headers['enabled']){
				$p['actions'] = array();
			}
		}

		// usort($packages, function ($a, $b) {
		// 	return $b['enabled'] - $a['enabled'];
		// });

		/**
		 * Catches packages actions.
		 */
		$get_action = $this->input->get('action', true);
		$get_package = $this->input->get('package', true);

		if (($get_action && in_array($get_action, array('activate', 'deactivate', 'delete')))
			&& ($get_package && isset($packages[$get_package]))
			&& check_nonce_url("package-{$get_action}_{$get_package}")
			&& method_exists($this, '_'.$get_action))
		{
			return call_user_func_array(array($this, '_'.$get_action), array($get_package));
		}

		// Data to pass to view.
		$this->data['packages'] = $packages;
		$this->data['filter']  = $filter;

		// Set page title and render view.
		$this->themes
			->set_title(__('lang_packages'))
			->render($this->data);
    }

	// ------------------------------------------------------------------------

	/**
	 * install
	 *
	 * Method for installing packages from future server or upload ZIP packages.
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
			->set_title(__('lang_add'))
			->render($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * upload
	 *
	 * Method for uploading packages using ZIP archives.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function upload()
	{
		// We check CSRF token validity.
		if ( ! check_nonce('upload-package'))
		{
			set_alert(__('This action did not pass our security controls'), 'error');
			redirect(admin_url('packages/install'));
			exit;
		}

		// Did the user provide a valid file?
		if (empty($_FILES['packagezip']['name']))
		{
			set_alert(__('lang_ERROR_file_not_exists'), 'error');
			redirect(admin_url('packages/install'));
			exit;
		}

		// Load our file helper and make sure the "unzip_file" function exists.
		$this->load->helper('file');
		if ( ! function_exists('unzip_file'))
		{
			set_alert(__('lang_ERROR_unzip'), 'error');
			redirect(admin_url('packages/install'));
			exit;
		}

		// Load upload library.
		$this->load->library('upload', array(
			'upload_path'   => get_upload_path('temp'),
			'allowed_types' => 'zip',
		));

		// Error uploading?
		if (false === $this->upload->do_upload('packagezip')
			OR ! class_exists('ZipArchive', false))
		{
			set_alert($this->upload->display_errors('', ''), 'warning');
			redirect(admin_url('packages/install'));
			exit;
		}

		// Prepare data for later use.
		$data = $this->upload->data();

		// Catch the upload status and delete the temporary file anyways.
		$status = unzip_file($data['full_path'], APPPATH.config_item('package_folder'));
		@unlink($data['full_path']);

		// Successfully installed?
		if (true === $status)
		{
			set_alert(__('success_upload'), 'success');
			redirect(admin_url('packages'));
			exit;
		}

		// Otherwise, the theme could not be installed.
		set_alert(__('error_upload'), 'error');
		redirect(admin_url('packages/install'));
		exit;
	}

	// ------------------------------------------------------------------------
	// Packages activation, deactivate and deletion.
	// ------------------------------------------------------------------------

	/**
	 * Method for activating the given package.
	 *
	 * @access 	protected
	 * @param 	string 	$folder
	 * @return 	void
	 */
	protected function _activate($folder)
	{
		$details = package_details($folder);
		$name = $details['name'];

		if (false !== $this->packages->activate($folder))
		{
			set_alert(sprintf(__('%s package successfully activated'), $name), 'success');
			redirect(admin_url('packages'));
			exit;
		}

		set_alert(sprintf(__('Unable to activate %s package'), $name), 'error');
		redirect(admin_url('packages'));
		exit;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for deactivating the given package.
	 *
	 * @access 	protected
	 * @param 	string 	$folder
	 * @return 	void
	 */
	protected function _deactivate($folder)
	{
		$details = package_details($folder);
		$name = $details['name'];

		if (package_is_active($folder) && $this->packages->deactivate($folder))
		{
			set_alert(sprintf(__('%s package successfully deactivated'), $name), 'success');
			redirect(admin_url('packages'));
			exit;
		}

		set_alert(sprintf(__('Unable to deactivate %s package'), $name), 'error');
		redirect(admin_url('packages'));
		exit;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for deleting the given package.
	 *
	 * @access 	protected
	 * @param 	string 	$folder
	 * @return 	void
	 */
	protected function _delete($folder)
	{
		$details = package_details($folder);
		$name = $details['name'];

		if (package_is_active($folder))
		{
			set_alert(sprintf(__('You must disable the %s package before deleting it'), $name), 'error');
			redirect(admin_url('packages'));
			exit;
		}

		function_exists('directory_delete') OR $this->load->helper('directory');

		if (false !== directory_delete($details['full_path']))
		{
			set_alert(sprintf(__('%s package successfully delete'), $name), 'success');
			redirect(admin_url('packages'));
			exit;
		}

		set_alert(sprintf(__('Unable to delete %s package'), $name), 'error');
		redirect(admin_url('packages'));
		exit;
	}

	// ------------------------------------------------------------------------
	// Private methods.
	// ------------------------------------------------------------------------

	/**
	 * Admin subheading.
	 * @access 	protected
	 * @param 	none
	 * @return	 void
	 */
	protected function _subhead()
	{
		// Displaying buttons depending on the page we are on.
		$method = $this->router->fetch_method();

		switch ($method)
		{
			// Case of packages install page.
			case 'install':
				$this->data['page_title'] = __('lang_add');

				// Subhead.
				add_action('admin_subhead', function() {

					// Upload package button.
					echo html_tag('button', array(
						'role' => 'button',
						'class' => 'btn btn-success btn-sm btn-icon me-2',
						'data-bs-toggle' => 'collapse',
						'data-bs-target' => '#package-install'
					), fa_icon('upload').__('lang_upload'));

					// Back button.
					$this->_btn_back('packages');

				});
				break;

			// Case of package's settings page.
			case 'settings':
				$details = package_details($this->uri->segment(4));
				$this->data['page_title'] = sprintf(__('lang_settings_name_%s'), $details['name']);

				add_action('admin_subhead', function() {
					$this->_btn_back('packages');
				}, 0);
				break;

			// Main page.
			default:
				add_action('admin_subhead', function() {
					$folder_packages = $this->router->list_packages();
					$active_packages = $this->options->get('active_packages');
					$filter          = $this->input->get('status');

					$all      = count($folder_packages);
					$active   = count($active_packages->value);
					$inactive = $all - $active;

					// Upload new package.
					echo html_tag('a', array(
						'href' => admin_url('packages/install'),
						'class' => 'btn btn-success btn-sm btn-icon'
					), fa_icon('plus-circle').__('lang_add')),

					// Filters toolbar.
					'<div class="btn-group ms-2" role="group">',

						// All packages.
						html_tag('a', array(
							'href'  => admin_url('packages'),
							'class' => 'btn btn-sm btn-'.($filter ? 'outline-secondary' : 'secondary'),
						), sprintf(__('filter_all_%s'), $all)),

						// Active packages.
						html_tag('a', array(
							'href'  => admin_url('packages?status=active'),
							'class' => 'btn btn-sm btn-'.('active' === $filter ? 'secondary' : 'outline-secondary'),
						), sprintf(__('filter_active_%s'), $active)),

						// Inactive packages.
						html_tag('a', array(
							'href'  => admin_url('packages?status=inactive'),
							'class' => 'btn btn-sm btn-'.('inactive' === $filter ? 'secondary' : 'outline-secondary'),
						), sprintf(__('filter_inactive_%s'), $inactive)),

					'</div>';
				});
				break;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * _reports_admin_head
	 *
	 * Add some JS lines.
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	string
	 */
	public function _admin_head($output)
	{
		$lines = array(
			'activate'   => __('confirm_activate'),
			'deactivate' => __('confirm_deactivate'),
			'delete'     => __('confirm_delete'),
		);
		$output .= '<script type="text/javascript">';
		$output .= 'cg.i18n.packages = '.json_encode($lines).';';
		$output .= '</script>';
		return $output;
	}
}