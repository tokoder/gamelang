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
    
	public function index()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		$this->view('testimoni/index', ["menu" => 18]);
	}
    
    /*------------------------------------------------------------------------*/
    
	public function list()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}

		if (isset($_GET["load"])) {
			$res = $this->load->view("testimoni/list", "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} elseif (isset($_POST["formid"])) {
			//$_POST["formid"] = intval(["formid"]);
			$this->db->where("id", intval($_POST["formid"]));
			$db = $this->db->get("@testimoni");
			$data = [];
			foreach ($db->result() as $r) {
				$data = [
					"id"	=> $_POST["formid"],
					"nama"	=> $r->nama,
					"foto"	=> $r->foto,
					"jabatan"	=> $r->jabatan,
					"komentar"	=> $r->komentar,
					"token"	=> $this->security->get_csrf_hash()
				];
			}
			echo json_encode($data);
		} else {
			redirect("admin");
		}
	}
}