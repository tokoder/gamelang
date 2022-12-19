<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Page Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Page extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
	}
    
    /*------------------------------------------------------------------------*/
    
	function index()
	{
		if (isset($_POST["formid"])) {
			//$_POST["formid"] = intval(["formid"]);
			$this->db->where("id", intval($_POST["formid"]));
			$db = $this->db->get("@page");
			$data = array();
			foreach ($db->result() as $r) {
				$data = array(
					"nama"	=> $r->nama,
					"konten" => $r->konten
				);
			}

			echo json_encode(array(
				"success" => true, 
				"data" => $data, 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false));
		}
	}

	/*------------------------------------------------------------------------*/
	
	function update()
	{
		if (isset($_POST["id"])) {
			$_POST["usrid"] = $_SESSION["uid"];
			$_POST["tgl"] = date("Y-m-d H:i:s");
			$_POST["slug"] = $this->setting->cleanURL($_POST["nama"]);
			$_POST["status"] = 1;
			unset($_POST[$this->security->get_csrf_token_name()]);

			if ($_POST["id"] != 0) {
				$this->db->where("id", intval($_POST["id"]));
				$this->db->update("@page", $_POST);
			} else {
				$this->db->insert("@page", $_POST);
			}

			echo json_encode(array(
				"success" => true, 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"token" => $this->security->get_csrf_hash()));
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
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id", intval($_POST["id"]));
			$this->db->delete("@page");
			echo json_encode([
				"success" => true, 
				"token" => $this->security->get_csrf_hash()]);
		} else {
			echo json_encode([
				"success" => false]);
		}
	}
}