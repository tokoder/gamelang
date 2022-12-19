<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Preorder Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Preorder extends MY_Controller
{
	/**
	 * Construct for this controller.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('produk_model');
		$this->load->model('sales_model');
		$this->data['set'] = $this->setting->getSetting("semua");
	}

	/*------------------------------------------------------------------------*/
	
	public function index()
	{
		$this->view('preorder', $this->data);
	}
}
