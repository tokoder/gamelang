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
 * Load Controller Class
 *
 * It handles render resource
 *
 * @subpackage 	Controllers
 * @author		Tokoder Team
 */
class Welcome extends CG_Controller
{
	/**
	 * __construct
	 *
	 * Simply call parent's constructor and make sure the request is only
	 * a GET request.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// -----------------------------------------------------------------------------

	public function _remap($method, $params = array())
	{
		// Call the method.
		return call_user_func_array(array($this, 'index'), $params);
	}

	// -----------------------------------------------------------------------------

	public function index()
	{
		$file = array_slice($this->uri->segment_array(), 1);
		$file = implode('/', $file);

		if ($file == null ) {
			show_404();
		}

		$ext = pathinfo($file, PATHINFO_EXTENSION);

		// Temporary output.
		$out = '';

		// Hold the path to the file.
		$file_path = normalize_path(APPPATH.$file);

		// The file does not exist?
		if ( ! file_exists($file_path))
		{
			return $out;
		}

		$out .= file_get_contents($file_path);

		// Still no output?
		if ('' === $out)
		{
			exit();
		}

		// We render our final output.
		$this->output
			->set_content_type($ext)
			->set_output($out);
	}
}