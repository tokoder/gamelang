<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Order extends MY_Controller
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
		
		$this->data['set'] = $this->setting->getSetting("semua");
	}

	/*------------------------------------------------------------------------*/

	public function index()
	{
		if ($this->setting->cekLogin() == true) {
			if ($_SESSION["status"] == 1) {
				$this->data['rekening'] = $this->user_model->getRekeningById($_SESSION["uid"]);
				$this->data['rekening_bank'] = $this->db->get("@rekening_bank");
				$this->data['profil'] = $this->user_model->getProfil($_SESSION["uid"], "semua", "usrid");
				$this->data['user'] = $this->user_model->getUser($_SESSION["uid"], "semua");
				$this->data['titel'] = 'Status Pesanan';

				$this->view("order", $this->data);
			} else {
				$this->session->sess_destroy();
				redirect("auth");
				exit;
			}
		} else {
			redirect("auth");
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function status()
	{
		if (isset($_GET["status"])) {
			$this->load->model('produk_model');
			if ($_GET["status"] == "belumbayar") {
				$this->load->view("order/bayar");
			} elseif ($_GET["status"] == "dikemas") {
				$this->load->view("order/dikemas");
			} elseif ($_GET["status"] == "selesai") {
				$this->load->view("order/selesai");
			} elseif ($_GET["status"] == "dikirim") {
				$this->load->view("order/dikirim");
			} elseif ($_GET["status"] == "batal") {
				$this->load->view("order/batal");
			} elseif ($_GET["status"] == "preorder") {
				$this->load->view("order/preorder");
			} else {
				redirect("404_notfound");
			}
		} else {
			redirect("404_notfound");
		}
	}
	
	/*------------------------------------------------------------------------*/
	
	public function batal($by = "user")
	{
		if (isset($_POST["pesanan"])) {
			$data = array(
				"tglupdate"	=> date("Y-m-d H:i:s"),
				"status"	=> 3
			);
			$this->db->where("id", $_POST["pesanan"]);
			if ($this->db->update("invoice", $data)) {
				if ($by == "penjual") {
					// PENGEMBALIAN SALDO
					// $this->setting->notifbatal($_POST["pesanan"], 1);
					$batal = "dibatalkan oleh penjual.";
				} else {
					// $this->setting->notifbatal($_POST["pesanan"], 2);
					$batal = "dibatalkan oleh pembeli.";
				}
				$this->db->where("idbayar", $_POST["pesanan"]);
				$this->db->update("sales", array(
					"status" => 4, 
					"selesai" => date("Y-m-d H:i:s"), 
					"keterangan" => $batal));

				echo json_encode(array(
					"success" => true, 
					"message" => "berhasil membatalkan pesanan", 
					"token" => $this->security->get_csrf_hash()));
			} else {
				echo json_encode(array(
					"success" => false, 
					"message" => "gagal membatalkan pesanan, coba ulangi beberapa saat lagi", 
					"token" => $this->security->get_csrf_hash()));
			}
		} else {
			echo json_encode(array(
				"success" => false, 
				"message" => "Forbidden Access", 
				"token" => $this->security->get_csrf_hash()));
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function detail()
	{
		// CEK LOGIN
		if ($this->setting->cekLogin() == true) {
			// CEK STATUS
			if ($_SESSION["status"] == 1) {
				// GET PARAM ORDER ID
				if (isset($_GET["orderid"])) {
					// GET TRANSAKSI BY PARAM ORDER ID
					$transaksi = $this->sales_model->getTransaksi($_GET["orderid"], "semua", "orderid");
					// GET BAYAR BY IDBAYAR FROM TRANSAKSI
					$bayar = $this->setting->getBayar($transaksi->idbayar, "semua");

					$this->data['transaksi'] = $transaksi;
					$this->data['bayar'] = $bayar;
					$this->data['titel'] = 'Rincian Pesanan';
					$this->view("order/detail", $this->data);
				} 
				// JIKA PARAM TIDAK DITEMUKAN
				else {
					echo "<script>history.back();</script>";
				}
			} 
			// JIKA STATUS TIDAK TERVERIFIKASI
			else {
				$this->session->sess_destroy();
				redirect("auth");
				exit;
			}
		} 
		// DIRECT KE LAMAN LOGIN
		else {
			redirect("auth");
		}
	}

	/*------------------------------------------------------------------------*/

	public function ulasan($orderid = 0)
	{
		if ($this->setting->cekLogin() == true) {
			if ($_SESSION["status"] == 1) {
				$this->data['orderid'] = $orderid;
				$this->data['titel'] = 'Ulasan Produk';
				$this->view("review", $this->data);
			} else {
				$this->session->sess_destroy();
				redirect("auth");
				exit;
			}
		} else {
			redirect("auth");
		}
	}
}
