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
    
	public function index()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_GET["load"])) {
			//$this->load->view('admin/notif');
		} else {
			$this->view('voucher/index', ["menu" => 3]);
		}
	}
    
    /*------------------------------------------------------------------------*/
    
	public function list()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}

		if (isset($_GET["load"])) {
			$res = $this->load->view("voucher/list", "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} elseif (isset($_POST["formid"])) {
			$_POST["formid"] = $_POST["formid"];
			$this->db->where("id", intval($_POST["formid"]));
			$db = $this->db->get("sales_voucher");
			$data = [];
			foreach ($db->result() as $r) {
				$data = [
					"id"           => $_POST["formid"],
					"usrid"        => $r->usrid,
					"nama"         => $r->nama,
					"deskripsi"    => $r->deskripsi,
					"kode"         => strtoupper(strtolower($r->kode)),
					"mulai"        => $r->mulai,
					"selesai"      => $r->selesai,
					"jenis"        => $r->jenis,
					"tipe"         => $r->tipe,
					"idproduk"     => $r->idproduk,
					"potongan"     => $r->potongan,
					"potonganmin"  => $r->potonganmin,
					"potonganmaks" => $r->potonganmaks,
					"peruser"      => $r->peruser,
					"token"        => $this->security->get_csrf_hash()
				];
			}
			echo json_encode($data);
		} else {
			redirect("admin");
		}
	}
}