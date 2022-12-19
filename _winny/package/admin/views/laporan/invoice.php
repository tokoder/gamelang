<!DOCTYPE html>
<html>

<head>
	<style>
		#invoice-POS {
			/* box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); */
			padding: 2mm;
			margin: 0 auto;
			width: 44mm;
			background: #fff;
		}

		#invoice-POS ::selection {
			background: #f31544;
			color: #fff;
		}

		#invoice-POS ::moz-selection {
			background: #f31544;
			color: #fff;
		}

		#invoice-POS h1 {
			font-size: 1.5em;
			color: #222;
		}

		#invoice-POS h2 {
			font-size: 0.9em;
		}

		#invoice-POS h3 {
			font-size: 1.2em;
			font-weight: 300;
			line-height: 2em;
			margin-bottom: 0;
		}

		#invoice-POS p {
			font-size: 0.7em;
			color: #666;
			line-height: 1.2em;
		}

		#invoice-POS #top,
		#invoice-POS #mid,
		#invoice-POS #bot {
			/* Targets all id with 'col-' */
			border-bottom: 1px solid #eee;
		}

		#invoice-POS #top {
			/* min-height: 100px; */
		}

		#invoice-POS #mid {
			/* min-height: 80px; */
		}

		#invoice-POS #bot {
			min-height: 50px;
		}

		/* #invoice-POS #top .logo {
			float: left;
			height: 60px;
			width: 60px;
			background-size: 60px 60px;
		}

		#invoice-POS .clientlogo {
			float: left;
			height: 60px;
			width: 60px;
			background-size: 60px 60px;
			border-radius: 50px;
		} */

		#invoice-POS .info {
			display: block;
			margin-left: 0;
			font-size: 0.7em;
		}

		#invoice-POS .title {
			float: right;
		}

		#invoice-POS .title p {
			text-align: right;
		}

		#invoice-POS table {
			width: 100%;
			border-collapse: collapse;
		}

		#invoice-POS .tabletitle {
			font-size: 0.5em;
			background: #eee;
		}

		#invoice-POS .service {
			border-bottom: 1px solid #eee;
		}

		#invoice-POS .item {
			width: 24mm;
		}

		#invoice-POS .itemtext {
			font-size: 0.5em;
		}

		#invoice-POS #legalcopy {
			margin-top: 5mm;
		}
	</style>
</head>

