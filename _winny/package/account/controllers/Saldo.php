<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Saldo Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Saldo extends MY_Controller
{
	/**
	 * Construct
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
		$this->load->library('rajaongkir');
	}
	
	/*------------------------------------------------------------------------*/
	
	// TOPUP SALDO
	function index()
	{
		if (isset($_POST)) {
			$idbayar = $_SESSION["uid"] . date("YmdHis");
			$data = array(
				"status" => 0,
				"jenis"	=> 2,
				"usrid"	=> $_SESSION["uid"],
				"total"	=> $_POST["jumlah"],
				"tgl"	=> date("Y-m-d H:i:s"),
				"trxid"	=> $idbayar
			);
			$this->db->insert("user_saldo_tarik", $data);

			$idbayar = $this->setting->arrEnc(array("trxid" => $idbayar), "encode");
			echo json_encode(array(
				"success" => true, 
				"idbayar" => $idbayar, 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"message" => "forbidden", 
				"token" => $this->security->get_csrf_hash()));
		}
	}
}
