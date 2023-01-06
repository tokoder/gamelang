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
 * Load Controller Class
 *
 * It handles render content and language
 *
 * @subpackage 	Controllers
 * @author		Tokoder Team
 */
class Lang extends CG_Controller
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

	// ------------------------------------------------------------------------

	/**
	 * set_language
	 *
	 * Method for changing current site language.
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function index()
	{
		// The language's folder name
		$folder = $this->uri->segment(3);

		// Prepare redirection.
		$redirect = ($this->input->get_post('next'))
			? $this->input->get_post('next')
			: $this->input->referrer('/', true);

		// No language set? Nothing to do.
		if (empty($folder))
		{
			redirect($redirect);
			exit;
		}

		// Language not available? Nothing to do.
		if (in_array($folder, config_item('languages')))
		{
			// We setup the session.
			$this->session->set_userdata('language', $folder);

			// We update user's language if he/she is logged in.
			if (true === $this->auth->online())
			{
				$user = $this->auth->user();
				$user->update('language', $folder);
			}
		}

		redirect($redirect);
		exit;
	}
}