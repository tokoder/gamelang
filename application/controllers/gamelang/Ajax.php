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
 * Reports AJAX controller.
 *
 * @subpackage 	Admin
 * @author		Tokoder Team
 */
class Ajax extends CG_Controller_Ajax
{
	/**
	 * __constructr
	 *
	 * Added safe methods.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	AJAX_Controller::response()
	 */
	public function __construct()
	{
		parent::__construct();

		// Add safe delete.
		$this->safe_admin_methods[] = 'reports';
	}

	// ------------------------------------------------------------------------

	/**
	 * index
	 *
	 * This method handles all operation done on the reserved sections.
	 *
	 * @access 	public
	 * @param 	string 	$target 	The target to perform action on.
	 * @param 	string 	$action 	The action to perform on the target.
	 * @param 	mixed 	$id 		The target name/id.
	 * @return 	AJAX_Controller:response()
	 */
	public function index(){}

	// ------------------------------------------------------------------------

	/**
	 * reports
	 *
	 * Method for interacting with reports.
	 *
	 * @access 	public
	 * @param 	string 	$action 	The action to perform.
	 * @param 	int  	$id 		The report ID.
	 * @return 	AJAX_Controller::response()
	 */
	public function reports($action = null, $id = 0)
	{
		/**
		 * In order to proceed, the following conditions are required:
		 * 1. The is is provided and is numeric.
		 */
		if (( ! is_numeric($id) OR $id < 0) )
		{
			$this->response->header  = self::HTTP_NOT_ACCEPTABLE;
			$this->response->message = __('error_nonce_url');
			return;
		}

		$this->response->message = $action;
		switch ($action) {

			// Delete report.
			case 'delete':

				// Successfully deleted?
				if (false !== $this->activities->delete($id))
				{
					$this->response->header  = self::HTTP_OK;
					$this->response->message = __('lang_success_delete');
					return;
				}

				// Otherwise, the activity could not be deleted.
				$this->response->message = __('lang_error_delete');
				return;

				break;
		}
	}

	// ----------------------------------------------------------------------------

	public function captcha()
	{
		$this->load->helper('captcha');
		$generate_captcha = generate_captcha();
		$img_url = $this->config->item('img_url', 'captcha');

		$this->response->header  = self::HTTP_OK;
		$this->response->results = path_join($img_url, $generate_captcha['filename']);
	}

	// ----------------------------------------------------------------------------

	public function login()
	{
		// Prepare empty validation rules.
		$rules = array();

		// add user
		$rules[]    = array(
			'field' => 'identity',
			'label' => 'lang:lang_identity',
			'rules' => 'trim|required|min_length[5]|user_exists'
		);

		// Add password.
		$rules[] = array(
			'field' => 'password',
			'label' => 'lang:lang_password',
			'rules' => "trim|required|min_length[8]|max_length[20]"
		);

		// Prepare form validation and pass rules.
		$this->prep_form(apply_filters('auth-validation-data', $rules), '#form-login');

		// After form processing!
		if ($this->form_validation->run() == false)
		{
			$this->response->message = $this->form_validation->validation_errors_list();
			return;
		}

		if (true !== check_nonce('user-login'))
		{
			$this->response->header  = self::HTTP_NOT_ACCEPTABLE;
			$this->response->message = __('error_nonce_url');
			return;
		}

		// Attempt to login the current user.
		$user_id = $this->auth->login(
			$this->input->post('identity', true),
			$this->input->post('password', true),
			$this->input->post('remember') == '1'
		);

		if ($user_id) {
			$this->response->header  = self::HTTP_OK;
		}
		else {
			$this->response->message = __('lang_ERROR_ACCOUNT_MISSING');
		}
	}
}