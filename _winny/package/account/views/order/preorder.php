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

$this->db->where("usrid", $_SESSION["uid"]);
$rows = $this->db->get("preorder");
$rows = $rows->num_rows();

$this->db->where("usrid", $_SESSION["uid"]);
$this->db->order_by("status", "ASC");
$this->db->limit($perpage, ($page - 1) * $perpage);
$db = $this->db->get("preorder");
if ($db->num_rows() > 0) {
	foreach ($db->result() as $rx) {
	?>
	<div class="mb-2">
		<div class="card pesanan-item">
			<div class="card-header">
				<div class="row p-b-10">
					<div class="col-md-8">
						<span class="font-medium f-sm-12 f-md-16">
							Order ID <span class="color1">#<?php echo $rx->orderid; ?></span>
						</span>
					</div>
				</div>
			</div>
			<?php
			$produk = $this->setting->getProduk($rx->idproduk, "semua");
			if (isset($produk->tglpo)) {
				$tglpo = $produk->tglpo;
			} else {
				$tglpo =  "0000-00-00 00:00:00";
			}
			?>
			<div class="card-body">
				<div class="row m-lr-0">
					<div class="col-md-8 p-lr-0 m-b-10">
						<div class="row pb-2 produk-item">
							<div class="col-4 col-md-2">
								<img src="<?php echo $this->produk_model->getFoto($rx->idproduk, "utama"); ?>" alt="">
							</div>
							<div class="col-8 col-md-10">
								<span class="font-medium text-dark btn-block f-sm-12 f-md-16">
									<?php if ($produk != null) {
										echo $produk->nama;
									} else {
										echo "Produk telah dihapus";
									} ?></span>
								<span class="f-sm-10 f-md-14 text-secondary"><?= $rx->variasi ?></span>
								<p class="font-medium text-dark btn-block f-sm-12 f-md-16">IDR <?php echo $this->setting->formUang($rx->harga); ?> <span style="font-size:12px">x<?php echo $rx->jumlah; ?></span></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-md-8 row">
						<?php if ($rx->status == 0) { ?>
							<div class="col-md-6 m-b-10">
								<span class="text-danger">Belum Bayar</span> <br>
								<a class="btn btn-outline-primary" href="<?= site_url("invoice?inv=" . $this->setting->arrEnc($rx->id, "encode") ) ?>">Lihat Invoice</a>
							</div>
						<?php } elseif ($rx->status == 1) { ?>
							<?php if ($this->setting->ubahTgl("Ymd", $tglpo) > date("Ymd")) { ?>
								<div class="col-12 m-b-10"><b class="text-primary">Sedang Dalam Proses Produksi</b></div>
							<?php } else {
								$this->db->where("idpo", $rx->id);
								$this->db->where("idtransaksi >", 0);
								$dbx = $this->db->get("sales_produk");
								if ($dbx->num_rows() > 0) { ?>
									<b class="text-success">Pesanan sudah diproses</b>
								<?php } else { ?>
									<div class="col-md-6 m-b-10">
										<b class="text-success">Stok Ready Silahkan Melakukan Checkout/Pelunasan</b> <br>
										<a class="btn btn-primary" href="<?= site_url("order/preorder/save?predi=" . $this->setting->arrEnc(array("idbayar" => $rx->id), "encode")) ?>">Checkout Sekarang</a>
									</div>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-6 text-right">
								<h5 class="text-black">Total DP</h5>
							</div>
							<div class="col-md-6">
								<h5 class="text-success font-bold text-right">Rp <?php echo $this->setting->formUang($rx->total); ?></h5>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 text-right">
								<h5 class="text-danger">Total Pelunasan</h5>
							</div>
							<div class="col-md-6">
								<h5 class="text-danger font-bold text-right">Rp <?php echo $this->setting->formUang(($rx->jumlah * $rx->harga) - $rx->total); ?></h5>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
	echo $this->setting->createPagination($rows, $page, $perpage, "refreshPO");
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