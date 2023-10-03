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

require_once(APPPATH.'libraries/CG_Hooks.php');

/**
 * CG_Hooks Class
 *
 * This file extends CI_Hooks class in order to make hooks available
 * to be loaded from the app folder.
 *
 * @category 	Core
 * @author		Tokoder Team
 */
class CG_Hooks extends CI_Hooks
{
	/**
	 * Class constructor
	 * @return 	void
	 */
	public function __construct()
	{
		$CFG =& load_class('Config', 'core');
		log_message('info', 'Hooks Class Initialized');

		// If hooks are not enabled in the config file
		// there is nothing else to do
		if ($CFG->item('enable_hooks') === FALSE)
		{
			return;
		}

		// Grab the "hooks" definition file.
		$file = 'hooks';
		foreach (array($file, ENVIRONMENT.DIRECTORY_SEPARATOR.$file, 'gamelang'.DIRECTORY_SEPARATOR.$file) as $location)
		{
			$file_path = APPPATH.'config/'.$location.'.php';

			if ( ! file_exists($file_path))
			{
				continue;
			}

			include($file_path);
		}

		// If there are no hooks, we're done.
		if ( ! isset($hook) OR ! is_array($hook))
		{
			return;
		}

		$this->hooks =& $hook;
		$this->enabled = TRUE;
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
