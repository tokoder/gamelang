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
    
	public function index()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_GET["load"])) {
			$res = $this->load->view('blog/list', "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} else {
			$this->view('blog/index', ["menu" => 17, "tiny" => true]);
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function edit($id = 0)
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_POST["nama"])) {
			$foto  = isset($_SESSION["fotoPage"]) ? $_SESSION["fotoPage"] : "no-image.png";
			$string = clean($_POST["nama"]);
			$this->db->where("url", $string);
			$dbs = $this->db->get("@blog");
			if ($dbs->num_rows() > 0) {
				$string = $string . "-" . date("His");
			}

			$data = [
				"tgl"	=> date("Y-m-d H:i:s"),
				"judul"	=> $_POST["nama"],
				"img"	=> $foto,
				"konten" => $_POST["konten"],
				"url"	=> $string
			];
			if ($id > 0) {
				unset($data["url"]);
				unset($data["img"]);
				$this->db->where("id", $id);
				$this->db->update("@blog", $data);

				//print_r($thumb);
				redirect("admin/blog");
			} else {
				$this->db->insert("@blog", $data);
				$insertid = $this->db->insert_id();

				redirect("admin/blog");
			}
		} else {
			$this->view("blog/form", ["id" => $id, "menu" => 17, "tiny" => true]);
		}
	}
}