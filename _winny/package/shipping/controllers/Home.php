<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Home extends MY_Controller
{
	/**
	 * Construct for this controller.
	 */
	public function __construct()
	{
		parent::__construct();

		// inisiasi dengan config array
		$this->load->library('rajaongkir');
	}

	/*------------------------------------------------------------------------*/
	
	/**
	 * CEK tarif pengiriman (ongkos kirim) dari dan ke kota tujuan tertentu dengan berat tertentu pula.
	 *
	 * @return void
	 */
	public function index()
	{
		$set    = $this->setting->getSetting("semua");
		$tujuan = (isset($_POST["tujuan"])) ? $_POST["tujuan"] : 278;
		$berat  = (isset($_POST["berat"])) ? $_POST["berat"] : 50;
		$kurir  = (isset($_POST["kurir"])) ? $_POST["kurir"] : "sentral";
		$ongkir = $this->rajaongkir->getCost($set->kota, $tujuan, $berat, $kurir);

		$result = "<option value=''>--Pilih Paket--</option>/n";
		if ($ongkir['rajaongkir']['status']['code'] == 200) {
			$data_paket = $ongkir['rajaongkir']['results'][0]['costs'];
			foreach ($data_paket as $key => $value) {
				$result .= "<option  value='" . $value['service'] . "' data-harga='".$value['cost'][0]['value']."'>" . $value['service'] . " | Rp." . $value['cost'][0]['value'] . " | " . $value['cost'][0]['etd'] . " Hari</option>/n";
			}
		} else {
			$result .= "<option value=''> Layanan tidak ditemukan </option>/n";
		}
		
		echo json_encode(
			array(
				"html" => $result, 
				"token" => $this->security->get_csrf_hash()
			));
	}
}