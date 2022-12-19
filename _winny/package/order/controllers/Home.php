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
	/**
	 * Construct
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('produk_model');
		$this->load->model('sales_model');
		$this->load->library('rajaongkir');
		$this->data['set'] = $this->setting->getSetting("semua");
	}

	/*------------------------------------------------------------------------*/
	
	public function index()
	{
		if ($this->setting->cekLogin() == true) {
			$this->data["data"] = $this->sales_model->getSaleByID();
			$this->data["saldo"] = $this->user_model->getSaldo($_SESSION["uid"], "saldo", "usrid");
			$this->data["prov"] = $this->rajaongkir->getProvinces()['rajaongkir']['results'];
			$this->data["voucher"] = $this->sales_model->getVoucherActive();
			$this->data['titel'] = "Pembayaran Pesanan";
	
			$this->_script = '_script';
			$this->view('order', $this->data);
		} else {
			redirect("auth");
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function save()
	{
		if (isset($_POST["total"]) and isset($_SESSION["uid"])) {
			// Set Total dan Kode Bayar
			$idbayar = 0;
			$kodebayaran = rand(100, 999);
			$kodebayar = $kodebayaran;
			$_POST["total"] = intval($_POST["total"]) - intval($_POST["diskon"]);
			$transfer = intval($_POST["total"]) - intval($_POST["saldo"]);
			if ($transfer > 0) {
				$total = $kodebayaran + intval($_POST["total"]);
			} else {
				$total = $_POST["total"];
				$kodebayar = 0;
			}

			// Set Status
			$seli = intval($_POST["saldo"]) - intval($_POST["total"]);
			$status = $seli >= 0 ? 1 : 0;
			$status = ($_POST["kurir"] == "cod") ? 1 : $status;

			// Set Alamat
			if ($_POST["alamat"] == "0") {
				$this->db->where("usrid", $_SESSION["uid"]);
				$statusal = ($this->db->get("user_alamat")->num_rows() > 0) ? 0 : 1;
				$alamat = array(
					"usrid"		=> $_SESSION["uid"],
					"status"	=> $statusal,
					"idkec"		=> $_POST["idkec"],
					"idkab"		=> $_POST["idkab"],
					"judul"		=> $_POST["judul"],
					"alamat"	=> $_POST["alamatbaru"],
					"kodepos"	=> $_POST["kodepos"],
					"nama"		=> $_POST["nama"],
					"nohp"		=> $_POST["nohp"]
				);
				$this->db->insert("user_alamat", $alamat);
				$idalamat = $this->db->insert_id();
			} else {
				$idalamat = $_POST["alamat"];
			}

			// Set voucher
			$voucher = $this->sales_model->getVoucher($_POST["kodevoucher"], "id", "kode");
			
			/**------------------------------------------------------------------------
			 *                           Insert To table Pembayaran
			 *------------------------------------------------------------------------**/
			$bayar = array(
				"usrid"      => $_SESSION["uid"],
				"tgl"        => date("Y-m-d H:i:s"),
				"total"      => $total,
				"saldo"      => $_POST["saldo"],
				"kodebayar"  => $kodebayar,
				"transfer"   => $transfer,
				"voucher"    => $voucher,
				"metode"     => $_POST["metode"],
				"diskon"     => $_POST["diskon"],
				"status"     => $status,
				"kadaluarsa" => date('Y-m-d H:i:s', strtotime("+2 days"))
			);
			$this->db->insert("invoice", $bayar);
			$idbayar = $this->db->insert_id();

			/**------------------------------------------------------------------------
			 *                           Update Saldo
			 *------------------------------------------------------------------------**/
			if ($_POST["metode"] == 2) {
				$saldoawal = $this->user_model->getSaldo($_SESSION["uid"], "saldo", "usrid");
				$saldoakhir = $saldoawal - intval($_POST["saldo"]);

				$update = array(
					"saldo" => $saldoakhir,
					"apdet" => date("Y-m-d H:i:s")
				);
				$this->user_model->updateSaldo($_SESSION["uid"], $update);

				$sh = array(
					"tgl"        => date("Y-m-d H:i:s"),
					"usrid"      => $_SESSION["uid"],
					"jenis"      => 2,
					"jumlah"     => $_POST["saldo"],
					"darike"     => 3,
					"sambung"    => $idbayar,
					"saldoawal"  => $saldoawal,
					"saldoakhir" => $saldoakhir
				);
				$this->user_model->saveSaldoHistory($sh);
			}

			/**------------------------------------------------------------------------
			 *                           Update no invoice di pembayaran
			 *------------------------------------------------------------------------**/
			$invoice = date("Ymd") . $idbayar . $kodebayaran;
			$this->db->where("id", $idbayar);
			$this->db->update("invoice", array("invoice" => $invoice));
			$invoice = "#" . $invoice;

			/**------------------------------------------------------------------------
			 *                           Insert Transaksi
			 *------------------------------------------------------------------------**/
			$transaksi = array(
				"orderid"    => "TRX" . date("YmdHis"),
				"tgl"        => date("Y-m-d H:i:s"),
				"kadaluarsa" => date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . ' + 2 days')),
				"usrid"      => $_SESSION["uid"],
				"alamat"     => $idalamat,
				"berat"      => $_POST["berat"],
				"ongkir"     => $_POST["ongkir"],
				"kurir"      => $_POST["kurir"],
				"paket"      => $_POST["paket"],
				"dari"       => $_POST["dari"],
				"tujuan"     => $_POST["tujuan"],
				"status"     => $status,
				"idbayar"    => $idbayar
			);

			// CEK DROPSHIP
			// if ($_POST["dropship"] != "") {
			// 	$transaksi["dropship"] = $_POST["dropship"];
			// 	$transaksi["dropshipnomer"] = $_POST["dropshipnomer"];
			// 	$transaksi["dropshipalamat"] = $_POST["dropshipalamat"];
			// }
			$this->sales_model->saveTransaksi($transaksi);
			$idtransaksi = $this->db->insert_id();

			/**------------------------------------------------------------------------
			 *                           Update Transaksi ITEM Produk
			 *------------------------------------------------------------------------**/
			for ($i = 0; $i < count($_POST["idproduk"]); $i++) {
				$this->sales_model->updateSale($_POST["idproduk"][$i], array("idtransaksi" => $idtransaksi));
			}

			/**------------------------------------------------------------------------
			 *                           UPDATE History STOK
			 *------------------------------------------------------------------------**/
			$items = [];
			$db = $this->sales_model->getSaleByTransaksi($idtransaksi);
			foreach ($db->result() as $r) {
				$produk = $this->produk_model->getProduk($r->idproduk, "semua");
				$item = array(
					"name"       => $produk->nama,
					"price"    => $r->harga,
					"quantity"   => $r->jumlah
				);
				$items[] = $item;

				if ($r->variasi != 0) {
					$var = $this->produk_model->getVariasi($r->variasi, "semua", "id");
					if ($r->jumlah > $var->stok) {
						echo json_encode(array("success" => false, "message" => "stok produk tidak mencukupi"));
						$stok = 0;
						exit;
					} else {
						$stok = $var->stok - $r->jumlah;
					}
					$variasi[] = $r->variasi;
					$stock[] = $stok;
					$stokawal[] = $var->stok;
					$jml[] = $r->jumlah;

					for ($i = 0; $i < count($variasi); $i++) {
						$update = ["stok" => $stock[$i], "tgl" => date("Y-m-d H:i:s")];
						$this->produk_model->updateVariasi($variasi[$i], $update);

						$data = array(
							"usrid"       => $_SESSION["uid"],
							"stokawal"    => $stokawal[$i],
							"stokakhir"   => $stock[$i],
							"variasi"     => $variasi[$i],
							"jumlah"      => $jml[$i],
							"tgl"         => date("Y-m-d H:i:s"),
							"idtransaksi" => $idtransaksi
						);
						$this->db->insert("history_stok", $data);
					}
				} else {
					$pro = $this->produk_model->getProduk($r->idproduk, "semua");
					if ($r->jumlah > $pro->stok) {
						echo json_encode(array(
							"success" => false,
							"message" => "stok produk tidak mencukupi"
						));
						$stok = 0;
						exit;
					}
					$stok = $pro->stok - $r->jumlah;
					$this->produk_model->updateProduk(
						$r->idproduk,
						["stok" => $stok, "tglupdate" => date("Y-m-d H:i:s")]
					);

					$data = array(
						"usrid"       => $_SESSION["uid"],
						"stokawal"    => $pro->stok,
						"stokakhir"   => $stok,
						"variasi"     => 0,
						"jumlah"      => $r->jumlah,
						"tgl"         => date("Y-m-d H:i:s"),
						"idtransaksi" => $idtransaksi
					);
					$this->db->insert("history_stok", $data);
				}
			}

			/**------------------------------------------------------------------------
			 *                           Send Notification
			 *------------------------------------------------------------------------**/
			$usrid    = $this->user_model->getUser($_SESSION["uid"], "semua");
			$profil   = $this->user_model->getProfil($_SESSION["uid"], "semua", "usrid");
			$alamat   = $this->setting->getAlamat($idalamat, "semua");
			$toko     = $this->setting->getSetting("semua");
			$diskon   = $_POST["diskon"] != 0 ? "Diskon: <b>Rp " . $this->setting->formUang(intval($_POST["diskon"])) . "</b><br/>" : "";
			$diskonwa = $_POST["diskon"] != 0 ? "Diskon: *Rp " . $this->setting->formUang(intval($_POST["diskon"])) . "*\n" : "";
			$pesan = "
					Halo <b>" . $profil->nama . "</b><br/>" .
				"Terimakasih sudah membeli produk kami.<br/>" .
				"Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>" .
				"No Invoice: <b>" . $invoice . "</b><br/> <br/>" .
				"Total Pesanan: <b>Rp " . $this->setting->formUang($total) . "</b><br/>" .
				"Ongkos Kirim: <b>Rp " . $this->setting->formUang(intval($_POST["ongkir"])) . "</b><br/>" . $diskon .
				"Kurir Pengiriman: <b>" . strtoupper($_POST["kurir"] . " " . $_POST["paket"]) . "</b><br/> <br/>" .
				"Detail Pengiriman <br/>" .
				"Penerima: <b>" . $alamat->nama . "</b> <br/>" .
				"No HP: <b>" . $alamat->nohp . "</b> <br/>" .
				"Alamat: <b>" . $alamat->alamat . "</b>" .
				"<br/> <br/>" .
				"Untuk pembayaran silahkan langsung klik link berikut:<br/>" .
				"<a href='" . site_url("home/invoice") . "?inv=" . $this->setting->arrEnc($idbayar, "encode") . "'>Bayar Pesanan Sekarang &raquo;</a>
				";
			$this->setting->sendEmail($usrid->username, $toko->nama . " - Pesanan", $pesan, "Pesanan");
			$pesan = "
					Halo *" . $profil->nama . "*\n" .
				"Terimakasih sudah membeli produk kami.\n" .
				"Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \n" .
				"No Invoice: *" . $invoice . "*\n" .
				"Total Pesanan: *Rp " . $this->setting->formUang($total) . "*\n" .
				"Ongkos Kirim: *Rp " . $this->setting->formUang(intval($_POST["ongkir"])) . "*\n" . $diskonwa .
				"Kurir Pengiriman: *" . strtoupper($_POST["kurir"] . " " . $_POST["paket"]) . "*\n \n" .
				"Detail Pengiriman \n" .
				"Penerima: *" . $alamat->nama . "*\n" .
				"No HP: *" . $alamat->nohp . "*\n" .
				"Alamat: *" . $alamat->alamat . "*\n \n" .
				"Untuk pembayaran silahkan langsung klik link berikut\n" . site_url("home/invoice") . "?inv=" . $this->setting->arrEnc($idbayar, "encode") . "
				";
			$this->setting->sendWA($profil->nohp, $pesan);
			$this->setting->sendWAOK($profil->nohp, $pesan);
			$pesan = "
					<h3>Pesanan Baru</h3><br/>
					<b>" . strtoupper(strtolower($profil->nama)) . "</b> telah membuat pesanan baru dengan total pembayaran 
					<b>Rp. " . $this->setting->formUang($total) . "</b> Invoice ID: <b>" . $invoice . "</b>
					<br/>&nbsp;<br/>&nbsp;<br/>
					Cek Pesanan Pembeli di <b>Dashboard Admin " . $toko->nama . "</b><br/>
					<a href='" . site_url("cdn") . "'>Klik Disini</a>
				";
			$this->setting->sendEmail($toko->email, $toko->nama . " - Pesanan Baru", $pesan, "Pesanan Baru di " . $toko->nama);
			$pesan = "
					*Pesanan Baru*\n" .
				"*" . strtoupper(strtolower($profil->nama)) . "* telah membuat pesanan baru dengan detail:\n" .
				"Total Pembayaran: *Rp. " . $this->setting->formUang($total) . "*\n" .
				"Invoice ID: *" . $invoice . "*" .
				"\n \n" .
				"Cek Pesanan Pembeli di *Dashboard Admin " . $toko->nama . "*
					";
			$this->setting->sendWA($toko->wasap, $pesan);
			$this->setting->sendWAOK($toko->wasap, $pesan);

			/**------------------------------------------------------------------------
			 *                           Send To Xendit
			 *------------------------------------------------------------------------**/
			Xendit::setApiKey($this->data['set']->xendit_server);
			// amount
			$bayar = $this->setting->getBayar($idbayar, "semua");

			// Param Xendit
			$success_redirect_url = base_url() . 'invoice/home/callback/' . $bayar->invoice;
			$params = [
				'external_id' => $bayar->invoice,
				'amount' => $total, // Total Harga
				'description' => 'Pembayaran pesanan di ' . $this->data['set']->nama, // Optional 
				'customer' => [
					'given_names' => $profil->nama,
					'email' => $this->user_model->getUser($bayar->usrid, "username"),
					'mobile_number' => $profil->nohp
				],
				'customer_notification_preference' => [
					'invoice_created' => ["email"],
					'invoice_reminder' => ["email"],
					'invoice_paid' => ["email"],
					'invoice_expired' => ["email"],
				],
				'items' => $items,
				'success_redirect_url' => $success_redirect_url
			];

			// Insert Xendit
			$createInvoice = \Xendit\Invoice::create($params);

			// update no invoice
			$this->db->where("id", $idbayar);
			$this->db->update("invoice", [
				"xendit_id" => $createInvoice['id'],
				"xendit_url" => $createInvoice['invoice_url'],
			]);

			$url = $status == 0 ? site_url("invoice") . "?inv=" . $this->setting->arrEnc($idbayar, "encode") : site_url("account/order");
			echo json_encode(array("success" => true, "url" => $url));
		} else {
			echo json_encode(array("success" => false, "message" => "forbidden"));
		}
	}
}
