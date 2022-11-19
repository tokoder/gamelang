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
 * CG_Hooks Class
 *
 * This file extends CI_Hooks class in order to make hooks available
 * to be loaded from the app folder.
 *
 * @category 	Core
 * @author		Tokoder Team
 */
require_once(APPPATH.'libraries/CG_Hooks.php');

class CG_Hooks extends CI_Hooks
{
	/**
	 * Class constructor
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// ------------------------------------------------------------------------

	/**
	 * call_hook
	 *
	 * Calls a particular hook. Added for app in order to execute action
	 * using the package class.
	 *
	 * @access 	public
	 * @param 	string 	$which 	The hook's name.
	 * @return 	bool 	true on success, else false.
	 */
	public function call_hook($which = '')
	{
		// We do any action first.
		do_action($which);

		// Then we let the parent do the rest.
		return parent::call_hook($which);
	}
}
