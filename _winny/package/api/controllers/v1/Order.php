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

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
		$this->load->library('rajaongkir');
	}

	/*------------------------------------------------------------------------*/
	
	function update()
	{
		if (isset($_POST["id"])) {
			//$_POST["id"] = intval(["id"]);
			$status = isset($_POST["status"]) ? $_POST["status"] : 1;

			if (isset($_POST["statusbayar"])) {
				if ($_POST["statusbayar"] == 1) {
					$this->setting->notifsukses($_POST["id"]);
				}

				$this->db->where("id", intval($_POST["id"]));
				$this->db->update("invoice", ["status" => intval($_POST["statusbayar"]), "tglupdate" => date("Y-m-d H:i:s")]);
			}

			if ($status >= 3) {
				$data = ["status" => $status, "selesai" => date("Y-m-d H:i:s")];
			} else {
				$data = ["status" => $status];
			}

			$this->db->where("idbayar", intval($_POST["id"]));
			$this->db->update("sales", $data);

			echo json_encode(array(
				"success" => true, 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"token" => $this->security->get_csrf_hash()));
		}
	}

	/*------------------------------------------------------------------------*/
	
	function batalkan()
	{
		if (isset($_POST["id"])) {
			//$_POST["id"] = intval(["id"]);
			$this->setting->notifbatal($_POST["id"], 1);
			$trx = $this->setting->getTransaksi(intval($_POST["id"]), "semua", "idbayar");
			$variasi = [];
			$this->db->where("idtransaksi", $trx->id);
			$db = $this->db->get("sales_produk");
			foreach ($db->result() as $r) {
				if ($r->variasi > 0) {
					$var = $this->setting->getVariasi($r->variasi, "semua", "id");
					if (isset($var->stok)) {
						$stok = $var->stok + $r->jumlah;
						$variasi[] = $r->variasi;
						$stock[] = $stok;
						$stokawal[] = $var->stok;
						$jml[] = $r->jumlah;
					}
				} else {
					$pro = $this->setting->getProduk($r->idproduk, "semua");
					$stok = $pro->stok + $r->jumlah;
					$this->db->where("id", $r->idproduk);
					$this->db->update("produk", ["stok" => $stok, "tglupdate" => date("Y-m-d H:i:s")]);

					$data = array(
						"usrid"	=> $trx->usrid,
						"stokawal" => $pro->stok,
						"stokakhir" => $stok,
						"variasi" => 0,
						"jumlah" => $r->jumlah,
						"tgl"	=> date("Y-m-d H:i:s"),
						"idtransaksi" => $trx->id
					);
					$this->db->insert("history_stok", $data);
				}
			}
			for ($i = 0; $i < count($variasi); $i++) {
				$this->db->where("id", $variasi[$i]);
				$this->db->update("produk_variasi", ["stok" => $stock[$i], "tgl" => date("Y-m-d H:i:s")]);

				$data = array(
					"usrid"	=> $trx->usrid,
					"stokawal" => $stokawal[$i],
					"stokakhir" => $stock[$i],
					"variasi" => $variasi[$i],
					"jumlah" => $jml[$i],
					"tgl"	=> date("Y-m-d H:i:s"),
					"idtransaksi" => $trx->id
				);
				$this->db->insert("history_stok", $data);
			}

			$this->db->where("id", intval($_POST["id"]));
			$this->db->update("invoice", ["status" => 3, "tglupdate" => date("Y-m-d H:i:s")]);

			$this->db->where("idbayar", intval($_POST["id"]));
			$this->db->update("sales", ["status" => 4, "selesai" => date("Y-m-d H:i:s")]);

			echo json_encode(array(
				"success" => true, 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"token" => $this->security->get_csrf_hash()));
		}
	}

	/*------------------------------------------------------------------------*/
	
	function selesai()
	{
		if (isset($_POST["id"])) {

			$data = array(
				"selesai" => date("Y-m-d H:i:s"),
				"status" => 3
			);

			$this->db->where("id", intval($_POST["id"]));
			$this->db->update("sales", $data);

			echo json_encode(array(
				"success" => true, 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"token" => $this->security->get_csrf_hash()));
		}
	}

	/*------------------------------------------------------------------------*/
	
	function inputresi()
	{
		if (isset($_POST["theid"])) {
			//$_POST["theid"] = intval(["theid"]);
			$trx = $this->setting->getTransaksi(intval($_POST["theid"]), "semua");
			$usrid = $this->user_model->getUser($trx->usrid, "semua");

			$status = $trx->kurir != "cod" ? 2 : 3;
			$resi = (isset($_POST["resi"])) ? $_POST["resi"] : "";
			$data = array(
				"resi" => $resi,
				"kirim" => date("Y-m-d H:i:s"),
				"status" => $status
			);
			if ($status == 3) {
				$data["selesai"] = date("Y-m-d H:i:s");
			}

			$this->db->where("id", intval($_POST["theid"]));
			$this->db->update("sales", $data);

			$namatoko = $this->setting->globalset("nama");

			if ($trx->kurir != "cod" and $trx->kurir != "toko") {
				$pesan = "
					Berikut resi pengiriman untuk pesanan anda di <b>" . $namatoko . "</b><br/>
					Resi: <b style='font-size:120%'>" . $resi . "</b><br/>&nbsp;<br/>&nbsp;<br/>
					Lacak pengirimannya langsung di menu <b>pesananku</b><br/>
					<a href='" . $this->setting->mainsite_url('account/order') . "'>Klik Disini</a>
				";
				$this->setting->sendEmail($usrid->username, $namatoko . " - Resi Pengiriman Pesanan", $pesan, "Resi Pengiriman");
				$pesan = "
					Berikut resi pengiriman untuk pesanan anda di *" . $namatoko . "* \n" .
					"Resi: *" . $resi . "* \n \n" .
					"Lacak pengirimannya langsung di menu *pesananku* \n " .
					$this->setting->mainsite_url('account/order') . "
				";
				$this->setting->sendWA($this->user_model->getProfil($trx->usrid, "nohp", "usrid"), $pesan);
			} else {
				$pesan = "
					Pesanan Anda di <b>" . $namatoko . "</b> akan segera kami kirimkan<br/>
					Kurir Toko: <b style='font-size:120%'>" . $resi . "</b><br/>&nbsp;<br/>&nbsp;<br/>
					Untuk waktu pengiriman bisa langsung menghubungi kurir kami, atau juga bisa ditanyakan ke admin kami di web<br/>
					<a href='" . $this->setting->mainsite_url('manage') . "'>Klik Disini</a>
				";
				$this->setting->sendEmail($usrid->username, $namatoko . " - Pengiriman Pesanan", $pesan, "Informasi Pengiriman");
				$pesan = "
					Pesanan Anda di *" . $namatoko . "* akan segera kami kirimkan \n" .
					"Kurir Toko: *" . $resi . "* \n \n" .
					"Untuk waktu pengiriman bisa langsung menghubungi kurir kami, atau juga bisa ditanyakan ke admin kami di web \n" .
					$this->setting->mainsite_url('manage') . "
				";
				$this->setting->sendWA($this->user_model->getProfil($trx->usrid, "nohp", "usrid"), $pesan);
			}

			echo json_encode(array(
				"success" => true, 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"token" => $this->security->get_csrf_hash()));
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function lacakiriman()
	{
		if (isset($_GET["orderid"])) {
			$trx = $this->setting->getTransaksi($_GET["orderid"], "semua", "orderid");
			// $response = $this->rajaongkir->getWaybill($trx);
			//print_r();
			// if ($response->rajaongkir->status->code == "200") {
			// 	$respon = $response->rajaongkir->result->manifest;
			// 	if ($response->rajaongkir->result->delivered == true) {
			// 		if ($trx->status < 3) {
			// 			$this->db->where("id", $trx->id);
			// 			$this->db->update("transaksi", ["status" => 3, "selesai" => date("Y-m-d H:i:s")]);
			// 		}
			// 		echo "
			// 			<div class='m-b-30'>
			// 				Status: <b style='color:#28a745;'>PAKET TELAH DITERIMA</b><br/>
			// 				Penerima: <b>" . strtoupper(strtolower($response->rajaongkir->result->delivery_status->pod_receiver)) . "</b><br/>
			// 				Tgl diterima: " . $this->setting->ubahTgl("d M Y H:i", $response->rajaongkir->result->delivery_status->pod_date . " " . $response->rajaongkir->result->delivery_status->pod_time) . " WIB
			// 			</div>
			// 		";
			// 	} else {
			// 		echo "<div class='m-b-30'>Status: <b style='color:#c0392b;'>PAKET SEDANG DIKIRIM</b></div>";
			// 	}

			// 	echo "
			// 		<div class='row p-tb-10' style='border-bottom: 1px solid #ccc;font-weight:bold;'>
			// 			<div class='col-md-3'>TANGGAL</div>
			// 			<div class='col-md-9'>STATUS</div>
			// 		</div>
			// 	";

			// 	if ($response->rajaongkir->result->delivered == true and $response->rajaongkir->query->courier != "jne") {
			// 		echo "
			// 			<div class='row p-tb-10' style='border-bottom: 1px dashed #ccc;'>
			// 				<div class='col-md-3'>
			// 					<i>" . $this->setting->ubahTgl("d/m/Y H:i", $response->rajaongkir->result->delivery_status->pod_date . " " . $response->rajaongkir->result->delivery_status->pod_time) . "WIB</i>
			// 				</div>
			// 				<div class='col-md-9'>
			// 					<i>Diterima oleh " . strtoupper(strtolower($response->rajaongkir->result->delivery_status->pod_receiver)) . "</i>
			// 				</div>
			// 			</div>
			// 		";
			// 	}

			// 	for ($i = 0; $i < count($respon); $i++) {
			// 		//print_r($respon[$i])."<p/>";
			// 		echo "
			// 			<div class='row p-tb-10' style='border-bottom: 1px dashed #ccc;'>
			// 				<div class='col-md-3'>
			// 					<i>" . $this->setting->ubahTgl("d/m/Y H:i", $respon[$i]->manifest_date . " " . $respon[$i]->manifest_time) . " WIB</i>
			// 				</div>
			// 				<div class='col-md-9'>
			// 					<i>" . $respon[$i]->manifest_description . "</i>
			// 					<i>" . $respon[$i]->city_name . "</i>
			// 				</div>
			// 			</div>
			// 		";
			// 	}
			// } else {
			// }
			echo "
				<div class='row p-tb-10' style='border-bottom: 1px dashed #ccc;'>
					<div class='col-md-12'>
						Nomor Resi tidak ditemukan, coba ulangi beberapa jam lagi sampai resi sudah update di sistem pihak ekspedisi.
					</div>
				</div>
			";
		} else {
			echo "<span class='label label-red'><i class='fa fa-exclamation-triangle'></i> terjadi kesalahan sistem, silahkan ualngi beberapa saat lagi.</span>";
		}
	}
}