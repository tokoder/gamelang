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
    
	public function index()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_GET["load"])) {
			//$this->load->view('admin/notif');
		} else {
			$this->view('user/index', ["menu" => 10]);
		}
	}
    
    /*------------------------------------------------------------------------*/
    
	public function list()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}

		if ($this->setting->demo() == true) {
			echo json_encode([
				"result" => "maaf, fitur tidak tersedia untuk mode demo aplikasi", 
				"token" => $this->security->get_csrf_hash()]);
		} else {
			if (isset($_POST["formid"])) {
				//$_POST["formid"] = intval(["formid"]);
				$rek = $this->user_model->getUserAdmin(intval($_POST["formid"]), "semua");
				$data = [
					"id"		=> $_POST["formid"],
					"pass"		=> $this->setting->decode($rek->password),
					"username"	=> $rek->username,
					"nama"		=> $rek->nama,
					"level"		=> $rek->level,
					"token"		=> $this->security->get_csrf_hash()
				];
				echo json_encode($data);
			} else {
				$res = $this->load->view("user/list", "", true);
				echo json_encode([
					"result" => $res, 
					"token" => $this->security->get_csrf_hash()]);
			}
		}
	}
}