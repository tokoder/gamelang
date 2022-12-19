<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kurir Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Kurir extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
	}
    
    /*------------------------------------------------------------------------*/
    
	function aktifkan()
	{
		if (isset($_POST["push"])) {
			$toko = $this->setting->globalset("kurir");
			$kurir = explode("|", $toko);
			$kurir[] = $this->security->xss_clean($_POST["push"]);
			$push = implode("|", $kurir);

			$this->db->where("field", "kurir");
			$this->db->update("@setting", array("value" => $push, "tgl" => date("Y-m-d H:i:s")));

			echo json_encode(array(
				"success" => true, 
				"msg" => "Berhasil mengupdate profil", 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"msg" => "Forbidden!"));
		}
	}

	/*------------------------------------------------------------------------*/
	
	function nonaktifkan()
	{
		if (isset($_POST["push"])) {
			$toko = $this->setting->globalset("kurir");
			$kurir = explode("|", $toko);
			for ($i = 0; $i < count($kurir); $i++) {
				if ($kurir[$i] == $_POST["push"]) {
					unset($kurir[$i]);
				}
			}
			array_values($kurir);
			$push = implode("|", $kurir);

			$this->db->where("field", "kurir");
			$this->db->update("@setting", array("value" => $push, "tgl" => date("Y-m-d H:i:s")));

			echo json_encode(array(
				"success" => true, 
				"msg" => "Berhasil mengupdate profil", 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"msg" => "Forbidden!"));
		}
	}
}