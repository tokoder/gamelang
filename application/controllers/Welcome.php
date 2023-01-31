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
 * Welcome Controller Class
 *
 * It handles render front-end
 *
 * @subpackage 	Controllers
 * @author		Tokoder Team
 */
class Welcome extends CG_Controller
{
	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// ------------------------------------------------------------------------

	/**
	 * We are remapping things just so we can handle methods that are
	 * http accessed.
	 */
	public function _remap($method, $params = array())
	{
		// Call the method.
		return call_user_func_array(apply_filters('remapping_method', array($this, $method)), $params);
	}

    // -----------------------------------------------------------------------------

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->themes->render();
	}

    // -----------------------------------------------------------------------------

	/**
	 * Error Page for this controller.
	 */
	public function error_404()
	{
		$this->themes->render();
	}
}