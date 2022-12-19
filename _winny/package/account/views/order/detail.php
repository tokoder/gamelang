<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */
?>

<!-- Page Title
============================================= -->
<section id="page-title" class="page-title-mini">
    <div class="container clearfix">
        <h1>Order ID #<?php echo $transaksi->orderid; ?></h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo site_url('account/order'); ?>">Pesananku</a></li>
            <li class="breadcrumb-item">Detail Pesanan</li>
        </ol>
    </div>
</section><!-- #page-title end -->

<!-- Content
============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
			<div class="row m-lr-0">
				<div class="col-md-7 m-b-30">
					<div class="card mb-2">
						<div class="card-body">
							<div class="row">
								<div class="col-md-6 p-b-10 p-t-10">
									<p class="m-b-10">
										Waktu Pemesanan:<br />
										<i class="font-medium"><?php echo $this->setting->ubahTgl("d M Y H:i", $transaksi->tgl); ?> WIB</i>
									</p>
									<p class="">
										Waktu Pembayaran:<br />
										<i class="font-medium"><?php echo $this->setting->ubahTgl("d M Y H:i", $bayar->tgl); ?> WIB</i>
									</p>
								</div>
								<div class="col-md-6">
									<?php if ($transaksi->status == 0) { ?>
										<!-- Belum Dibayar -->
										<p class="alert alert-warning">Belum Dibayar</p>
										<p class="m-b-5">segera lakukan pembayaran maks. 1x24jam untuk menghindari pembatalan otomatis.</p>
									<?php } elseif ($transaksi->status == 2 and $transaksi->resi != "") { ?>
										<!-- Dalam Pengiriman -->
										<p class="alert alert-primary">Sedang Dikirim</p>
										<p class="m-b-5">pesanan Anda sudah dalam perjalanan, untuk melihat proses pengiriman silahkan cek info dibawah.</p>
									<?php } elseif ($transaksi->status == 1) { ?>
										<!-- Sedang Dikemas -->
										<p class="alert alert-primary">Sedang Dikemas</p>
										<p class="m-b-5">pesanan sedang dikemas oleh admin dan akan segera dikirim.</p>
									<?php } elseif ($transaksi->status == 3) { ?>
										<!-- Selesai -->
										<p class="alert alert-success">Telah Diterima</p>
										<p class="m-b-5">pesanan telah diterima oleh pembeli.</p>
									<?php } elseif ($transaksi->status == 4) { ?>
										<!-- Selesai -->
										<p class="alert alert-danger">Pesanan Dibatalkan</p>
										<p class="m-b-5">pesanan dibatalkan karena <?php echo $transaksi->keterangan; ?></p>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">
								Produk Pesanan
							</h4>
						</div>
						<div class="card-body">
							<?php
							$this->db->where("idtransaksi", $transaksi->id);
							$db = $this->db->get("sales_produk");
							$total = 0;
							foreach ($db->result() as $res) {
								$total += $res->harga * $res->jumlah;
								$produk = $this->produk_model->getProduk($res->idproduk, "semua");
								$variasee = $this->produk_model->getVariasi($res->variasi, "semua");
								$variasi = ($res->variasi != 0 and isset($variasee->warna)) ? $this->produk_model->getWarna($variasee->warna, "nama") . " " . $produk->subvariasi . " " . $this->produk_model->getSize($variasee->size, "nama") : "";
								$variasi = ($res->variasi != 0 and isset($variasee->warna)) ? "<small class='text-primary'>" . $produk->variasi . ": " . $variasi . "</small>" : "";
								?>
								<div class="row">
									<div class="col-4 col-md-3">
										<img src="<?php echo $this->produk_model->getFoto($res->idproduk, "utama"); ?>" alt="">
									</div>
									<div class="col-8 col-md-9">
										<p class="font-medium"><?php echo $produk->nama; ?></p>
										<?php echo $variasi; ?>
										<p>Rp <?php echo $this->setting->formUang($res->harga); ?> <span class="fs-14">x <?php echo $res->jumlah; ?></span></p>
									</div>
								</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>
				<div class="col-md-5 m-b-30">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">
								Informasi Pengiriman
								<?php
								$alamat = $this->setting->getAlamat($transaksi->alamat, "semua");
								$kab = '-';
								if (isset($alamat->idkab) && $alamat->idkab != null) {
									$kab = $this->rajaongkir->getCity($alamat->idkab)['rajaongkir']['results']['city_name'];
								}
								?>
							</h4>
						</div>
						<div class="card-body">
							<div class="row p-tb-10">
								<div class="col-md-6">
									<h5 class="p-b-10">KURIR & PAKET</h5>
									<p>
										<?php
										echo "<div class='badge badge-warning fs-18 font-regular'>" . strtoupper($transaksi->kurir) . " - " . strtoupper($transaksi->paket) . "</div>";
										?>
									</p>
								</div>
								<div class="col-md-6">
									<h5 class="text-black p-b-10">RESI PENGIRIMAN</h5>
									<p class="text-success font-medium"><?php echo $transaksi->resi; ?></p>
								</div>
							</div>
							<div class="row p-t-20">
								<div class="col-md-6">
									<h5 class="text-black p-b-10">Nama Penerima</h5>
									<p><?php echo strtoupper(strtolower($alamat->nama)); ?></p>
								</div>
								<div class="col-md-6">
									<h5 class="text-black p-b-10">No Telepon</h5>
									<p><?php echo $alamat->nohp; ?></p>
								</div>
							</div>
							<div class="row p-t-20">
								<div class="col-md-12">
									<h5 class="text-black p-b-10">Alamat Pengiriman</h5>
									<?php echo strtoupper(strtolower($alamat->alamat)); ?>
									<?php echo $kab; ?><br>
									Kodepos <?php echo $alamat->kodepos; ?>
								</div>
							</div>
						</div>
						<?php if ($transaksi->resi != "" and $transaksi->kurir != "cod" and $transaksi->kurir != "toko") { ?>
						<!-- <div class="card-footer">
							<a href="<?php echo site_url("account/lacakpaket/" . $transaksi->orderid); ?>" class="btn btn-warning btn-lg btn-block"><i class="fas fa-shipping-fast"></i> &nbsp;<b>CEK STATUS PENGIRIMAN</b></a>
						</div> -->
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>