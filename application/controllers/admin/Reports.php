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
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings Class
 *
 * @subpackage 	Admin
 * @author		Tokoder Team
 */
class Reports extends CG_Controller_Admin {

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

		if ( ! $this->auth->is_admin())
		{
			set_alert(__('lang_error_permission'), 'error');
			redirect('');
			exit;
		}

		// Default page title and icon.
		$this->data['page_icon']  = 'bar-chart';
		$this->data['page_title'] = __('lang_reports');

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
		parse_str($_SERVER['QUERY_STRING'], $get);

		// Custom $_GET appended to pagination links and WHERE clause.
		$_get  = null;
		$where = null;

		// Filtering by package, controller or method?
		foreach (array('package', 'controller', 'method') as $filter)
		{
			if (isset($get[$filter]))
			{
				$_get[$filter]  = $get[$filter];
				$where[$filter] = strval(xss_clean($get[$filter]));
			}
		}

		// We cannot search by method :D.
		if (isset($where['method'])
			&& ( ! isset($where['controller']) OR empty($where['controller'])))
		{
			unset($where['method']);
		}

		// Filtering by user ID?
		if (isset($get['user']))
		{
			$_get['user']     = $get['user'];
			$where['user_id'] = intval(xss_clean($get['user']));
		}

		// Build the query appended to pagination links.
		(empty($_get)) OR $_get = '?'.http_build_query($_get);

		// Load pagination library and set configuration.
		$this->load->library('pagination');
		$config['base_url'] = $config['first_ul'] = admin_url('reports'.$_get);
		$config['per_page'] = config_item('per_page');

		// Count total rows.
		$config['total_rows'] = $this->activities->count($where);

		// Initialize pagination.
		$this->pagination->initialize($config);

		// Create pagination links.
		$this->data['pagination'] = $this->pagination->create_links();

		// Prepare the offset and limit users to get reports.
		$limit  = $config['per_page'];
		$offset = (isset($get['page'])) ? $config['per_page'] * ($get['page'] - 1) : 0;

		// Retrieve reports.
		$reports = $this->activities->get_many($where, null, $limit, $offset);

		// Loop through reports to complete data.
		if ($reports)
		{
			foreach ($reports as &$report)
			{
				// User anchor
				if (false !== $report->user)
				{
					$report->user_anchor = admin_anchor(
						'reports?user='.$report->user_id.(isset($get['package']) ? '&package='.$get['package'] : ''),
						$report->user->full_name
					);
				}

				// Packages anchor
				$report->package_anchor = '';
				if ( ! empty($report->package))
				{
					$report->package_anchor = html_tag('a', array(
						'href' => admin_url('reports/'.$report->package),
						'class' => 'btn btn-outline-secondary btn-sm btn-icon',
					), fa_icon('link').$report->package);
				}

				// Controller anchor
				$report->controller_anchor = '';
				if ( ! empty($report->controller))
				{
					$controller_url = (empty($report->package))
						? admin_url('reports?controller='.$report->controller)
						: admin_url("reports/{$report->package}?controller={$report->controller}");
					$report->controller_anchor = html_tag('a', array(
						'href' => $controller_url,
						'class' => 'btn btn-outline-secondary btn-sm btn-icon',
					), fa_icon('link').$report->controller);
				}

				// Method anchor
				$report->method_anchor = '';
				if ( ! empty($report->method))
				{
					if ( ! empty($report->package))
					{
						$method_url = (empty($report->controller))
							? admin_url("reports/{$report->package}?method=$report->method")
							: admin_url("reports/{$report->package}?controller={$report->controller}&method={$report->method}");
					}
					else
					{
						$method_url = (empty($report->controller))
							? admin_url('reports?method='.$report->method)
							: admin_url("reports?controller={$report->controller}&method={$report->method}");
					}
					$report->method_anchor = html_tag('a', array(
						'href' => $method_url,
						'class' => 'btn btn-outline-secondary btn-sm btn-icon',
						'rel' => 'tooltip',
						'title' => $report->activity,
					), fa_icon('info-circle').$report->method);
				}

				// IP location link.
				$report->ip_address = anchor(
					'https://www.iptolocation.net/trace-'.$report->ip_address,
					$report->ip_address,
					'target="_blank"'
				);
			}
		}

		// Add reports to view.
		$this->data['reports'] = $reports;

		// Set page title and render view.
		$this->themes
			->set_title(__('lang_ACTIVITY_LOG'))
			->render($this->data);
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
		// We add the back button only if there are $_GET params.
		empty($_GET) OR  add_action('admin_subhead', function() {
			$this->_btn_back();
		});
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
		$output .= '<script type="text/javascript">';
		$output .= 'cg.i18n.reports = cg.i18n.reports || {};';
		$output .= 'cg.i18n.reports.delete = "'.__('confirm_delete').'";';
		$output .= '</script>';

		return $output;
	}
}