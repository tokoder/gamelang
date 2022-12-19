<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Agen Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Agen extends MY_Controller
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

		if (isset($_GET["load"])) {
			$res = $this->load->view('agen/list', "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} else {
			$this->view('agen/index', ["menu" => 9]);
		}
	}
    
    /*------------------------------------------------------------------------*/
    
	public function tambah()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}

		if (isset($_POST["id"])) {
			//$_POST["id"] = intval(["id"]);
			$data = array(
				"level"	=> $_POST["level"],
				"tgl"	=> date("Y-m-d H:i:s")
			);
			if ($_POST["id"] > 0) {
				$this->db->where("id", intval($_POST["id"]));
				$this->db->update("user", $data);
				echo json_encode([
					"success" => true, 
					"token" => $this->security->get_csrf_hash()]);
			} elseif ($_POST["id"] == 0) {
				$this->db->insert("user", $data);
				echo json_encode([
					"success" => true, 
					"token" => $this->security->get_csrf_hash()]);
			} else {
				echo json_encode([
					"success" => false, 
					"token" => $this->security->get_csrf_hash()]);
			}
		} else {
			$res = $this->load->view("agen/form", "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		}
	}
}