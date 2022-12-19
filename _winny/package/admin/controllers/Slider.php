<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Slider Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Slider extends MY_Controller
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
		if (!isset($_SESSION["isMasok"]) or $_SESSION["isMasok"] != true) {
			redirect("admin/auth/logout");
			exit;
		}
		$this->view('slider/index', array("menu" => 5));
	}

	/*------------------------------------------------------------------------*/
	
	public function form($id = 0)
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (!isset($_POST["tgl"])) {
			$this->view('slider/form', ["id" => $id, "menu" => 5]);
		} else {
			if ($_POST["id"] == 0) {
				$config['upload_path'] = './uploads/promo/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name'] = "promo_upl" . date("YmdHis");

				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('gambar')) {
					$error = array('error' => $this->upload->display_errors());
					print_r($error);
				} else {
					$uploadData = $this->upload->data();

					$data = [
						"usrid" => $_SESSION["uid"],
						"caption" => $_POST["caption"],
						"jenis" => $_POST["jenis"],
						"link" => $_POST["link"],
						//"linktext"=> $_POST["linktext"],
						"status" => $_POST["status"],
						"tgl" => $_POST["tgl"],
						"tgl_selesai" => $_POST["tgl_selesai"],
						"gambar" => $uploadData["file_name"]
					];
					$this->db->insert("@promo", $data);

					redirect("admin/slider");
				}
			} else {
				$data = [
					"usrid" => $_SESSION["uid"],
					"caption" => $_POST["caption"],
					"jenis" => $_POST["jenis"],
					"link" => $_POST["link"],
					//"linktext"=> $_POST["linktext"],
					"status" => $_POST["status"],
					"tgl" => $_POST["tgl"],
					"tgl_selesai" => $_POST["tgl_selesai"]
				];
				$this->db->where("id", $_POST["id"]);
				$this->db->update("@promo", $data);

				redirect("admin/slider");
			}
		}
	}

	/*------------------------------------------------------------------------*/
	
	function hapus()
	{
		if (!isset($_SESSION["isMasok"]) or $_SESSION["isMasok"] != true) {
			redirect("admin/auth/logout");
			exit;
		}

		if (isset($_POST["pro"])) {
			$_POST["pro"] = $this->setting->clean($_POST["pro"]);
			$this->db->where("id", $_POST["pro"]);
			$dbs = $this->db->get("@promo");
			foreach ($dbs->result() as $R) {
				if (file_exists("uploads/promo/" . $R->gambar)) {
					unlink("uploads/promo/" . $R->gambar);
				}
			}
			$this->db->where("id", $_POST["pro"]);
			$this->db->delete("@promo");

			echo json_encode(array(
				"success" => true, 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"msg" => "promo tidak ditemukan", 
				"token" => $this->security->get_csrf_hash()));
		}
	}
}