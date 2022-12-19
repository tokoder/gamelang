<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Upload Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Upload extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
	}

	/*------------------------------------------------------------------------*/
	
	// FotoProduk
	public function index()
	{
		if (isset($_POST)) {
			//$_POST["idproduk"] = intval(["idproduk"]);
			$this->db->where("idproduk", $_POST["idproduk"]);
			$db = $this->db->get("produk_upload");
			$jenis = $db->num_rows() > 0 ? 0 : 1;

			$config['upload_path'] = './uploads/produk/';
			$config['allowed_types'] = 'gif|jpeg|jpg|png';
			$config['file_name'] = $_SESSION["uid"] . date("YmdHis");

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('fotoProduk')) {
				echo json_encode(array("success" => false, "msg" => "error: " . $this->upload->display_errors(), "token" => $this->security->get_csrf_hash()));
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

				$data = array(
					"idproduk" => $_POST["idproduk"],
					"jenis" => $jenis,
					"nama" => $uploadData["file_name"],
					"tgl" => date("Y-m-d H:i:s")
				);
				unset($data[$this->security->get_csrf_token_name()]);
				$this->db->insert("produk_upload", $data);
				$_SESSION["fotoProduk"][] = $this->db->insert_id();

				if ($_POST["idproduk"] == 0) {
					$upl = (isset($_SESSION["uploadedPhotos"])) ? $_SESSION["uploadedPhotos"] : 0;
					$_SESSION["uploadedPhotos"] = $upl + 1;
				}

				echo json_encode(array(
					"success" => true, 
					"token" => $this->security->get_csrf_hash()));
			}
		} else {
			echo json_encode(array(
				"success" => false, 
				"msg" => "File tidak ditemukan", 
				"token" => $this->security->get_csrf_hash()));
		}
	}
    
    /*------------------------------------------------------------------------*/
    
	// jadikanFotoUtama
	public function utama($id)
	{
		$idproduk = (isset($_POST["idproduk"])) ? intval($_POST["idproduk"]) : 0;
		$this->db->where("idproduk", $idproduk);
		$this->db->where("jenis", 1);
		$this->db->update("produk_upload", array("jenis" => 0));

		$this->db->where("id", $id);
		$this->db->update("produk_upload", array("jenis" => 1));

		echo json_encode(array(
			"success" => true, 
			"token" => $this->security->get_csrf_hash()));
	}

	/*------------------------------------------------------------------------*/
	
	// hapusFotoProduk
	public function hapus($id)
	{
		if ($id == "all") {
			$idproduk = (isset($_POST["idproduk"])) ? intval($_POST["idproduk"]) : 0;
			$this->db->where("idproduk", $idproduk);
			$db = $this->db->get("produk_upload");
			foreach ($db->result() as $res) {
				if (file_exists("uploads/" . $res->nama)) {
					unlink("./uploads/produk/" . $res->nama);
				}
			}

			$_SESSION["uploadedPhotos"] = 0;
			$this->session->unset_userdata("fotoProduk");
			$this->db->where("idproduk", $idproduk);
			$this->db->delete("produk_upload");
		} else {
			$url = $this->setting->getUpload($id, "nama");
			if (file_exists($url)) {
				unlink($url);
			}
			$this->db->where("id", $id);
			$this->db->delete("produk_upload");
			$_SESSION["uploadedPhotos"] = $_SESSION["uploadedPhotos"] - 1;
			if (($key = array_search($id, $_SESSION["fotoProduk"])) !== false) {
				unset($_SESSION["fotoProduk"][$key]);
			}
		}

		echo json_encode(array(
			"success" => true, 
			"token" => $this->security->get_csrf_hash()));
	}

	/*------------------------------------------------------------------------*/
	
	public function logo($tipe = 1)
	{
		if (isset($_POST)) {
			$type = $tipe == 1 ? "logo" : "favicon";
			$foto = $this->setting->globalset($type);
			if (file_exists("./uploads/logo/" . $foto)) {
				unlink("./uploads/logo/" . $foto);
			}

			$config['upload_path'] = './uploads/logo/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['file_name'] = $type . "-" . date("YmdHis");

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('logo')) {
				$error = array('error' => $this->upload->display_errors());
			} else {
				$uploadData = $this->upload->data();
				$data = array(
					"value" => 'uploads/logo/'.$uploadData["file_name"],
					"tgl" => date("Y-m-d H:i:s")
				);
				$this->db->where("field", $type);
				$this->db->update("@setting", $data);
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