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
 * Settings Class
 *
 * @subpackage 	Admin
 * @author		Tokoder Team
 */
class Settings extends CG_Controller_Admin
{
	/**
	 * Array of options tabs and their display order.
	 * @var array
	 */
	protected $_tabs = array(

		// Global Settings.
		'general' => array(
			'site_name',
			'site_description',
			'site_keywords',
			'site_title',
			'site_author',
			'per_page',
			'google_analytics_id',
			'google_site_verification',
		),

		// User Settings.
		'users'   => array(
			'allow_registration',
			'email_activation',
			'manual_activation',
			'login_type',
			'allow_multi_session',
			'use_gravatar',
		),

		// Email Settings.
		'email'   => array(
			'admin_email',
			'server_email',
			'mail_protocol',
			'sendmail_path',
			'smtp_host',
			'smtp_port',
			'smtp_crypto',
			'smtp_user',
			'smtp_pass',
		),

		// Captcha Settings.
		'captcha'   => array(
			'use_captcha',
			'use_recaptcha',
			'recaptcha_site_key',
			'recaptcha_private_key',
		),

		// Upload Settings.
		'upload'  => array(
			'upload_path',
			'allowed_types',
			'max_size',
			'min_width',
			'min_height',
			'max_width',
			'max_height',
		),
	);

	/**
	 * __construct
	 *
	 * Load needed files.
	 */
	public function __construct()
	{
		parent::__construct();

		// Default page title and icon.
		$this->data['page_icon']  = 'sliders';
		$this->data['page_title'] = __('lang_settings');
	}

	// ------------------------------------------------------------------------

	/**
	 * index
	 *
	 * Method for updating site global settings.
	 */
	public function index()
	{
		// Let's see what tab we are on.
		$tab = $this->input->get('tab', true);
		if (empty($tab) OR true !== array_key_exists($tab, $this->_tabs))
		{
			$tab = 'general';
		}

		if ('general' !== $tab)
		{
			$this->data['page_title'] = sprintf(
				__('lang_name'),
				__('lang_tab_'.strtoupper($tab))
			);
		}

		list($this->data['inputs'], $rules) = $this->_prep_settings($tab);

		// Set validation rules
		$this->prep_form($rules, '#settings-'.$tab);
		$this->data['tab'] = $tab;

		// Prepare form action.
		$action = '';
		('general' !== $tab) && $action .= '?tab='.$tab;
		$this->data['action'] = 'admin/settings'.$action;

		if ($this->form_validation->run() == false)
		{
			$this->themes
				->set_title($this->data['page_title'])
				->render($this->data);
		}
		else
		{
			$this->_save_settings($this->data['inputs'], $tab);
			redirect('admin/settings'.$action, 'refresh');
			exit;
		}

	}

	// -----------------------------------------------------------------------------

    /**
     * sysinfo
     *
     * Method for displaying system information.
     */
    public function sysinfo()
    {
        // System information.
        $this->data['info'] = array(
            'php_built_on'     => php_uname(),
            'php_version'      => phpversion(),
            'database_type'    => $this->db->platform(),
            'database_version' => $this->db->version(),
            'web_server'       => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : getenv('SERVER_SOFTWARE'),
            'app_version' 	   => CI_VERSION,
            'user_agent'       => $this->agent->agent_string(),
        );

        // PHP Settings.
        $this->data['php'] = array(
            'safe_mode'          => ini_get('safe_mode') == '1',
            'display_errors'     => ini_get('display_errors') == '1',
            'short_open_tag'     => ini_get('short_open_tag') == '1',
            'file_uploads'       => ini_get('file_uploads') == '1',
            'magic_quotes_gpc'   => ini_get('magic_quotes_gpc') == '1',
            'register_globals'   => ini_get('register_globals') == '1',
            'output_buffering'   => (int) ini_get('output_buffering') !== 0,
            'open_basedir'       => ini_get('open_basedir'),
            'session.save_path'  => ini_get('session.save_path'),
            'session.auto_start' => ini_get('session.auto_start'),
            'disable_functions'  => ini_get('disable_functions'),
            'xml'                => extension_loaded('xml'),
            'zlib'               => extension_loaded('zlib'),
            'zip'                => function_exists('zip_open') && function_exists('zip_read'),
            'mbstring'           => extension_loaded('mbstring'),
            'iconv'              => function_exists('iconv'),
            'max_input_vars'     => ini_get('max_input_vars'),
        );

        // PHP Info.
        $this->data['phpinfo'] = $this->_get_phpinfo();

        // Page icon and heading.
        $this->data['page_icon'] = 'info-circle';
        $this->data['page_title'] = __('lang_system_information');

        $this->themes
            ->set_title(__('lang_system_information'))
            ->render($this->data);
    }

    // ------------------------------------------------------------------------

    /**
     * _get_phpinfo
     *
     * Method for getting PHP information.
     */
    protected function _get_phpinfo()
    {
        ob_start();
        date_default_timezone_set('UTC');
        phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
        $phpInfo = ob_get_contents();
        ob_end_clean();

        preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpInfo, $output);

