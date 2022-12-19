<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Voucher Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Voucher extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
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
			$_POST["usrid"]	= $_SESSION["uid"];
			$_POST["kode"]	= strtoupper(strtolower($_POST["kode"]));
			unset($_POST[$this->security->get_csrf_token_name()]);

			if ($_POST["id"] > 0) {
				$this->db->where("id", intval($_POST["id"]));
				$this->db->update("sales_voucher", $_POST);
				echo json_encode(["success" => true, "token" => $this->security->get_csrf_hash()]);
			} elseif ($_POST["id"] == 0) {
				$this->db->insert("sales_voucher", $_POST);
				echo json_encode(["success" => true, "token" => $this->security->get_csrf_hash()]);
			} else {
				echo json_encode(["success" => false, "token" => $this->security->get_csrf_hash()]);
			}
		} else {
			echo json_encode(["success" => false]);
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function hapus()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}
		if (isset($_POST["id"])) {
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id", intval($_POST["id"]));
			$this->db->delete("sales_voucher");
			echo json_encode(["success" => true, "token" => $this->security->get_csrf_hash()]);
		} else {
			echo json_encode(["success" => false]);
		}
	}
}