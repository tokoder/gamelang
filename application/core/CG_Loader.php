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
		if (isset($_ci_data['_ci_view']) &&
			list($package, $class) = get_instance()->package->_is_valid($_ci_data['_ci_view']))
		{
			// package already loaded?
			$_ci_data['_ci_path'] = get_instance()->router->packages_dir($package).'/views/'.$class.'.php';

			return parent::_ci_load($_ci_data);
		}

		return parent::_ci_load($_ci_data);
	}
}