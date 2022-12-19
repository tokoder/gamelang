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

use Xendit\Xendit;

class Home extends MY_Controller
{
	private $set;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		$this->load->model('sales_model');
		$this->load->library('rajaongkir');

		// Add Xendit Api Key
		$this->data['set'] = $this->setting->getSetting("semua");
		Xendit::setApiKey($this->data['set']->xendit_server);
	}

	/*------------------------------------------------------------------------*/
	
	// Lihat Invoice
	public function index()
	{
		if (isset($_GET["inv"])) {
			$idbayar = $this->setting->arrEnc($_GET["inv"], "decode");
			if (intval($idbayar) == 0) {
				redirect("404_index");
				exit;
			}
			if (isset($_GET["revoke"])) {
				$byr = $this->setting->getBayar($idbayar, "semua");
				$this->db->where("id", $idbayar);
				$this->db->update("invoice", ["invoice" => $byr->invoice . date("Hi"), "midtrans_id" => ""]);
			}

			//TRANSAKSI
			$transaksi = $this->sales_model->getTransaksi($idbayar, "semua", "idbayar");
			$this->data["data"] = $this->setting->getBayar($idbayar, "semua");
			$this->data["usrid"] = $this->user_model->getUser($this->data["data"]->usrid, "semua");
			$this->data["profil"] = $this->user_model->getProfil($this->data["data"]->usrid, "semua", "usrid");
			$this->data["transaksi"] = array($transaksi);
			$this->data["alamat"] = $this->setting->getAlamat($transaksi->alamat, "semua");
			$this->data["bank"] = $this->user_model->getRekeningJoin();

			// STATUS
			$this->data["status"] = \Xendit\Invoice::retrieve($this->data["data"]->xendit_id);
			$this->data['titel'] = "Informasi Pembayaran Pesanan";
	
			$this->_script = '_script';
			$this->view('invoice', $this->data);
		} else {
			redirect();
		}
	}

	/*------------------------------------------------------------------------*/
	
	// Callback Invoice Response 
	public function callback($invoice)
	{
		// Get Pembayaran by Order ID
		$bayar = $this->setting->getBayar($invoice, "semua", 'invoice');

		// Get Invoice from xendit official
		$getInvoice = \Xendit\Invoice::retrieve($bayar->xendit_id);

		if ($getInvoice['status'] == 'SETTLED') {
			$this->db->where("id", $bayar->id);
			$this->db->update("invoice", ["status" => 1]);

			// SETTLED TRANSAKSI
			$this->sales_model->updateTransaksi($bayar->id, ["status" => 1]);
		} 
		else if ($getInvoice['status'] == 'PAID') {
			$this->db->where("id", $bayar->id);
			$this->db->update("invoice", ["status" => 1]);

			// PAID TRANSAKSI
			$this->sales_model->updateTransaksi($bayar->id, ["status" => 1]);
		} 
		else if ($getInvoice['status'] == 'PENDING') {
			$this->db->where("id", $bayar->id);
			$this->db->update("invoice", ["status" => 0]);

			// PENDING TRANSAKSI
			$this->sales_model->updateTransaksi($bayar->id, ["status" => 0]);
		} 
		else if ($getInvoice['status'] == 'EXPIRED') {
			$this->db->where("id", $bayar->id);
			$this->db->update("invoice", ["status" => 3]);

			// EXPIRED TRANSAKSI
			$this->sales_model->updateTransaksi($bayar->id, ["status" => 4]);
		}

		redirect("account/order");
	}
}
