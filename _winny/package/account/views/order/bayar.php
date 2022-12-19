<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

$page = (isset($_GET["page"]) and $_GET["page"] != "" and intval($_GET["page"]) > 0) ? intval($_GET["page"]) : 1;
$perpage = 10;

$this->db->where("status", 0);
$this->db->where("usrid", $_SESSION["uid"]);
$rows = $this->db->get("invoice");
$rows = $rows->num_rows();

$this->db->where("status", 0);
$this->db->where("usrid", $_SESSION["uid"]);
$this->db->order_by("status ASC, id DESC");
$this->db->limit($perpage, ($page - 1) * $perpage);
$db = $this->db->get("invoice");
if ($db->num_rows() > 0) {
	foreach ($db->result() as $res) {
		$idbyr = $this->setting->arrEnc(array("idbayar" => $res->id), "encode");
		$this->db->where("idbayar", $res->id);
		$konf = $this->db->get("invoice_konfirmasi");
		$link = $res->id; //$this->setting->arrEnc(array("idbayar"=>$res->id),"encode");
		$klik = ($res->ipaymu_tipe == "va" || $res->ipaymu_tipe == "cstore") ? "bayarVA(" . $res->id . ",'" . site_url("invoice?inv=" . $this->setting->arrEnc($link, "encode")) . "')" : "openLink('" . site_url("invoice?inv=" . $this->setting->arrEnc($link, "encode") ) . "')";
		?>
		<div class="mb-2">
			<div class="pesanan-item card">
				<div class="card-header">
					<div class="row">
						<div class="col-8">
							<span class="font-medium f-sm-12 f-md-16">
								Order ID&nbsp; <strong><span class="color1">#<?php echo $res->invoice; ?></span></strong>
							</span>
						</div>
						<div class="col-4 text-right">
							<a href="javascript:void(0)" onclick="batal(<?php echo $res->id; ?>)" class="btn btn-outline-danger btn-sm f-sm-12 f-md-14">
								<i class="fas fa-times-circle"></i> Batal<span class='hidesmall'>kan pesanan</span>
							</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-8 p-lr-0">
							<?php
							$this->db->where("idbayar", $res->id);
							$trx = $this->db->get("sales");
							$no = 1;
							foreach ($trx->result() as $rx) {
								$this->db->where("idtransaksi", $rx->id);
								$trp = $this->db->get("sales_produk");
								foreach ($trp->result() as $key) {
									$produk = $this->produk_model->getProduk($key->idproduk, "semua");
									$variasee = ($key->variasi != 0) ? $this->produk_model->getVariasi($key->variasi, "semua") : null;
									$variasi = ($key->variasi != 0 and $variasee != null) ? $this->produk_model->getWarna($variasee->warna, "nama") . " " . $produk->subvariasi . " " . $this->produk_model->getSize($variasee->size, "nama") : "";
									$variasi = ($key->variasi != 0 and $variasee != null) ? "<small class='text-secondary'>" . $produk->variasi . ": " . $variasi . "</small>" : "";
									//if($no == 1){
									if ($no == 2) { ?>
										<div class="m-b-10 show-product">
										<?php
									} ?>
										<div class="row pb-2 produk-item">
											<div class="col-4 col-md-2">
												<img src="<?php echo $this->produk_model->getFoto($key->idproduk, "utama"); ?>" alt="">
											</div>
											<div class="col-8 col-md-10">
												<span class="font-medium text-dark btn-block f-sm-12 f-md-16">
													<?php if ($produk != null) {
														echo $produk->nama;
													} else {
														echo "Produk telah dihapus";
													} ?>
												</span>
												<span class="f-sm-10 f-md-14 text-secondary"><?= $variasi ?></span>
												<p class="font-medium text-dark btn-block f-sm-12 f-md-16">IDR <?php echo $this->setting->formUang($key->harga); ?> <span style="font-size:12px">x<?php echo $key->jumlah; ?></span></p>
											</div>
										</div>
									<?php
									$no++;
								}
							}
							if ($no > 2) { ?>
								</div>
								<div class="p-b-10 p-r-10">
									<a href="javascript:void(0)" class="view-product color1 f-sm-12 f-md-14"><i class="fas fa-chevron-circle-down"></i> Lihat produk lainnya</a>
									<a href="javascript:void(0)" class="view-product color1 f-sm-12 f-md-14" style="display:none;"><i class='fas fa-chevron-circle-up'></i> Sembunyikan produk</a>
								</div>
								<?php
							}
							?>
						</div>
						<div class="row m-lr-0 p-lr-12 col-md-4">
							<div class="text-dark f-sm-12 f-md-16 p-lr-0 col-6 col-md-12">Total<span class="hidesmall"> Pembayaran</span></div>
							<div class="color1 f-sm-12 f-md-16 p-lr-0 col-6 col-md-12 font-bold"><strong>IDR <?php echo $this->setting->formUang($res->saldo + $res->transfer); ?></strong></div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-md-6">
							<p class="f-sm-12 f-md-14">Segera lakukan pembayaran dalam <strong>1 x 24 jam</strong>, atau pesanan Anda akan Otomatis Dibatalkan.</p>
							<div class="m-t-16 showsmall"></div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<?php
								$this->db->where("idbayar", $res->id);
								$knf = $this->db->get("invoice_konfirmasi");
								if ($knf->num_rows() > 0) {
									echo "<div class='col-md-12 cl1 txt-center f-sm-12 f-md-14'><strong>status pembayaran:</strong> <span class='color2'>Pesanan kamu sedang kami proses, dalam waktu 1 x 24 jam akan kami kemas.</span>";
									foreach ($knf->result() as $ref) {
										echo "<br/><strong>waktu konfirmasi:</strong> <span>" . $this->setting->ubahTgl("d M Y H:i", $ref->tgl) . " WIB</span>";
									}
									echo "</div>";
								} else {
									if ($res->midtrans_id != "") { ?>
										<div class="col-5">
											<a href="javascript:void(0)" onclick="cekMidtrans(<?= $res->id ?>)" class="btn btn-outline-colorbutton btn-block m-b-10 f-sm-10 f-md-14">
												Cek Status
											</a>
										</div>
										<div class="col-7">
											<a href="javascript:void(0)" onclick="bayarUlang('<?= $res->id ?>','<?= $link ?>')" class="btn btn-colorbutton btn-block m-b-10 f-sm-10 f-md-14">
												Ubah Metode Pembayaran
											</a>
										</div>
										<?php
									} else { ?>
										<div class="col-6 offset-6">
											<a href="javascript:void(0)" onclick="<?= $klik ?>" class="btn btn-primary btn-block m-b-10 f-sm-12 f-md-14">
												Lihat Invoice
											</a>
										</div>
										<!-- <div class="col-6">
											<a href="javascript:void(0)" onclick="konfirmasi(<?php echo $res->id; ?>)" class="btn btn-dark btn-block m-b-10 f-sm-12 f-md-14">
												Konfirmasi
											</a>
										</div> -->
										<?php
									}
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="bayarva_<?= $res->id ?>" class="bayarva" style="display:none;">
			<div class="nomerva m-lr-30 m-t-20 p-lr-20 p-tb-14 bg2 bold">
				<h2 class="text-success"><?= $res->ipaymu_kode ?></h2>
			</div>
			<div class="bank m-lr-30 m-t-10">
				<h4>Channel: <?= strtoupper(strtolower($res->ipaymu_channel)) ?></h4>
			</div>
			<div class="bank m-lr-30 m-t-10">
				<h4>Total Pembayaran:<b class="text-danger"> Rp. <?= $this->setting->formUang($res->transfer + $res->kodebayar) ?></b></h4>
			</div>
		</div>
	<?php
	}
	echo $this->setting->createPagination($rows, $page, $perpage, "refreshBelumbayar");
} else { ?>
	<div class="text-center section">
		<i class="fas fa-box-open fs-100 text-secondary m-b-20"></i>
		<h5 class="text-secondary font-bold f-sm-14 f-md-18">TIDAK ADA PESANAN</h5>
	</div>
	<?php
}
?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".show-product").hide();
		$(".view-product").click(function() {
			$(this).parent().parent().find(".show-product").slideToggle();
			$(this).parent().parent().find(".view-product").toggle();
		});
	});

	function bayarVA(id, link) {
		$('#modalva').modal();
		$(".loadva").html($("#bayarva_" + id).html());
		$("#linkVA").attr("href", link);
	}

	function openLink(id) {
		window.location.href = id;
	}
</script>

<div class="modal fade" id="modalva" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Status Pembayaran</h5>
				<button type="button" data-dismiss="modal" aria-label="Close">
					<i class="fas fa-times text-danger fs-24 p-all-2"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-md-12 p-b-20">
					<div class="p-l-20 p-r-30 p-lr-0-lg">
						Silahkan melakukan pembayaran ke <br />
						<h4>Nomor Virtual Account</h4>
					</div>
					<div class="loadva"></div>
					<div class="m-t-20 p-lr-20">
						<b>Catatan:</b><br />
						Apabila melakukan pembayaran melalui Channel Alfamart / Indomaret, sampaikan kepada petugas kasirnya
						bahwa akan melakukan pembayaran <b>IPAYMU</b>
					</div>
					<div class="m-t-40 p-lr-20">
						<a href="" id="linkVA" class="btn btn-warning btn-block">UBAH METODE PEMBAYARAN</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>