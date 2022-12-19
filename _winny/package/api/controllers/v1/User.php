<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class User extends MY_Controller
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
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}

		if (isset($_GET["level"])) {
			//$_POST["level"] = intval(["level"]);
			$this->db->where("level", $_GET["level"]);
			$this->db->order_by("id DESC");
			$db = $this->db->get("user");

			echo '<option value="">== Pilih Pengguna ==</option>';
			foreach ($db->result() as $r) {
				echo "<option value='" . $r->id . "'>" . $r->username . "</option>";
			}
		} else {
			show_404();
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
			$this->db->delete("user_admin");
			echo json_encode(["success" => true, "token" => $this->security->get_csrf_hash()]);
		} else {
			echo json_encode(["success" => false]);
		}
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
			if (isset($_POST["pass"])) {
				$_POST["password"]	=  $this->setting->encode($_POST["pass"]);
				unset($_POST["pass"]);
			};
			unset($_POST[$this->security->get_csrf_token_name()]);

			if ($_POST["id"] > 0) {
				$this->db->where("id", intval($_POST["id"]));
				$this->db->update("user_admin", $_POST);
				echo json_encode(["success" => true, "token" => $this->security->get_csrf_hash()]);
			} elseif ($_POST["id"] == 0) {
				$this->db->insert("user_admin", $_POST);
				echo json_encode(["success" => true, "token" => $this->security->get_csrf_hash()]);
			} else {
				echo json_encode(["success" => false, "token" => $this->security->get_csrf_hash()]);
			}
		} elseif (isset($_POST["gantipass"])) {
			$set["password"] = $this->setting->encode($_POST["gantipass"]);

			$this->db->where("id", $_SESSION["uid"]);
			$this->db->update("user_admin", $set);
			echo json_encode(["success" => true, "token" => $this->security->get_csrf_hash()]);
		} else {
			echo json_encode(["success" => false]);
		}
	}
}