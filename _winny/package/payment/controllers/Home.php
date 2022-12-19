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
	 * Construct for this controller.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		$this->load->model('sales_model');
		$this->load->library('rajaongkir');

		$this->data['set'] = $this->setting->getSetting("semua");
		$production = (strpos($this->data['set']->midtrans_snap, "sandbox") == true) ? false : true;
		\Midtrans\Config::$serverKey = $this->data['set']->midtrans_server;
		\Midtrans\Config::$isProduction = $production;
		\Midtrans\Config::$isSanitized = true;
		\Midtrans\Config::$is3ds = true;
		
		// Add Xendit Api Key
		Xendit::setApiKey($this->set->xendit_server);
	}

    /*-------------------------------- MIDTRANS ------------------------------*/
    
	public function bayarulangpesanan()
	{
		if (isset($_POST["bayar"])) {
			$bayar = $this->setting->getBayar($_POST["bayar"], "semua");
			$orderId = $bayar->midtrans_id;
			$status = \Midtrans\Transaction::cancel($orderId);

			if ($status) {
				echo json_encode(["success" => true]);
			} else {
				echo json_encode(["success" => false]);
			}
		} else {
			echo json_encode(["success" => false]);
		}
	}

	function midtrans_webhook()
	{
		$notif = new \Midtrans\Notification();

		$transaction = $notif->transaction_status;
		$transaction_id = $notif->transaction_id;
		$type = $notif->payment_type;
		$fraud = $notif->fraud_status;
		$order_id = $notif->order_id;
		$bayarid = $this->setting->getBayar($notif->order_id, "id", "invoice");

		if ($transaction == 'capture') {
			if ($type == 'credit_card') {
				if ($fraud == 'challenge') {
					echo "Transaction order_id: " . $order_id . " is challenged by FDS";
				} else {
					$this->suksesTrxMidtrans($bayarid, $transaction_id);
					echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;
				}
			}
		} else if ($transaction == 'settlement') {
			$this->suksesTrxMidtrans($bayarid, $transaction_id);
			echo "Transaction order_id: " . $order_id . " successfully transfered using " . $type;
		} else if ($transaction == 'pending') {
			echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
		} else if ($transaction == 'deny') {
			echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
		} else if ($transaction == 'expire') {
			echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
		} else if ($transaction == 'cancel') {
			echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
		}
	}

	function suksesTrxMidtrans($bayarid, $transaction_id)
	{
		$this->db->where("id", $bayarid);
		$this->db->update("invoice", ["status" => 1, "tglupdate" => date("Y-m-d H:i:s"), "midtrans_id" => $transaction_id]);

		// UPDATE TRANSAKSI
		$this->sales_model->updateTransaksi(["idbayar"=> $bayarid], ["status" => 1]);

		$this->midtransSukses($bayarid);
	}

	function midtransSukses($bayar)
	{
		$bayar = $this->setting->getBayar($bayar, "semua");
		$trx = $this->sales_model->getTransaksi($bayar->id, "semua", "idbayar");
		$alamat = $this->setting->getAlamat($trx->alamat, "semua");
		$usr = $this->user_model->getUser($bayar->usrid, "semua");
		$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp " . $this->setting->formUang($bayar->diskon) . "</b><br/>" : "";
		$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp " . $this->setting->formUang($bayar->diskon) . "*\n" : "";
		$toko = $this->setting->getSetting("semua");

		$this->db->where("id", $bayar->id);
		$this->db->update("invoice", ["status" => 1, "tglupdate" => date("Y-m-d H:i:s")]);

		// UPDATE TRANSAKSI
		$this->sales_model->updateTransaksi(["idbayar"=> $bayar->id], ["status" => 1]);

		$pesan = "
			Halo <b>" . $usr->nama . "</b><br/>" .
			"Terimakasih, pembayaran untuk pesananmu sudah kami terima.<br/>" .
			"Mohon ditunggu, admin kami akan segera memproses pesananmu<br/>" .
			"<b>Detail Pesanan</b><br/>" .
			"No Invoice: <b>#" . $bayar->invoice . "</b><br/>" .
			"Total Pesanan: <b>Rp " . $this->setting->formUang($bayar->total) . "</b><br/>" .
			"Ongkos Kirim: <b>Rp " . $this->setting->formUang($trx->ongkir) . "</b><br/>" . $diskon .
			"Kurir Pengiriman: <b>" . strtoupper($trx->kurir . " " . $trx->paket) . "</b><br/> <br/>" .
			"Detail Pengiriman <br/>" .
			"Penerima: <b>" . $alamat->nama . "</b> <br/>" .
			"No HP: <b>" . $alamat->nohp . "</b> <br/>" .
			"Alamat: <b>" . $alamat->alamat . "</b>" .
			"<br/> <br/>" .
			"Cek Status pesananmu langsung di menu:<br/>" .
			"<a href='" . site_url("account/order") . "'>PESANANKU &raquo;</a>
		";
		$this->setting->sendEmail($usr->username, $toko->nama . " - Pesanan", $pesan, "Pesanan");
		$pesan = "
			Halo *" . $usr->nama . "* \n" .
			"Terimakasih, pembayaran untuk pesananmu sudah kami terima. \n" .
			"Mohon ditunggu, admin kami akan segera memproses pesananmu \n" .
			"*Detail Pesanan* \n" .
			"No Invoice: *#" . $bayar->invoice . "* \n" .
			"Total Pesanan: *Rp " . $this->setting->formUang($bayar->total) . "* \n" .
			"Ongkos Kirim: *Rp " . $this->setting->formUang($trx->ongkir) . "* \n" . $diskonwa .
			"Kurir Pengiriman: *" . strtoupper($trx->kurir . " " . $trx->paket) . "* \n  \n" .
			"Detail Pengiriman  \n" .
			"Penerima: *" . $alamat->nama . "* \n" .
			"No HP: *" . $alamat->nohp . "* \n" .
			"Alamat: *" . $alamat->alamat . "*" .
			" \n  \n" .
			"Cek Status pesananmu langsung di menu: \n" .
			"*PESANANKU*
		";
		$this->setting->sendWA($this->user_model->getProfil($usr->id, "nohp", "usrid"), $pesan);
	}

	public function cekmidtrans()
	{
		if (isset($_GET["bayar"])) {
			$bayar = $this->setting->getBayar($_GET["bayar"], "semua");
			$orderId = $bayar->midtrans_id;
			$status = \Midtrans\Transaction::status($orderId);
			print_r($status);
		} else {
			echo "ID Transaksi Pembayaran tidak valid";
		}
	}

	/**------------------------------------------------------------------------
	 * todo                             
	 * midtranstoken
	 * midtranspay
	 *   
	 *
	 *------------------------------------------------------------------------**/

    /*-------------------------------- IPAYMU ------------------------------*/
    
	function topupipaymu($order_id)
	{
		$bayar = $this->user_model->getSaldotarik($order_id, "semua");
		$total = $bayar->total;

		if ((!empty($bayar) and $bayar->ipaymu_link == "") or (!empty($bayar) and $bayar->ipaymu_link != "" and $bayar->ipaymu_tipe != "")) {
			$url = 'https://my.ipaymu.com/payment';
			$url = $this->data['set']->ipaymu_url != "" ? $this->data['set']->ipaymu_url : $url;
			$mobile = (isset($_GET["mobile"])) ? "&mobile=true" : "";
			$profil = $this->user_model->getProfil($bayar->usrid, "semua", "usrid");
			$params = array(
				'key'      => $this->data['set']->ipaymu, // API Key Merchant / Penjual
				'action'   => 'payment',
				'product'  => 'Order : #' . $bayar->trxid,
				'price'    => $total, // Total Harga
				'quantity' => 1,
				'reference_id' => $order_id,
				'comments' => 'Pembayaran topup saldo di ' . $this->data['set']->nama, // Optional           
				'ureturn'  => site_url("home/ipaymustatustopup") . '?id_order=' . $bayar->trxid . $mobile,
				'unotify'  => site_url("home/ipaymustatustopup") . '?id_order=' . $bayar->trxid . '&params=notify',
				'ucancel'  => site_url("home/ipaymustatustopup") . '?id_order=' . $bayar->trxid . '&params=cancel' . $mobile,
				'buyer_name' => $profil->nama,
				'buyer_phone' => $profil->nohp,
				'buyer_email' => $this->user_model->getUser($bayar->usrid, "username"),
				'format'   => 'json' // Format: xml / json. Default: xml 
			);
			$params_string = http_build_query($params);

			//open connection
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, count($params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			//execute post
			$request = curl_exec($ch);

			if ($request === false) {
				echo curl_error($ch);
			} else {

				$result = json_decode($request, true);
				if (isset($result['url'])) {
					$this->db->where("id", $order_id);
					$this->db->update("saldo_tarik", ["ipaymu" => $result['sessionID'], "ipaymu_link" => $result['url']]);

					redirect($result['url'], 'refresh');
				} else {
					redirect("home/topupsaldo?inv=" . $this->setting->arrEnc(array("trxid" => $bayar->trxid), "encode"));
					//$results = false;
					//echo "Request Error ". $result['Status'] .": ". $result['Keterangan'];
				}
			}

			//close connection
			curl_close($ch);
		} else if (!empty($bayar) and $bayar->ipaymu_link != "" and $bayar->ipaymu_tipe == "") {
			redirect($bayar->ipaymu_link, 'refresh');
			//echo "link lama";
		} else {
			redirect("home/topupsaldo?inv=" . $this->setting->arrEnc(array("trxid" => $bayar->trxid), "encode"));
		}
	}
	// Pesanan Ipaymu
	public function ipaymu($order_id)
	{
		$bayar = $this->setting->getBayar($order_id, "semua");
		$total = $bayar->transfer + $bayar->kodebayar;

		if ((!empty($bayar) and $bayar->ipaymu_link == "") or (!empty($bayar) and $bayar->ipaymu_link != "" and $bayar->ipaymu_tipe != "")) {
			$url = 'https://my.ipaymu.com/payment';
			$url = $this->set->ipaymu_url != "" ? $this->set->ipaymu_url : $url;
			$mobile = (isset($_GET["mobile"])) ? "&mobile=true" : "";
			$profil = $this->user_model->getProfil($bayar->usrid, "semua", "usrid");
			$params = array(
				'key'      => $this->set->ipaymu, // API Key Merchant / Penjual
				'action'   => 'payment',
				'product'  => 'Order : #' . $bayar->invoice,
				'price'    => $total, // Total Harga
				'quantity' => 1,
				'reference_id' => $order_id,
				'comments' => 'Pembayaran pesanan di ' . $this->set->nama, // Optional           
				'ureturn'  => site_url("home/ipaymustatus") . '?id_order=' . $bayar->invoice . $mobile,
				'unotify'  => site_url("home/ipaymustatus") . '?id_order=' . $bayar->invoice . '&params=notify',
				'ucancel'  => site_url("home/ipaymustatus") . '?id_order=' . $bayar->invoice . '&params=cancel' . $mobile,
				'buyer_name' => $profil->nama,
				'buyer_phone' => $profil->nohp,
				'buyer_email' => $this->user_model->getUser($bayar->usrid, "username"),
				'format'   => 'json' // Format: xml / json. Default: xml 
			);
			$params_string = http_build_query($params);

			//open connection
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, count($params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			//execute post
			$request = curl_exec($ch);

			if ($request === false) {
				echo curl_error($ch);
			} else {

				$result = json_decode($request, true);
				if (isset($result['url'])) {
					$this->db->where("id", $order_id);
					$this->db->update("invoice", ["ipaymu" => $result['sessionID'], "ipaymu_link" => $result['url']]);

					redirect($result['url'], 'refresh');
				} else {
					redirect("invoice?inv=" . $this->setting->arrEnc(array("idbayar" => $order_id), "encode"));
				}
			}

			//close connection
			curl_close($ch);
		} else if (!empty($bayar) and $bayar->ipaymu_link != "" and $bayar->ipaymu_tipe == "") {
			redirect($bayar->ipaymu_link, 'refresh');
			//echo "link lama";
		} else {
			redirect("invoice?inv=" . $this->setting->arrEnc(array("idbayar" => $order_id), "encode"));
		}
	}

	/*-------------------------------- XENDIT ------------------------------*/
	
	// Pesanan xendit
	public function xendit($order_id)
	{
		$bayar = $this->setting->getBayar($order_id, "semua");
		$total = $bayar->transfer + $bayar->kodebayar;
		$profil = $this->user_model->getProfil($bayar->usrid, "semua", "usrid");
		
		// Param Xendit
		$success_redirect_url = base_url() . 'invoice/home/callback/' . $order_id;
		$params = [
			'external_id' => $bayar->invoice,
			'amount' => $total, // Total Harga
			'description' => 'Pembayaran pesanan di ' . $this->set->nama, // Optional 
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
			'success_redirect_url' => $success_redirect_url
		];

		// Insert Xendit
		$createInvoice = \Xendit\Invoice::create($params);

		// update no invoice
		$this->db->where("id", $order_id);
		$this->db->update("invoice", [
			"xendit_id" => $createInvoice['id'],
			"xendit_url" => $createInvoice['invoice_url'],
		]);

		// invoice_url
		redirect( $createInvoice['invoice_url'], 'refresh');
	}
}