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

$this->db->where("status", 2);
$this->db->where("resi !=", "");
$this->db->where("usrid", $_SESSION["uid"]);
$rows = $this->db->get("sales");
$rows = $rows->num_rows();

$this->db->where("status", 2);
$this->db->where("resi !=", "");
$this->db->where("usrid", $_SESSION["uid"]);
$this->db->order_by("id", "DESC");
$this->db->limit($perpage, ($page - 1) * $perpage);
$db = $this->db->get("sales");
if ($db->num_rows() > 0) {
	foreach ($db->result() as $rx) {
	?>
		<div class="mb-2">
			<div class="card pesanan-item">
				<div class="card-header">
					<div class="row p-b-10">
						<div class="col-6">
							<span class="font-medium f-sm-12 f-md-16">
								Order ID <strong><span class="color1">#<?php echo $rx->orderid; ?></span></strong>
							</span>
						</div>
						<div class="col-6 text-right">
							<a href="<?php echo site_url("account/order/detail?orderid=") . $rx->orderid; ?>" class="btn btn-sm btn-colorbutton f-sm-12 f-md-14"><i class="fas fa-angle-double-right"></i> Rincian<span class="hidesmall"> Pesanan</span></a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row m-lr-0">
						<div class="col-md-8 p-lr-0">
							<?php
							$this->db->where("idtransaksi", $rx->id);
							$trp = $this->db->get("sales_produk");
							$totalproduk = 0;
							$no = 1;
							foreach ($trp->result() as $key) {
								$totalproduk += $key->harga * $key->jumlah;
								$produk = $this->produk_model->getProduk($key->idproduk, "semua");
								$variasee = $this->produk_model->getVariasi($key->variasi, "semua");
								$variasi = ($key->variasi != 0 and $variasee != null) ? $this->produk_model->getWarna($variasee->warna, "nama") . " " . $produk->subvariasi . " " . $this->produk_model->getSize($variasee->size, "nama") : "";
								$variasi = ($key->variasi != 0 and $variasee != null) ? "<small class='text-secondary'>" . $produk->variasi . ": " . $variasi . "</small>" : "";
								if ($no == 2) {
							?>
									<div class="m-b-10 show-product">
									<?php
								}
									?>
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
							if ($no > 2) {
								?>
									</div>
									<div class="p-b-10 p-r-10">
										<a href="javascript:void(0)" class="view-product color1 f-sm-12 f-md-14"><i class="fas fa-chevron-circle-down"></i> Lihat produk lainnya</a>
										<a href="javascript:void(0)" class="view-product color1 f-sm-12 f-md-14" style="display:none;"><i class='fas fa-chevron-circle-up'></i> Sembunyikan produk</a>
									</div>
								<?php
							}
								?>
						</div>
						<div class="col-md-4 f-sm-12 f-md-16">
							Waktu Pengiriman:<br />
							<span class="text-dark p-r-8 font-medium"><?php echo $this->setting->ubahTgl("d M Y H:i", $rx->kirim); ?> WIB</span>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<!-- <div class="col-6">
									<a href="<?php echo site_url("account/lacakpaket/" . $rx->orderid); ?>" disabled="disabled" class="btn btn-outline-primary btn-block m-b-10 f-sm-12 f-md-14">
										Lacak Kiriman
									</a>
								</div> -->
								<div class="col-6">
									<a href="javascript:void(0)" onclick="terimaPesanan(<?php echo $rx->id; ?>)" class="btn btn-primary btn-block m-b-10 f-sm-12 f-md-14">
										Terima Pesanan
									</a>
								</div>
							</div>
						</div>
						<div class="col-md-2 m-b-14"></div>
						<div class="col-md-4 text-right">
							<h5 class="text-dark f-sm-12 f-md-14">Total Order &nbsp;<strong><span class="color1 font-bold text-right f-sm-12 f-md-14">IDR <?php echo $this->setting->formUang($rx->ongkir + $totalproduk); ?></span></strong></h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
	echo $this->setting->createPagination($rows, $page, $perpage, "refreshDikirim");
} else {
?>
	<div class="text-center section m-0">
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
</script>