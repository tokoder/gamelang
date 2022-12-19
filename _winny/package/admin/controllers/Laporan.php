<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Laporan Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Laporan extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
		$this->load->library('rajaongkir');
	}
    
    /*------------------------------------------------------------------------*/
    
	public function transaksi()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_GET["load"])) {
			$res = $this->load->view('laporan/transaksilist', "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} else {
			$this->view('laporan/transaksi', ["menu" => 14]);
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function user()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_GET["load"])) {
			$res = $this->load->view('laporan/userlist', "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} else {
			$this->view('laporan/user',["menu" => 15]);
		}
	}

	/*------------------------------------------------------------------------*/
	
	function cetakInvoice()
	{
		$this->load->view("laporan/invoice");
	}

	/*------------------------------------------------------------------------*/
	
	function cetakLabel()
	{
		$this->load->view("laporan/label");
	}
}