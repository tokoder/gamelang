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
        <h1>INVOICE</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Home</a></li>
            <li class="breadcrumb-item">INVOICE</li>
        </ol>
    </div>
</section><!-- #page-title end -->

<!-- Content
============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="card mb-0 mx-auto" style="max-width: 800px;">
				<div class="text-center mt-3">
					<h2 class="mb-0">INVOICE</h2>
					<?php echo $data->invoice; ?>
					<hr>
				</div>
				<div class="mt-2 py-2 ">
					<div class="col-12">
						<table class="w-100 mb-3">
							<tbody>
								<tr>
									<td><b>No. Invoice </b></td>
									<td class="text-right"><b><?php echo $data->invoice; ?></b></td>
								</tr>
								<tr>
									<td>A.N </td>
									<td class="text-right"><?php echo $profil->nama; ?></td>
								</tr>
								<tr>
									<td>No. Telepon </td>
									<td class="text-right"><?php echo $profil->nohp; ?></td>
								</tr>
								<tr>
									<td>Email </td>
									<td class="text-right"><?php echo $usrid->username; ?></td>
								</tr>
								<tr>
									<td>Alamat </td>
									<td class="text-right">
										<?php
										if (isset($alamat->idkab) && $alamat->idkab != null) {
											$kab = $this->rajaongkir->getCity($alamat->idkab)['rajaongkir']['results']['city_name'];
											echo strtoupper(strtolower($alamat->alamat . "<br/>" . $kab . "<br/>Kodepos " . $alamat->kodepos));
										}
										?>
									</td>
								</tr>
							</tbody>
						</table>

						<?php if($status['status'] == 'PENDING') {
							$method ="";
							$color = 'danger';
						} else if($status['status'] == 'SETTLED') {

							$method = "<p>Payment Method : VA ".$status['bank_code']."</p>";
							$color = 'primary';
						} else if($status['status'] =='PAID') {

							$method = "<p>Payment Method : ".$status['payment_method']."</p>";
							$color = 'primary';
						} else {
							$method ="";
							$color = 'dark';
						}  ?>

						<button type='button' class="btn mb-2 btn-<?php echo $color; ?>" ><?php echo $status['status']; ?></button> 
						<?php echo $method; ?>
					</div>
				</div>
				<div class="card-header"><strong>Informasi Pesanan</strong></div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table cart m-0">
							<tbody>
								<?php
								for ($i = 0; $i < count($transaksi); $i++) {
									$ongkir = (isset($ongkir)) ? $transaksi[$i]->ongkir + $ongkir : $transaksi[$i]->ongkir;
									$db = $this->sales_model->getSaleByTransaksi($transaksi[$i]->id);
									foreach ($db->result() as $res) {
										$produk = $this->produk_model->getProduk($res->idproduk, "semua");
										$variasee = $this->produk_model->getVariasi($res->variasi, "semua");
										$variasi = ($res->variasi != 0) ? $this->produk_model->getWarna($variasee->warna, "nama") . " size " . $this->produk_model->getSize($variasee->size, "nama") : "";
										$variasi = ($res->variasi != 0) ? "<br/><small class='text-secondary'>variasi: " . $variasi . "</small>" : "";
										?>
										<tr class="produk-item">
											<td class="cart-product-thumbnail">
												<?php if ($produk != null) { ?>
													<img src="<?php echo $this->produk_model->getFoto($produk->id, "utama"); ?>" alt="" width="64" height="64">
												<?php } else { echo "Produk telah dihapus"; } ?>
											</td>

											<td class="cart-product-name">
												<?php 
												if ($produk != null) {
													echo $produk->nama . $variasi;
													echo "<br><b>Catatan: </b> <i>" . $res->keterangan . "</i>";
												} else {
													echo "Produk telah dihapus";
												} ?>
											</td>

											<td class="cart-product-quantity">
												<div class="quantity clearfix">
													x <?php echo $res->jumlah; ?>
												</div>
											</td>

											<td class="cart-product-subtotal">
												<span class="amount"><?php echo $this->setting->formUang($res->harga); ?></span>
											</td>
										</tr>
									<?php
									}
								} ?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="col-md-12">
					<?php
					$bayartotal = $data->transfer + $data->kodebayar;
					?>
					<div class="text-right">
						<h4 class="m-0">Total : IDR <?php echo $this->setting->formUang($bayartotal); ?></h4>
					</div>
				</div>
				<hr>

				<?php if($status['status'] =="PENDING"){ ?>
					<?=form_open('', 'class="checkout"');?>
						<div class="col-md-12">
							<!-- <div>
								<label><b>Payment : </b></label>
								<p><b><?php strtoupper(str_replace("-"," ",$data->xendit_id)); ?></b></p>
							</div> -->
							<div class="text-center">
								<label><b>Link Invoice : </b></label>
								<div class="input-group m-t-10 f-sm-12 f-md-14">
									<input class="form-control" id="result-short" readonly value="<?php echo $data->xendit_url; ?>"></input>
									<div class="input-group-append">
										<a href="javascript:void(0);" class="btn btn-dark copy" data-clipboard-target="#result-short" data-original-title="" title=""> <span><i class="fas fa-copy"></i> Copy</a>
										<!-- <button type="button" class="btn btn-danger btn mr-1" data-toggle="modal" data-target="#VoidModal "><i class="fa fa-times"></i> VOID !</button> -->
										<!-- WARNING!! Jika diexpiredkan, customer tidak akan bisa membayar invoice ini. Dan Anda harus membuat invoice Baru apabila customer ingin melanjutkan transaksi.  -->
				
										<a href="<?php echo $data->xendit_url; ?>" target="_blank" class="btn btn-primary"> <span>Bayar Pesanan <i class="fas fa-chevron-circle-right"></i></a>
									</div>
								</div>
							</div>
						</div>
						<!-- Hapus Modal -->
						<div class="modal fade" id="VoidModal" tabindex="-1" role="dialog" aria-labelledby="ModalHapus" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="ModalHapus"><i class="fas fa-exclamation-triangle text-danger"></i> Warning</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										Yakin untuk VOID Invoice <?php echo $data->invoice; ?>? <br>
										Invoice akan otomatis expired setelah di Void
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-dark" data-dismiss="modal">Cancel</button>
										
										<a href="order/void/<?php echo $data->xendit_id; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> VOID INVOICE</a>
									</div>
								</div>
							</div>
						</div>

						<!-- <div class="card">
							<div class="card-header"><strong>Pembayaran</strong></div>
							<?php
							if ($data->transfer > 0) {
								$bayartotal = $data->transfer + $data->kodebayar;
								?>
								<div class="card-body">
									Mohon lakukan pembayaran sejumlah <br />
									<h5 class="f-sm-14 f-md-18 color1"><strong>IDR <?php echo $this->setting->formUang($bayartotal); ?></strong></h5>
									<h6 class="text-black f-sm-12 f-md-14">Pilih Metode Pembayaran:</h6>
									<div class="metode-bayar m-b-10">
										<?php if ($set->payment_transfer == 1) { ?>
											<div class="card metode-item manual" style="cursor: pointer;" onclick="bayarManual()">
												<div class="card-body">
													<div class="row">
														<div class="col-3">
															<img class="icon" src="<?= base_url("assets/img/tf_manual.png") ?>" />
														</div>
														<div class="col-9 f-sm-12 f-md-14">
															<strong>Transfer Manual</strong>&nbsp;<br>
															<span class="f-sm-10 f-md-12">Lakukan transfer ke No Rekening <?= $set->nama; ?>, kemudian upload bukti transfer.</span>
														</div>
													</div>
												</div>
											</div>
										<?php
										}
										if ($set->payment_ipaymu == 1) {
										?>
											<div class="col-md-12 m-b-12 section metode-item otomatis" onclick="bayarOtomatis()">
												<div class="row">
													<div class="col-12 bg-color1"><i class="cek fas fa-check-circle fs-24"></i></div>
												</div>
												<div class="row">
													<div class="col-3">
														<img class="icon" src="<?= base_url("assets/img/tf_minimarket.png") ?>" />
													</div>
													<div class="col-9 f-sm-12 f-md-14">
														<strong>Ipaymu</strong>&nbsp;<br>
														<span class="f-sm-10 f-md-12">Lakukan pembayaran melalui minimarket, virtual account bank, go pay, ovo, dan sebagainya.</span>
													</div>
												</div>
											</div>
										<?php
										}
										if ($set->payment_midtrans == 1) {
										?>
											<div class="col-md-12 m-b-12 section metode-item midtrans" onclick="bayarMidtrans()">
												<div class="row">
													<div class="col-12 bg-color1"><i class="cek fas fa-check-circle fs-24"></i></div>
												</div>
												<div class="row">
													<div class="col-3">
														<img class="icon" src="<?= base_url("assets/img/tf_minimarket.png") ?>" />
													</div>
													<div class="col-9 f-sm-12 f-md-14">
														<strong>Midtrans</strong>&nbsp;<br>
														<span class="f-sm-10 f-md-12">Lakukan pembayaran melalui minimarket, virtual account bank, go pay, ovo, dan sebagainya.</span>
													</div>
												</div>
											</div>
										<?php 
										} 
										if ($set->payment_xendit == 1) {
										?>
										<div class="card metode-item xendit mt-2" style="cursor: pointer;" onclick="bayarXendit()">
											<div class="card-body">
												<div class="row">
													<div class="col-3">
														<img class="icon" src="<?= base_url("./assets/img/payment/xendit.png") ?>" />
													</div>
													<div class="col-9">
														<h5>Xendit</h5>
														<span>Lakukan pembayaran melalui minimarket, virtual account bank, go pay, ovo, dan sebagainya.</span>
													</div>
												</div>
											</div>
										</div>
										<?php 
										} ?>
									</div>

									<div class="modal animated slideInUp" id="modal_bayarmanual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content modal_fixed">
												<div class="modal-header">
													<h5 class="modal-title f-sm-14 f-md-16"><strong>Metode Pembayaran: Transfer Manual</strong></h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: left">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="f-sm-12 f-md-14" style="text-align: justify;">
														<h5 class="text-black f-sm-12 f-md-14">Silahkan transfer pembayaran ke rekening berikut:</h5>
														<?php
														foreach ($bank->result() as $bn) : ?>
															<h5 class="cl2 m-t-10 m-b-10 p-t-10 p-l-10 p-b-10" style="border-left: 8px solid #f1f2f3;">
																<b class="text-dark f-sm-12 f-md-14">Bank <?= $bn->nama; ?>: </b>
																<b class="color1 f-sm-12 f-md-14"><?= $bn->norek; ?></b>
																<button type="button" class="btn btn-sm btn-light fs-10 copyText" id="myInput" data-clipboard-text="<?= $bn->norek; ?>" title="Copy">Copy</Button><br>
																<span class="f-sm-12 f-md-14">
																	a/n <?= $bn->atasnama; ?><br />
																	KCP <?= $bn->kcp; ?>
																</span>
															</h5>
														<?php endforeach; ?>

														<p class="m-b-5 m-t-20">
															<b>PENTING: </b>
														</p>
														<ul style="margin-left: 15px;">
															<li style="list-style-type: disc;">Mohon lakukan pembayaran dalam <b>1x24 jam</b></li>
															<li style="list-style-type: disc;">Sistem akan otomatis mendeteksi apabila pembayaran sudah masuk</li>
															<li style="list-style-type: disc;">Apabila sudah transfer dan status pembayaran belum berubah, mohon konfirmasi pembayaran manual di bawah atau di menu pesanan</li>
															<li style="list-style-type: disc;">Pesanan akan dibatalkan secara otomatis jika Anda tidak melakukan pembayaran.</li>
														</ul>
													</div>
												</div>
												<div class="modal-footer text-center">
													<a href="<?php echo site_url("account/order?konfirmasi=" . $data->id); ?>" class="bayarmanual"><b>Konfirmasi Pembayaran</b> &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-footer bayarmidtrans" style="display:none;">
									<a href="javascript:void(0)" onclick="payMidtrans()" class="btn button button-small"><i class="fa fa-chevron-circle-right"></i> &nbsp;<b>Bayar Sekarang</b></a>
									<a href="<?php echo site_url("account/order"); ?>" class="btn button button-small"><i class="fa fa-times"></i> &nbsp;<b>Bayar Nanti Saja</b></a>
								</div>
								<div class="card-footer bayarXendit" style="display:none;">
									<a href="<?php echo site_url("payment/xendit/" . $data->id); ?>" class="btn button button-small"><i class="fa fa-chevron-circle-right"></i> &nbsp;<b>Bayar Sekarang</b></a>
									<a href="<?php echo site_url("account/order"); ?>" class="btn button button-small"><i class="fa fa-times"></i> &nbsp;<b>Bayar Nanti Saja</b></a>
								</div>
								<div class="card-footer bayarotomatis" style="display:none;">
									<a href="<?php echo site_url("payment/ipaymu/" . $data->id); ?>" class="btn button button-small"><i class="fa fa-chevron-circle-right"></i> &nbsp;<b>Bayar Sekarang</b></a>
									<a href="<?php echo site_url("account/order"); ?>" class="btn button button-small"><i class="fa fa-times"></i> &nbsp;<b>Bayar Nanti Saja</b></a>
								</div>
								<?php
							} else {
								?>
								<div class="card-header">
									<h5 class="text-black">Metode Pembayaran: <span class="cl1" style="font-size: 16px;">Saldo <?= $this->setting->getSetting("nama") ?></span> </h5>
								</div>
								<div class="card-body">
									Terima kasih, saldo <b class='cl1'><?= $this->setting->getSetting("nama") ?></b> sudah terpotong sebesar
									<span style="color: #c0392b; font-size: 20px;"><b>Rp <?php echo $this->setting->formUang($data->saldo); ?></b></span>
									untuk pembayaran pesanan Anda.<br />
								</div>
								<hr class="m-t-30" />
								<a href="<?php echo site_url("account/order"); ?>" class="cl1 text-center w-full dis-block"><b>STATUS PESANAN</b> <i class="fa fa-chevron-circle-right"></i></a>
							<?php } ?>
						</div> -->
						<!-- <div class="card">
							<div class="card-header"><strong>Pembayaran</strong></div>
							<?php
							if ($data->transfer > 0) {
								$bayartotal = $data->transfer + $data->kodebayar;
								?>
								<div class="card-body">
									Mohon lakukan pembayaran sejumlah <br />
									<h5 class="f-sm-14 f-md-18 color1"><strong>IDR <?php echo $this->setting->formUang($bayartotal); ?></strong></h5>
									<h6 class="text-black f-sm-12 f-md-14">Pilih Metode Pembayaran:</h6>
									<div class="metode-bayar m-b-10">
										<?php if ($set->payment_transfer == 1) { ?>
											<div class="card metode-item manual" style="cursor: pointer;" onclick="bayarManual()">
												<div class="card-body">
													<div class="row">
														<div class="col-3">
															<img class="icon" src="<?= base_url("assets/img/tf_manual.png") ?>" />
														</div>
														<div class="col-9 f-sm-12 f-md-14">
															<strong>Transfer Manual</strong>&nbsp;<br>
															<span class="f-sm-10 f-md-12">Lakukan transfer ke No Rekening <?= $set->nama; ?>, kemudian upload bukti transfer.</span>
														</div>
													</div>
												</div>
											</div>
										<?php
										}
										if ($set->payment_ipaymu == 1) {
										?>
											<div class="col-md-12 m-b-12 section metode-item otomatis" onclick="bayarOtomatis()">
												<div class="row">
													<div class="col-12 bg-color1"><i class="cek fas fa-check-circle fs-24"></i></div>
												</div>
												<div class="row">
													<div class="col-3">
														<img class="icon" src="<?= base_url("assets/img/tf_minimarket.png") ?>" />
													</div>
													<div class="col-9 f-sm-12 f-md-14">
														<strong>Ipaymu</strong>&nbsp;<br>
														<span class="f-sm-10 f-md-12">Lakukan pembayaran melalui minimarket, virtual account bank, go pay, ovo, dan sebagainya.</span>
													</div>
												</div>
											</div>
										<?php
										}
										if ($set->payment_midtrans == 1) {
										?>
											<div class="col-md-12 m-b-12 section metode-item midtrans" onclick="bayarMidtrans()">
												<div class="row">
													<div class="col-12 bg-color1"><i class="cek fas fa-check-circle fs-24"></i></div>
												</div>
												<div class="row">
													<div class="col-3">
														<img class="icon" src="<?= base_url("assets/img/tf_minimarket.png") ?>" />
													</div>
													<div class="col-9 f-sm-12 f-md-14">
														<strong>Midtrans</strong>&nbsp;<br>
														<span class="f-sm-10 f-md-12">Lakukan pembayaran melalui minimarket, virtual account bank, go pay, ovo, dan sebagainya.</span>
													</div>
												</div>
											</div>
										<?php 
										} 
										if ($set->payment_xendit == 1) {
										?>
										<div class="card metode-item xendit mt-2" style="cursor: pointer;" onclick="bayarXendit()">
											<div class="card-body">
												<div class="row">
													<div class="col-3">
														<img class="icon" src="<?= base_url("./assets/img/payment/xendit.png") ?>" />
													</div>
													<div class="col-9">
														<h5>Xendit</h5>
														<span>Lakukan pembayaran melalui minimarket, virtual account bank, go pay, ovo, dan sebagainya.</span>
													</div>
												</div>
											</div>
										</div>
										<?php 
										} ?>
									</div>

									<div class="modal animated slideInUp" id="modal_bayarmanual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content modal_fixed">
												<div class="modal-header">
													<h5 class="modal-title f-sm-14 f-md-16"><strong>Metode Pembayaran: Transfer Manual</strong></h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: left">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="f-sm-12 f-md-14" style="text-align: justify;">
														<h5 class="text-black f-sm-12 f-md-14">Silahkan transfer pembayaran ke rekening berikut:</h5>
														<?php
														foreach ($bank->result() as $bn) : ?>
															<h5 class="cl2 m-t-10 m-b-10 p-t-10 p-l-10 p-b-10" style="border-left: 8px solid #f1f2f3;">
																<b class="text-dark f-sm-12 f-md-14">Bank <?= $bn->nama; ?>: </b>
																<b class="color1 f-sm-12 f-md-14"><?= $bn->norek; ?></b>
																<button type="button" class="btn btn-sm btn-light fs-10 copyText" id="myInput" data-clipboard-text="<?= $bn->norek; ?>" title="Copy">Copy</Button><br>
																<span class="f-sm-12 f-md-14">
																	a/n <?= $bn->atasnama; ?><br />
																	KCP <?= $bn->kcp; ?>
																</span>
															</h5>
														<?php endforeach; ?>

														<p class="m-b-5 m-t-20">
															<b>PENTING: </b>
														</p>
														<ul style="margin-left: 15px;">
															<li style="list-style-type: disc;">Mohon lakukan pembayaran dalam <b>1x24 jam</b></li>
															<li style="list-style-type: disc;">Sistem akan otomatis mendeteksi apabila pembayaran sudah masuk</li>
															<li style="list-style-type: disc;">Apabila sudah transfer dan status pembayaran belum berubah, mohon konfirmasi pembayaran manual di bawah atau di menu pesanan</li>
															<li style="list-style-type: disc;">Pesanan akan dibatalkan secara otomatis jika Anda tidak melakukan pembayaran.</li>
														</ul>
													</div>
												</div>
												<div class="modal-footer text-center">
													<a href="<?php echo site_url("account/order?konfirmasi=" . $data->id); ?>" class="bayarmanual"><b>Konfirmasi Pembayaran</b> &nbsp;<i class="fa fa-chevron-circle-right"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-footer bayarmidtrans" style="display:none;">
									<a href="javascript:void(0)" onclick="payMidtrans()" class="btn button button-small"><i class="fa fa-chevron-circle-right"></i> &nbsp;<b>Bayar Sekarang</b></a>
									<a href="<?php echo site_url("account/order"); ?>" class="btn button button-small"><i class="fa fa-times"></i> &nbsp;<b>Bayar Nanti Saja</b></a>
								</div>
								<div class="card-footer bayarXendit" style="display:none;">
									<a href="<?php echo site_url("payment/xendit/" . $data->id); ?>" class="btn button button-small"><i class="fa fa-chevron-circle-right"></i> &nbsp;<b>Bayar Sekarang</b></a>
									<a href="<?php echo site_url("account/order"); ?>" class="btn button button-small"><i class="fa fa-times"></i> &nbsp;<b>Bayar Nanti Saja</b></a>
								</div>
								<div class="card-footer bayarotomatis" style="display:none;">
									<a href="<?php echo site_url("payment/ipaymu/" . $data->id); ?>" class="btn button button-small"><i class="fa fa-chevron-circle-right"></i> &nbsp;<b>Bayar Sekarang</b></a>
									<a href="<?php echo site_url("account/order"); ?>" class="btn button button-small"><i class="fa fa-times"></i> &nbsp;<b>Bayar Nanti Saja</b></a>
								</div>
								<?php
							} else {
								?>
								<div class="card-header">
									<h5 class="text-black">Metode Pembayaran: <span class="cl1" style="font-size: 16px;">Saldo <?= $this->setting->getSetting("nama") ?></span> </h5>
								</div>
								<div class="card-body">
									Terima kasih, saldo <b class='cl1'><?= $this->setting->getSetting("nama") ?></b> sudah terpotong sebesar
									<span style="color: #c0392b; font-size: 20px;"><b>Rp <?php echo $this->setting->formUang($data->saldo); ?></b></span>
									untuk pembayaran pesanan Anda.<br />
								</div>
								<hr class="m-t-30" />
								<a href="<?php echo site_url("account/order"); ?>" class="cl1 text-center w-full dis-block"><b>STATUS PESANAN</b> <i class="fa fa-chevron-circle-right"></i></a>
							<?php } ?>
						</div> -->
					</form>
					<div id="tokenGenerated" style="display:none;"></div>

				<?php } else if ($status['status'] =="EXPIRED") { ?>
					<div class="col-md-12 text-center">
						<div>
							<h3><b>INVOICE EXPIRED</b></h3>
							<p>Silakan Buat Invoice Baru untuk Penagihan Customer</p>
						</div>
					</div>
					<hr>
					<div class="col-md-12 mt-3">
						<div class="text-center">
							<a href="order/new" class="btn btn-success" > <span><i class="fas fa-receipt"></i> Buat Invoice Baru</span> </a>
						</div>
					</div>
				<?php } else { ?>
					<div class="col-md-12 mt-3">
						<div class="text-center">
							<a href="sales" class="btn btn-success" > <span><i class="fas fa-arrow-left"></i> KEMBALI</span> </a>
						</div>
					</div>
				<?php } ?> 
			</div>
        </div>
    </div>