<body onload="window.print();setTimeout(function(){window.close();},3000);">
	<div class="nota">
		<?php
		$trxid = (isset($_GET["id"])) ? intval($_GET["id"]) : 0;
		if ($trxid != 0) {
			$trx = $this->setting->getTransaksi($trxid, "semua");
			$byr = $this->setting->getBayar($trx->idbayar, "semua");

			$alamat = $this->setting->getAlamat($trx->alamat, "semua");
			$kab = '-';
			$prov = '-';
			$lkp = '-';
			if (isset($alamat->idkab) && $alamat->idkab != null) {
				$kab = $this->rajaongkir->getCity($alamat->idkab)['rajaongkir']['results'];
				$prov = $this->rajaongkir->getProvince($kab['province_id'])['rajaongkir']['results']['province'];
				$lkp = $kab['city_name'] . " " . $prov . " " . $alamat->kodepos;
			}
		?>
			<div id="invoice-POS">

				<center id="top">
					<?php if($trx->dropship == ""){ ?>
						<img src="<?=base_url($this->setting->globalset("logo"))?>" class="logo" style="width: 30%;" />
					<?php } ?>
					<div class="info">
						<h2><?= $this->setting->globalset("nama") ?></h2>
						<p>
							<b style="font-size:120%;"><i class="fas fa-file-invoice"></i> Nota #<?= $trx->orderid ?></b></br>
							<?= $this->setting->ubahTgl("D, d M Y", $trx->tgl) ?></br>
						</p>
						<!-- <table>
							<tr><th>Agen</td><td><?= $this->user_model->getUser($trx->usrid, "nama") ?></th></tr>
							<tr><th>Invoice</th><th>#<?= $trx->orderid ?></th></tr>
						</table> -->
					</div>
					<!--End Info-->
				</center>
				<!--End InvoiceTop-->

				<div id="mid">
					<div class="info">
						<?php if ($trx->dropship == "") { ?>
							<h2>Pengirim</h2>
							<p>
								<b style="font-size:120%;"><?= $this->setting->globalset("nama") ?></b></br>
								Address : <?= $this->rajaongkir->getCity($this->setting->globalset("kota"))['rajaongkir']['results']['city_name']; ?></br>
								Phone : <?= $this->setting->globalset("notelp") ?></br>
							</p>
						<?php } else { ?>
							<h2>Pengirim</h2>
							<p>
								<b style="font-size:120%;"><?= $trx->dropship ?></b></br>
								Address : <?= $trx->dropshipalamat; ?></br>
								Phone : <?= $trx->dropshipnomer ?></br>
							</p>
						<?php } ?>
					</div>
				</div>
				<div id="mid">
					<div class="info">
						<h2>Penerima</h2>
						<p>
							<b style="font-size:120%;"><?= $alamat->nama ?></b></br>
							Address : <?= $alamat->alamat ?></br>
							Phone : <?= $alamat->nohp ?></br>
						</p>
					</div>
				</div>
				<!--End Invoice Mid-->

				<div id="bot">

					<div id="table">
						<table>
							<tr class="tabletitle">
								<td class="item">
									<h2>Item</h2>
								</td>
								<td class="Hours">
									<h2>Qty</h2>
								</td>
								<td class="Rate">
									<h2>Sub Total</h2>
								</td>
							</tr>

							<?php
							//$this->db->select("SUM(jumlah) as jml,idproduk,harga,jumlah,diskon
							$this->db->where("idtransaksi", $trx->id);
							$db = $this->db->get("sales_produk");
							$total = 0;
							$totalqty = 0;
							$totalberat = 0;
							$ket = "";
							foreach ($db->result() as $r) {
								$prod = $this->setting->getProduk($r->idproduk, "semua");
								$total += ($r->diskon + $r->harga) * $r->jumlah;
								$totalberat += !empty($prod) ? $prod->berat : 0;
								$berat = !empty($prod) ? $prod->berat : 0;
								$kode = !empty($prod) ? $prod->kode : 0;
								$nama = !empty($prod) ? $prod->nama : "Produk dihapus";
								$totalqty += $r->jumlah;
								$ket .= $r->keterangan . "<br/>";
								echo '
									<tr class="service">
										<td class="tableitem">
											<p class="itemtext">' . $nama . '</p>
										</td>
										<td class="tableitem">
											<p class="itemtext">' . $r->jumlah . '</p>
										</td>
										<td class="tableitem">
											<p class="itemtext">' . $this->setting->formUang(($r->diskon + $r->harga) * $r->jumlah) . '</p>
										</td>
									</tr>';
							}
							?>

							<tr class="tabletitle">
								<td></td>
								<td class="Rate">
									<h2><?= $totalqty ?></h2>
								</td>
								<td class="payment">
									<h2><?= $this->setting->formUang($total) ?></h2>
								</td>
							</tr>
							<tr><td></td></tr>
							<tr class="tabletitle">
								<td></td>
								<td class="Rate">
									<h2>Ongkir <?= strtoupper(strtolower($trx->kurir . " " . $trx->paket)) ?></h2>
								</td>
								<td class="payment">
									<h2><?= $this->setting->formUang($trx->ongkir) ?></h2>
								</td>
							</tr>
							<tr class="tabletitle">
								<td></td>
								<td class="Rate">
									<h2>Diskon</h2>
								</td>
								<td class="payment">
									<h2>-<?= $this->setting->formUang($byr->diskon) ?></h2>
								</td>
							</tr>
							<tr class="tabletitle">
								<td></td>
								<td class="Rate">
									<h2>Grand Total</h2>
								</td>
								<td class="payment">
									<h2><?= $this->setting->formUang($total + $trx->ongkir - $byr->diskon) ?></h2>
								</td>
							</tr>

						</table>
					</div>
					<!--End Table-->

					<div id="legalcopy">
						<p>KETERANGAN:<br /> <small><?= $ket ?></small></p>
						<p class="legal"><strong>Thank you for your business!</strong>
						</p>
					</div>

				</div>
				<!--End InvoiceBot-->
			</div>
			<!--End Invoice-->

		<?php
		}
		?>
	</div>
</body>

</html>