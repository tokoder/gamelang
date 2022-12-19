<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Shop Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Shop extends MY_Controller
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
		$this->view('shop', $this->data);
	}
}
