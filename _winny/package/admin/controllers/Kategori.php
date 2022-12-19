<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kategori Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Kategori extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
		$this->load->helper('admin');
	}
    
    /*------------------------------------------------------------------------*/
    
	public function index()
	{
		if (!isset($_SESSION["isMasok"]) or $_SESSION["isMasok"] != true) {
			redirect('admin/auth/logout');
			exit;
		}
		$this->view('produk/kategori/index',array("menu" => 7));
	}

	/*------------------------------------------------------------------------*/
	
	public function form($id = 0)
	{
		if (!isset($_SESSION["isMasok"]) or $_SESSION["isMasok"] != true) {
			redirect('admin/auth/logout');
			exit;
		}

		if (!isset($_POST["nama"])) {
			$this->view('produk/kategori/form', ["id" => $id, "menu" => 7]);
		} else {
			if (isset($_FILES['icon']) and $_FILES['icon']['size'] != 0 and $_FILES['icon']['error'] == 0) {
				$config['upload_path'] = './uploads/kategori/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name'] = "cat_" . date("YmdHis");

				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('icon')) {
					$error = array('error' => $this->upload->display_errors());
					print_r($error);
					exit;
				} else {
					$uploadData = $this->upload->data();

					$data["icon"] = $uploadData["file_name"];
				}
			}

			$data["nama"] = $_POST["nama"];
			$data["url"] = clean($_POST["nama"]);

			if ($_POST["id"] == 0) {
				$this->db->insert("@kategori", $data);
			} else {
				$this->db->where("id", $_POST["id"]);
				$this->db->update("@kategori", $data);
			}

			redirect("admin/kategori");
		}
	}

	/*------------------------------------------------------------------------*/
	
	function hapus()
	{
		if (!isset($_SESSION["isMasok"]) or $_SESSION["isMasok"] != true) {
			redirect('admin/auth/logout');
			exit;
		}

		if (isset($_POST["pro"])) {
			$_POST["pro"] = $this->setting->clean($_POST["pro"]);
			$this->db->where("id", $_POST["pro"]);
			$dbs = $this->db->get("@kategori");
			foreach ($dbs->result() as $R) {
				unlink("./uploads/kategori/" . $R->icon);
			}
			$this->db->where("id", $_POST["pro"]);
			$this->db->delete("@kategori");

			echo json_encode(array(
				"success" => true, 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"msg" => "produk tidak ditemukan", 
				"token" => $this->security->get_csrf_hash()));
		}
	}
}