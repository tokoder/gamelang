<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Testimoni Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Testimoni extends MY_Controller
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
			if (isset($_FILES['foto']) and $_FILES['foto']['size'] != 0 && $_FILES['foto']['error'] == 0) {
				$testi = (isset($_POST["id"])) ? intval($_POST["id"]) : 0;
				$this->db->where("id", $testi);
				$db = $this->db->get("@testimoni");
				foreach ($db->result() as $res) {
					if (file_exists("./uploads/" . $res->foto) and $res->foto != "") {
						unlink("./uploads/" . $res->foto);
					}
				}

				$config['upload_path'] = './uploads/gallery/';
				$config['allowed_types'] = 'jpg|jpeg|png|bmp|gif';
				$config['file_name'] = "testimoni_" . date("YmdHis");;

				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('foto')) {
					$error = $this->upload->display_errors();
					echo json_encode(array("success" => false, "msg" => $error, "token" => $this->security->get_csrf_hash()));
					exit;
				} else {
					$upload_data = $this->upload->data();
					$_POST["foto"] = $upload_data["file_name"];
				}
			} else {
				unset($_FILES["foto"]);
				unset($_POST["foto"]);
			}
			$_POST["tgl"] = date("Y-m-d H:i:s");
			$_POST["status"] = 1;
			unset($_POST[$this->security->get_csrf_token_name()]);

			if ($_POST["id"] > 0) {
				$this->db->where("id", intval($_POST["id"]));
				$this->db->update("@testimoni", $_POST);
				echo json_encode(["success" => true, "token" => $this->security->get_csrf_hash()]);
			} elseif ($_POST["id"] == 0) {
				$this->db->insert("@testimoni", $_POST);
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
			$testi = (isset($_POST["id"])) ? intval($_POST["id"]) : 0;
			$this->db->where("id", $testi);
			$db = $this->db->get("@testimoni");
			foreach ($db->result() as $res) {
				if (file_exists("./uploads/" . $res->foto) and $res->foto != "") {
					unlink("./uploads/" . $res->foto);
				}
			}

			//$_POST["id"] = intval(["id"]);
			$this->db->where("id", intval($_POST["id"]));
			$this->db->delete("@testimoni");
			echo json_encode(["success" => true, "token" => $this->security->get_csrf_hash()]);
		} else {
			echo json_encode(["success" => false]);
		}
	}
}