<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Produk Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Produk extends MY_Controller
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

		if (isset($_GET['load']) and $_GET['load'] == "true") {
			$res = $this->load->view('produk/list', "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} else {
			$this->view('produk/index', ["menu" => 6]);
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function form($id = 0)
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_POST["nama"])) {
			$_POST["id"] = $this->setting->clean($_POST["id"]);
			$data = [
				"tgl"	=> date("Y-m-d H:i:s"),
				"nama"	=> $_POST["nama"],
				"deskripsi" => $_POST["deskripsi"],
			];
			if ($_POST["id"] > 0) {
				$this->db->where("id", $_POST["id"]);
				$this->db->update("produk", $data);

				//print_r($thumb);
				redirect("admin/produk");
			} else {
				$this->db->insert("produk", $data);
				$insertid = $this->db->insert_id();

				redirect("admin/produk");
			}
		} else {
			$this->view("produk/form", ["id" => $id, "menu" => 6, "tiny" => true]);
		}
	}
}