        $output = preg_replace('#<table[^>]*>#', '<table class="table table-hover m-0">', $output[1][0]);
        $output = str_replace('<h2>', '<h2 class="m-0 p-2 bg-light">', $output);
        $output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
        $output = preg_replace('#<hr />#', '', $output);
        $output = str_replace('<div class="center">', '', $output);

        return $output;
    }

	// ------------------------------------------------------------------------

	/**
	 * _prep_settings
	 *
	 * Method for preparing all settings data and their form validation rules.
	 */
	protected function _prep_settings($tab = 'general')
	{
		$settings = $this->options->get_by_tab($tab);

		if (false === $settings)
		{
			return array(false, null);
		}

		if (isset($this->_tabs[$tab]) && ! empty($this->_tabs[$tab]))
		{
			$_settings = array();
			$order = array_flip($this->_tabs[$tab]);

			foreach ($settings as $index => $setting)
			{
				$_settings[$order[$setting->name]] = $setting;
			}

			if ( ! empty($_settings))
			{
				ksort($_settings);
				$settings = $_settings;
			}
		}

		// Prepare empty form validation rules.
		$rules = array();

		foreach ($settings as $option)
		{
			$data[$option->name] = array(
				'type'  => $option->field_type,
				'name'  => $option->name,
				'id'    => $option->name,
				'value' => $option->value,
			);

			if ($option->required == 1)
			{
				$data[$option->name]['required'] = 'required';
				$rules[$option->name] = array(
					'field' => $option->name,
					'label' => "lang:lang_{$option->name}",
					'rules' => 'required',
				);
			}

			if ($option->field_type == 'dropdown' && ! empty($option->options))
			{
				$data[$option->name]['options'] = array_map(function($val) {
					if (is_numeric($val))
					{
						return $val;
					}

					return (sscanf($val, 'lang:%s', $lang_val) === 1) ? __($lang_val) : $val;
				}, $option->options);

				$rules[$option->name]['rules'] .= '|in_list['.implode(',', array_keys($option->options)).']';
			}
			else
			{
				$data[$option->name]['placeholder'] = __('lang_'.$option->name);
			}
		}

		return array($data, array_values($rules));
	}

	// ------------------------------------------------------------------------

	/**
	 * _save_settings
	 *
	 * Method that handles automatically saving settings.
	 */
	protected function _save_settings($inputs, $tab = null)
	{
		// Nothing provided? Nothing to do.
		if (empty($inputs) OR (empty($tab) OR ! array_key_exists($tab, $this->_tabs)))
		{
			set_alert(__('error_csrf'), 'error');
			return false;
		}

		// Check nonce.
		if (true !== check_nonce('settings-'.$tab, false))
		{
			set_alert(__('error_csrf'), 'error');
			return false;
		}

		/**
		 * We make sure to collect all settings data from the provided
		 * $inputs array (We use their keys).
		 * Then, we loop through all elements and remove those that did
		 * not change to avoid useless update.
		 */
		$settings = $this->input->post(array_keys($inputs), true);
		foreach ($settings as $key => $val)
		{
			if (to_bool_or_serialize($inputs[$key]['value']) === $val)
			{
				unset($settings[$key]);
			}
		}

		/**
		 * If all settings were removed, we will end up with an empty
		 * array, so we simply fake it :) .. We say everything was updated.
		 */
		if (empty($settings))
		{
			set_alert(__('lang_success_update'), 'success');
			return true;
		}

		/**
		 * In case we have some left settings, we make sure to updated them
		 * one by one and stop in case one of them could not be updated.
		 */
		foreach ($settings as $key => $val)
		{
			if (false === $this->options->set_item($key, $val))
			{
				log_message('error', "Unable to update setting {$tab}: {$key}");
				set_alert(__('lang_error_update'), 'error');
				return false;
			}
		}

		set_alert(__('lang_success_update'), 'success');
		return true;
	}

	// ------------------------------------------------------------------------

	protected function _subhead()
	{
		if ('sysinfo' === $this->router->fetch_method())
		{
			return;
		}

		add_action('admin_subhead', function() {

			$tab = $this->input->get('tab', true);
			if (empty($tab) OR true !== array_key_exists($tab, $this->_tabs))
			{
				$tab = 'general';
			}

			echo '<div class="btn-group btn-group-sm">',

			html_tag('a', array(
				'href' => admin_url('settings'),
				'class' => 'btn btn-'.('general' === $tab ? 'primary' : 'outline-primary'),
			), __('lang_tab_general')),

			html_tag('a', array(
				'href' => admin_url('settings?tab=users'),
				'class' => 'btn btn-'.('users' === $tab ? 'primary' : 'outline-primary'),
			), __('lang_tab_users')),

			html_tag('a', array(
				'href' => admin_url('settings?tab=email'),
				'class' => 'btn btn-'.('email' === $tab ? 'primary' : 'outline-primary'),
			), __('lang_tab_email')),

			html_tag('a', array(
				'href' => admin_url('settings?tab=captcha'),
				'class' => 'btn btn-'.('captcha' === $tab ? 'primary' : 'outline-primary'),
			), __('lang_tab_captcha')),

			html_tag('a', array(
				'href' => admin_url('settings?tab=upload'),
				'class' => 'btn btn-'.('upload' === $tab ? 'primary' : 'outline-primary'),
			), __('lang_tab_upload')),

			'</div>';
		});
	}
}