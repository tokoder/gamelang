<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Notif Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Notif extends MY_Controller
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
			$res = $this->load->view('notif/masuk', "", true);
			echo json_encode([
				"result" => $res, 
				"token" => $this->security->get_csrf_hash()]);
		} else {
			$this->view('notif/index', ["menu" => 4]);
		}
	}
}