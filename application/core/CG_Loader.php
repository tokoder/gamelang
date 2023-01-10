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
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CG_Loader Class
 *
 * Loads framework components.
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class CG_Loader extends CI_Loader
{
	/**
	 * Class constructor
	 *
	 * Sets component load paths, gets the initial output buffering level.
	 *
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();
	}

	// ------------------------------------------------------------------------

	/**
	 * Internal CI Data Loader
	 * @access 	protected
	 * @param	array	$_ci_data	Data to load
	 * @return 	void
	 */
	protected function _ci_load($_ci_data)
	{
		// See if it's inside a package!
		if (isset($_ci_data['_ci_view'])
			&& list($package, $class) = get_instance()->router->valid_package($_ci_data['_ci_view']))
		{
			$package_path = get_instance()->router->package_path($package);
			$this->add_package_path($package_path);
		}

		// Add Themes Path
		$this->_ci_view_paths = array(get_theme_path('/') => TRUE) + $this->_ci_view_paths;

		return parent::_ci_load($_ci_data);
	}

	// --------------------------------------------------------------------

	/**
	 * CI Autoloader
	 *
	 * Loads component listed in the config/autoload.php file.
	 *
	 * @used-by	CI_Loader::initialize()
	 * @return	void
	 */
	protected function _ci_autoloader()
	{
		$file = 'autoload';
		foreach (array($file, ENVIRONMENT.DIRECTORY_SEPARATOR.$file, 'gamelang'.DIRECTORY_SEPARATOR.$file) as $location)
		{
			$file_path = APPPATH.'config/'.$location.'.php';

			if ( ! file_exists($file_path))
			{
				continue;
			}

			include($file_path);
		}

		if ( ! isset($autoload))
		{
			return;
		}

		// Autoload packages
		if (isset($autoload['packages']))
		{
			foreach ($autoload['packages'] as $package_path)
			{
				$this->add_package_path($package_path);
			}
		}

		// Load any custom config file
		if (count($autoload['config']) > 0)
		{
			foreach ($autoload['config'] as $val)
			{
				$this->config($val);
			}
		}

		// Autoload helpers and languages
		foreach (array('helper', 'language') as $type)
		{
			if (isset($autoload[$type]) && count($autoload[$type]) > 0)
			{
				$this->$type($autoload[$type]);
			}
		}

		// Autoload drivers
		if (isset($autoload['drivers']))
		{
			$this->driver($autoload['drivers']);
		}

		// Load libraries
		if (isset($autoload['libraries']) && count($autoload['libraries']) > 0)
		{
			// Load the database driver.
			if (in_array('database', $autoload['libraries']))
			{
				$this->database();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
			}

			// Load all other libraries
			$this->library($autoload['libraries']);
		}

		// Autoload models
		if (isset($autoload['model']))
		{
			$this->model($autoload['model']);
		}
	}
}