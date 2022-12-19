<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Blog Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Blog extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
		$this->load->helper('admin');
	}
    
    /*------------------------------------------------------------------------*/
    
	public function hapus()
	{
		if (isset($_POST["id"])) {
			//$_POST["id"] = intval(["id"]);
			$img = $this->setting->getBlog($_POST["id"], "img");
			if ($img != "no-image.png") {
				unlink("./uploads/" . $img);
			}

			$this->db->where("id", $_POST["id"]);
			$this->db->delete("@blog");

			echo json_encode(array(
				"success" => true, 
				"msg" => "berhasil menghapus", 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"msg" => "form not submitted!"));
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function upload($id = 0)
	{
		if (isset($_POST)) {
			if (isset($_SESSION["fotoPage"])) {
				unlink("./uploads/" . $_SESSION["fotoPage"]);
				$this->session->unset_userdata("fotoPage");
			}

			$config['upload_path'] = './uploads/gallery/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['file_name'] = "blogpost_" . $_SESSION["uid"] . date("YmdHis");

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('foto')) {
				echo json_encode(array(
					"success" => false, 
					"msg" => $this->upload->display_errors(), 
					"token" => $this->security->get_csrf_hash()));
			} else {
				$uploadData = $this->upload->data();
				/*
				$this->load->library('image_lib');
				$config_resize['image_library'] = 'gd2';
				$config_resize['maintain_ratio'] = TRUE;
				$config_resize['master_dim'] = 'height';
				$config_resize['quality'] = "100%";
				$config_resize['source_image'] = $config['upload_path'].$uploadData["file_name"];

				$config_resize['width'] = 1024;
				$config_resize['height'] = 720;
				$this->image_lib->initialize($config_resize);
				$this->image_lib->resize();
				*/

				if ($id == 0) {
					$_SESSION["fotoPage"] = $uploadData["file_name"];
				} else {
					$img = $this->setting->getBlog($id, "img");
					$url = "uploads/" . $img;
					if ($img != "no-image.png" and file_exists($url)) {
						unlink($url);
					}
					$this->db->where("id", $id);
					$this->db->update("@blog", ["img" => $uploadData["file_name"], "tgl" => date("Y-m-d H:i:s")]);
				}

				echo json_encode(array(
					"success" => true, 
					"filename" => $uploadData["file_name"], 
					"token" => $this->security->get_csrf_hash()));
			}
		} else {
			echo json_encode(array(
				"success" => false, 
				"msg" => "File tidak ditemukan", 
				"token" => $this->security->get_csrf_hash()));
		}
	}
}