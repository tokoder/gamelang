<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Rekening Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Rekening extends MY_Controller
{
	/**
	 * Construct
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
		$this->load->library('rajaongkir');
	}

	/*------------------------------------------------------------------------*/
	
	public function index()
	{
		if (isset($_POST["rek"])) {
			$rek = $this->setting->getRekening($_POST["rek"], "semua");
			if (count((array)$rek) > 0) {
				echo json_encode(array(
					"success" => true, 
					"id" => $rek->id, 
					"idbank" => $rek->idbank, 
					"norek" => $rek->norek, 
					"atasnama" => $rek->atasnama, 
					"kcp" => $rek->kcp, 
					"token" => $this->security->get_csrf_hash()));
			} else {
				echo json_encode(array(
					"success" => false, 
					"token" => $this->security->get_csrf_hash()));
			}
		} else {
			echo json_encode(array(
				"success" => false, 
				"token" => $this->security->get_csrf_hash()));
		}
	}

	/*------------------------------------------------------------------------*/
	
	// REKENING
	public function drop($id = 1, $drop = "all")
	{
		if ($id != null) {
			$db = $this->user_model->getRekeningById($id);
			$asal = $id > 0 ? "asal" : "tujuan";
			$asal = $drop != "all" ? $drop : $asal;

			echo "<option value=''>Rekening " . $asal . "</option>";
			foreach ($db->result() as $res) {
				echo "<option value='" . $res->id . "'>BANK " . $this->setting->getBank($res->idbank, "nama") . " - " . $res->norek . " a/n " . $res->atasnama . "</option>";
			}
			echo "<option value='tambah'>+ Tambah Baru</option>";
		} else {
			echo "<option value=''>ERROR</option>";
		}
	}
}
