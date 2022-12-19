<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Check Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Check extends MY_Controller
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
		if ($_POST) {
			$daris = (isset($_POST["dari"])) ? $_POST["dari"] : 0;
			$tujuan = (isset($_POST["tujuan"])) ? $_POST["tujuan"] : 0;
			$berat = (isset($_POST["berat"])) ? $_POST["berat"] : 0;
			$hargapaket = (isset($_POST["hargapaket"])) ? $_POST["hargapaket"] : 0;
			$berat = ($berat == 0) ? 1000 : $berat;
			$kurir = (isset($_POST["kurir"])) ? $_POST["kurir"] : "jne";
			// if ($kurir == "jne") {
			// 	$srvdefault = "REG";
			// } elseif ($kurir == "tiki") {
			// 	$srvdefault = "REG";
			// } else {
			// }
			$srvdefault = "";
			$service = (isset($_POST["service"])) ? $_POST["service"] : $srvdefault;

			//COD
			// if ($kurir == "cod") {
			// 	$biayacod = $this->setting->getSetting("biaya_cod");
			// 	$hasil = array(
			// 		"success"	=> true,
			// 		"dari"		=> $daris,
			// 		"tujuan"	=> $tujuan,
			// 		"kurir"		=> $kurir,
			// 		"service"	=> $service,
			// 		"harga"		=> $biayacod,
			// 		"update"	=> date("Y-m-d H:i:s"),
			// 		"hargaperkg" => $biayacod,
			// 		"token"		=> $this->security->get_csrf_hash()
			// 	);
			// 	echo json_encode($hasil);
			// 	exit;
			// }
			// if ($kurir == "toko") {
			// 	$biayakurir = $this->setting->getSetting("biaya_kurir");
			// 	$hasil = array(
			// 		"success"	=> true,
			// 		"dari"		=> $daris,
			// 		"tujuan"	=> $tujuan,
			// 		"kurir"		=> $kurir,
			// 		"service"	=> $service,
			// 		"harga"		=> $biayakurir,
			// 		"update"	=> date("Y-m-d H:i:s"),
			// 		"hargaperkg" => $biayakurir,
			// 		"token"		=> $this->security->get_csrf_hash()
			// 	);
			// 	echo json_encode($hasil);
			// 	exit;
			// }

			$this->db->where("dari", $daris);
			$this->db->where("tujuan", $tujuan);
			$this->db->where("kurir", $kurir);
			$this->db->limit(1);
			$this->db->order_by("id", "DESC");
			$results = $this->db->get("history_ongkir");
			if ($results->num_rows() > 0) {
				foreach ($results->result() as $res) {
					$this->request($daris, $berat, $tujuan, $kurir, $service, $hargapaket);
					$just = true;
				}
				if (!isset($just)) {
					$this->request($daris, $berat, $tujuan, $kurir, $service, $hargapaket);
				}
			} else {
				$this->request($daris, $berat, $tujuan, $kurir, $service, $hargapaket);
			}
		} else {
			redirect("404_notfound");
		}
	}

	/*------------------------------------------------------------------------*/
	
	/**
	 * CEK tarif pengiriman (ongkos kirim) dari dan ke kota tujuan tertentu dengan berat tertentu pula.
	 *
	 * @return void
	 */
	private function request($dari, $berat, $tujuan, $kurir, $service, $hargapaket)
	{
		$usrid = (isset($_SESSION["uid"])) ? $_SESSION["uid"] : 0;

		$beratkg = $this->setting->beratkg($berat, $kurir);
		$beratkg = $beratkg < 1 ? 1 : $beratkg;

		$ongkir = $this->rajaongkir->getCost($dari, $tujuan, $berat, $kurir);
		$hasil = array(
			"success" => false, 
			"response" => "daerah tidak terjangkau!", 
			"message" => "service code tidak ada data", 
			"harga" => 0, 
			"token" => $this->security->get_csrf_hash());

		if ($ongkir['rajaongkir']['status']['code'] == 200) {
			for ($i = 0; $i < count($ongkir['rajaongkir']['results'][0]['costs']); $i++) {
				$harga = $hargapaket;
				$hargaperkg = $harga / $beratkg;
				// $service = $ongkir['rajaongkir']['results'][0]['costs'][$i]['service'];
				
				$array = array(
					"dari"		=> $dari,
					"tujuan"	=> $tujuan,
					"kurir"		=> $kurir,
					"service"	=> $service,
					"harga"		=> $harga,
					"update"	=> date("Y-m-d H:i:s"),
					"usrid"		=> $usrid
				);

				$idhistory = $this->setting->getHistoryOngkir(array(
					"dari" => $dari, 
					"tujuan" => $tujuan, 
					"kurir" => $kurir, 
					"service" => $service), "id");
				if ($idhistory > 0) {
					$this->db->where("id", $idhistory);
					$this->db->update("history_ongkir", $array);
				} else {
					if ($hargaperkg > 0) {
						$this->db->insert("history_ongkir", $array);
					}
				}

				$hasil = array(
					"success"	=> true,
					"dari"		=> $dari,
					"tujuan"	=> $tujuan,
					"kurir"		=> $kurir,
					"service"	=> $service,
					"harga"		=> $harga,
					"update"	=> date("Y-m-d H:i:s"),
					"hargaperkg" => $hargaperkg,
					"token" => $this->security->get_csrf_hash()
				);
			}
		}
		echo json_encode($hasil);
	}
}