<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Preorder Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Preorder extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
	}

	/*------------------------------------------------------------------------*/
	
	function update()
	{
		if (isset($_POST["id"])) {
			//$_POST["id"] = intval(["id"]);

			$this->db->where("id", intval($_POST["id"]));
			$this->db->update("preorder", $_POST);

			echo json_encode(array(
				"success" => true, 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"token" => $this->security->get_csrf_hash()));
		}
	}
}