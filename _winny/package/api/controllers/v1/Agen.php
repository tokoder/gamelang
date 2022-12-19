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
	
	public function hapus()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}

		if (isset($_POST)) {
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id", $_POST["id"]);
			$this->db->delete("user");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("user_alamat");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("user_profil");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("sales");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("preorder");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("sales_produk");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("invoice");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("user_rekening");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("sales_wishlist");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("saldo");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("saldohistory");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("saldotarik");
			$this->db->where("usrid", $_POST["id"]);
			$this->db->delete("user_token");
			$this->db->where("dari", $_POST["id"]);
			$this->db->or_where("tujuan", $_POST["id"]);
			$this->db->delete("pesan");

			echo json_encode([
				"success" => true, 
				"token" => $this->security->get_csrf_hash()]);
		} else {
			echo json_encode([
				"success" => false, 
				"token" => $this->security->get_csrf_hash()]);
		}
	}
}