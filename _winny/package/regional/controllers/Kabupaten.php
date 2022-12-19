<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kabupaten Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Kabupaten extends MY_Controller
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
	 * Ambil data Kabupaten berdasarkan Id Provinsi
	 *
	 * @return void
	 */
	public function index()
	{
		$id = (isset($_POST["id"])) ? $_POST["id"] : 34;
		$city = $this->rajaongkir->getCities($id)['rajaongkir']['results'];
		$result = "<option value=''>Pilih Kabupaten/Kota</option>/n";
		foreach ($city as $res) {
			$result .= "<option  value='" . $res['city_id'] . "' data-nama='" . $res['city_name'] . "' data-kodepos='" . $res['postal_code'] . "'>" . $res['type'] . " " . $res['city_name'] . "</option>/n";
		}
		echo json_encode(array(
			"html" => $result, 
			"token" => $this->security->get_csrf_hash()));
	}
}