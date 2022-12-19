<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Upload Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Upload extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
	}

	/*------------------------------------------------------------------------*/
	
	// FotoResult
	public function result($idpro = 0)
	{
		$data = $this->load->view("produk/foto", array("idproduk" => $idpro), true);
		echo json_encode(array(
			"success" => true, 
			"data" => $data, 
			"token" => $this->security->get_csrf_hash()));
	}
}