<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Order extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
	}
    
    /*------------------------------------------------------------------------*/
    
	public function index()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_GET['load']) and $_GET['load'] == "bayar") {
			$res = $this->load->view("order/bayar", "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} elseif (isset($_GET['load']) and $_GET['load'] == "dikemas") {
			$res = $this->load->view("order/dikemas", "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} elseif (isset($_GET['load']) and $_GET['load'] == "dikirim") {
			$res = $this->load->view("order/dikirim", "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} elseif (isset($_GET['load']) and $_GET['load'] == "selesai") {
			$res = $this->load->view("order/selesai", "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} elseif (isset($_GET['load']) and $_GET['load'] == "batal") {
			$res = $this->load->view("order/batal", "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} else {
			$this->view('order/index', ['menu' => 2]);
		}
	}

	/*------------------------------------------------------------------------*/
	
	function detail()
	{
		if (isset($_GET["theid"])) {
			$this->load->view("order/detail");
		} else {
			echo "404 - Request Not Found";
		}
	}
}