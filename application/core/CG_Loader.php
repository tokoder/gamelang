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
}