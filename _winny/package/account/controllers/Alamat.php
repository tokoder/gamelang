<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Alamat Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Alamat extends MY_Controller
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
	
	// ALAMAT
	public function index()
	{
		if (isset($_POST["rek"])) {
			$rek = $this->setting->getAlamat($_POST["rek"], "semua");
			if (count((array)$rek) > 0) {
				$kab = '';
				$prov = '';
				$keckab = $this->rajaongkir->getCity($rek->idkab)['rajaongkir'];
				if ($keckab['status']['code'] == '200') {
					$kab = $keckab['results']['city_id'];
					$prov = $keckab['results']['province_id'];
				}
				$respon = array(
					"success" => true,
					"kab"     => $kab,
					"prov"    => $prov,
					"idkec"   => $rek->idkec,
					"nama"    => $rek->nama,
					"judul"   => $rek->judul,
					"alamat"  => $rek->alamat,
					"kodepos" => $rek->kodepos,
					"nohp"    => $rek->nohp,
					"status"  => $rek->status,
					"token"   => $this->security->get_csrf_hash()
				);
				echo json_encode($respon);
			} else {
				echo json_encode(array(
					"success" => false,
					"token" => $this->security->get_csrf_hash()
				));
			}
		} else {
			echo json_encode(array(
				"success" => false,
				"token" => $this->security->get_csrf_hash()
			));
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function tambah()
	{
		if (isset($_POST["idkab"])) {
			if ($_POST["status"] == 1) {
				$this->db->where("usrid", $_SESSION["uid"]);
				$this->db->update("user_alamat", array("status" => 0));
			}

			$data = array(
				"usrid"   => $_SESSION["uid"],
				"idkab"   => $_POST["idkab"],
				"nama"    => $_POST["nama"],
				"judul"   => $_POST["judul"],
				"alamat"  => $_POST["alamat"],
				"kodepos" => $_POST["kodepos"],
				"nohp"    => $_POST["nohp"],
				"status"  => $_POST["status"]
			);

			if (isset($_POST["id"]) and $_POST["id"] > 0) {
				$this->db->where("id", $_POST["id"]);
				$this->db->update("user_alamat", $data);
			} else {
				$this->db->insert("user_alamat", $data);
			}

			echo json_encode(array(
				"success" => true,
				"id" => $this->db->insert_id(),
				"token" => $this->security->get_csrf_hash()
			));
		} else {
			echo json_encode(array(
				"success" => false,
				"token" => $this->security->get_csrf_hash()
			));
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function hapus()
	{
		if (isset($_POST["rek"])) {
			$this->db->where("id", $_POST["rek"]);
			$this->db->delete("user_alamat");

			echo json_encode(array(
				"success" => true,
				"token" => $this->security->get_csrf_hash()
			));
		} else {
			echo json_encode(array(
				"success" => false,
				"token" => $this->security->get_csrf_hash()
			));
		}
	}
}
