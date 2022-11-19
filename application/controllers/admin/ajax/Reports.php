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
class Reports extends CG_Controller_Ajax
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
		$this->safe_admin_methods[] = 'delete';
	}

	// ----------------------------------------------------------------------------

	public function index()
	{
		# code...
	}

	// ------------------------------------------------------------------------

	/**
	 * delete
	 *
	 * Method for interacting with delete.
	 *
	 * @access 	public
	 * @param 	int  	$id 		The report ID.
	 * @return 	AJAX_Controller::response()
	 */
	public function delete($id = 0)
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
	}

}