</section><!-- #content end -->

<script type="text/javascript" src="<?=$set->midtrans_snap?>" data-client-key="<?=$set->midtrans_client?>"></script>
<script>
	function bayarManual() {
		$(".metode-item").removeClass("active");
		$("#modal_bayarmanual").modal('show', function() {
			$('.copyText').click(function() {
				alert('Ok');
			});
		});
		$(".bayarmanual").show();
		$(".bayarotomatis").hide();
		$(".bayarmidtrans").hide();
		$(".bayarXendit").hide();
	}

	function bayarOtomatis() {
		$(".metode-item").removeClass("active");
		$(".metode-item.otomatis").addClass("active");
		$(".bayarmanual").hide();
		$(".bayarmidtrans").hide();
		$(".bayarXendit").hide();
		$(".bayarotomatis").show();
	}

	function bayarMidtrans() {
		$(".metode-item").removeClass("active");
		$(".metode-item.midtrans").addClass("active");
		$(".bayarmanual").hide();
		$(".bayarotomatis").hide();
		$(".bayarXendit").hide();
		$(".bayarmidtrans").show();
	}

	function bayarXendit() {
		$(".metode-item").removeClass("active");
		$(".metode-item.xendit").addClass("active");
		$(".bayarmanual").hide();
		$(".bayarotomatis").hide();
		$(".bayarmidtrans").hide();
		$(".bayarXendit").show();
	}

	// function payMidtrans() {
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "<?= site_url("payment/midtranstoken") ?>",
	// 		data: {
	// 			"invoice": "<?= $data->invoice ?>",
	// 			[$("#names").val()]: $("#tokens").val()
	// 		},
	// 		statusCode: {
	// 			200: function(responseObject, textStatus, jqXHR) {
	// 				var data = eval("(" + responseObject + ")");
	// 				updateToken(data.token)
	// 				$("#tokenGenerated").html(data.midtranstoken);
	// 				payMidtransNow();
	// 			},
	// 			404: function(responseObject, textStatus, jqXHR) {
	// 				Swal.fire("Sudah diproses", "Pembayaran gagal diproses, kami akan mencobanya kembali, apabila pesan ini terjadi berulang silahkan hubungi admin toko.", "success").then(res => {
	// 					window.location.href = "<?= site_url("invoice?revoke=true&inv=" . $_GET["inv"]) ?>"; //"<?= site_url("account/order") ?>";
	// 				});
	// 			},
	// 			500: function(responseObject, textStatus, jqXHR) {
	// 				Swal.fire("Sudah diproses", "Pembayaran gagal diproses, API Key tidak valid, silahkan hubungi admin toko untuk memperbaiki kendala ini.", "success").then(res => {
	// 					window.location.href = "<?= site_url("invoice?revoke=true&inv=" . $_GET["inv"]) ?>"; //"<?= site_url("account/order") ?>";
	// 				});
	// 			}
	// 		}
	// 	});
	// }

	// function payMidtransNow() {
	// 	snap.pay($("#tokenGenerated").html(), {
	// 		onSuccess: function(result) {
	// 			var url = "<?= site_url("payment/midtranspay") ?>?order_id=<?= $data->invoice ?>&status=success&transaction_id=" + result.transaction_id;
	// 			var form = document.createElement("form");
	// 			form.setAttribute("method", "post");
	// 			form.setAttribute("action", url);
	// 			var hiddenField = document.createElement("input");
	// 			hiddenField.setAttribute("name", "response");
	// 			hiddenField.setAttribute("value", JSON.stringify(result));
	// 			form.appendChild(hiddenField);
	// 			var hiddenFields = document.createElement("input");
	// 			hiddenFields.setAttribute("name", $("#names").val());
	// 			hiddenFields.setAttribute("value", $("#tokens").val());
	// 			form.appendChild(hiddenFields);

	// 			document.body.appendChild(form);
	// 			form.submit();
	// 			console.log(result);
	// 		},
	// 		onPending: function(result) {
	// 			var url = "<?= site_url("payment/midtranspay") ?>?order_id=<?= $data->invoice ?>&status=pending&transaction_id=" + result.transaction_id;
	// 			var form = document.createElement("form");
	// 			form.setAttribute("method", "post");
	// 			form.setAttribute("action", url);
	// 			var hiddenField = document.createElement("input");
	// 			hiddenField.setAttribute("name", "response");
	// 			hiddenField.setAttribute("value", JSON.stringify(result));
	// 			form.appendChild(hiddenField);
	// 			var hiddenFields = document.createElement("input");
	// 			hiddenFields.setAttribute("name", $("#names").val());
	// 			hiddenFields.setAttribute("value", $("#tokens").val());
	// 			form.appendChild(hiddenFields);

	// 			document.body.appendChild(form);
	// 			form.submit();
	// 			console.log(result);
	// 		},
	// 		onError: function(result) {},
	// 		onClose: function() {}
	// 	});
	// }
</script>