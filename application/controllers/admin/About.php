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
class About extends CG_Controller_Admin
{
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
	}

	// -----------------------------------------------------------------------------

    /**
     * index
     *
     * Method for displaying system information.
     */
    public function index()
    {
        // System information.
        $this->data['info'] = array(
            'php_built_on'        => php_uname(),
            'php_version'         => phpversion(),
            'database_type'       => $this->db->platform(),
            'database_version'    => $this->db->version(),
            'web_server'          => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : getenv('SERVER_SOFTWARE'),
            'app_version'         => CG_VERSION,
            'codeigniter_version' => CI_VERSION,
            'user_agent'          => $this->agent->agent_string(),
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
}