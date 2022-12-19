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

<!-- breadcrumb -->
<div class="container">
	<div class="bread-crumb flex-w p-l-15 p-r-15 p-t-30 p-lr-0-lg">
		<a href="<?php echo site_url(); ?>" class="text-primary">
			Home
			<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
		</a>
		<a href="<?php echo site_url("manage/pesanan"); ?>" class="text-primary">
			Pesananku
			<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
		</a>

		<span class="stext-109 cl4">
			Ulasan Produk
		</span>
	</div>
</div>

<!-- Shoping Cart -->
<div class="container">
	<div class="row m-lr-0 m-b-50">
		<div class="col-md-12 m-lr-auto m-t-40">
			<h3 class="font-bold text-primary p-b-30">
				Ulasan Produk
		</div>

		<?php
		$order = $this->setting->getTransaksi($orderid, "semua", "orderid");
		$this->db->where("idtransaksi", $order->id);
		$db = $this->db->get("review");

		if ($db->num_rows() > 0) {
		?>
			<?php
			foreach ($db->result() as $r) {
				$produk = $this->setting->getProduk($r->idproduk, "semua");
			?>
				<div class="col-md-6 m-b-10">
					<div class="section p-lr-30 p-tb-30 row m-lr-0">
						<div class="col-3" style="min-height:80px;">
							<img style="max-width:100%;max-height:80px;" src="<?php echo $this->setting->getFoto($produk->id, "utama"); ?>" />
						</div>
						<div class="col-9">
							<b class="pointer" onclick="location.href='<?php echo site_url('product?url=' . $produk->url); ?>'"><?php echo $produk->nama; ?></b><br />
							<span class="fs-28 pointer m-lr-auto">
								<i class="fa fa-star text-warning"></i>
								<i class="fa fa-star <?php if ($r->nilai < 2) {
															echo "text-secondary";
														} else {
															echo "text-warning";
														} ?>"></i>
								<i class="fa fa-star <?php if ($r->nilai < 3) {
															echo "text-secondary";
														} else {
															echo "text-warning";
														}  ?>"></i>
								<i class="fa fa-star <?php if ($r->nilai < 4) {
															echo "text-secondary";
														} else {
															echo "text-warning";
														}  ?>"></i>
								<i class="fa fa-star <?php if ($r->nilai < 5) {
															echo "text-secondary";
														} else {
															echo "text-warning";
														}  ?>"></i>
							</span>
							<small style="display:block;">
								<?php
								switch ($r->nilai) {
									case 1:
										echo "(produk sangat buruk)";
										break;
									case 2:
										echo "(produk kurang baik)";
										break;
									case 3:
										echo "(produk standar)";
										break;
									case 4:
										echo "(produk baik)";
										break;
									case 5:
										echo "(produk sangat baik)";
										break;
								}
								echo "<br/>";
								echo $this->setting->ubahTgl("d M Y H:i", $r->tgl);
								?>
							</small>
						</div>
						<?php if ($r->keterangan != "") { ?>
							<div class="col-12 p-b-5 p-t-20">
								<?php echo $r->keterangan; ?>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php
			}
			?>
		<?php
		} else {
		?>
			<?= form_open('', 'class="row m-lr-0" id="addreview"'); ?>
			<?php
			$this->db->where("idtransaksi", $order->id);
			$db = $this->db->get("sales_produk");
			$no = 1;
			foreach ($db->result() as $t) {
				$produk = $this->setting->getProduk($t->idproduk, "semua");
			?>
				<div class="col-md-6 m-b-20">
					<div class="section p-lr-30 p-tb-30">
						<input type="hidden" name="orderid[]" value="<?php echo $order->orderid; ?>" />
						<input type="hidden" name="produk[]" value="<?php echo $t->idproduk; ?>" />
						<div class="p-tb-10 row m-lr-0">
							<div class="col-3" style="min-height:80px;">
								<img style="max-width:100%;max-height:80px;" src="<?php echo $this->setting->getFoto($produk->id, "utama"); ?>" />
							</div>
							<div class="col-9">
								<b><?php echo $produk->nama; ?></b>
							</div>
						</div>
						<div class="flex-w flex-m p-t-10 p-b-10" id="ulasan_<?= $t->id ?>">
							<span class="wrap-rating fs-36 cl11 pointer m-lr-auto">
								<i class="item-rating pointer fas fa-star text-medium nilai_1" onclick="nilai(<?= $t->id ?>,1);$('#nilai_<?php echo $no; ?>').val(1);"></i>
								<i class="item-rating pointer fas fa-star text-medium nilai_2" onclick="nilai(<?= $t->id ?>,2);$('#nilai_<?php echo $no; ?>').val(2);"></i>
								<i class="item-rating pointer fas fa-star text-medium nilai_3" onclick="nilai(<?= $t->id ?>,3);$('#nilai_<?php echo $no; ?>').val(3);"></i>
								<i class="item-rating pointer fas fa-star text-medium nilai_4" onclick="nilai(<?= $t->id ?>,4);$('#nilai_<?php echo $no; ?>').val(4);"></i>
								<i class="item-rating pointer fas fa-star text-medium nilai_5" onclick="nilai(<?= $t->id ?>,5);$('#nilai_<?php echo $no; ?>').val(5);"></i>
								<input type="hidden" class="nilai" id="nilai_<?php echo $no; ?>" name="nilai[]" value="0">
							</span>
						</div>
						<div class="p-b-25">
							<textarea class="form-control" rows=5 id="review" name="keterangan[]" placeholder="Beritahu penjual dan pengguna lain tentang kondisi produk ini"></textarea>
						</div>
					</div>
				</div>
			<?php
				$no++;
			}
			?>
			<div class="m-t-30 m-b-60 col-12">
				<div class="section p-tb-20 p-lr-30">
					<button type="submit" class="submit btn btn-success m-r-8"><i class="fas fa-send"></i> &nbsp;KIRIM ULASAN</button>
					<a href="javascript:history.back()" class="submit btn btn-danger"><i class="fas fa-times"></i> &nbsp;LAIN KALI</a>
					<h5 class="loaders dis-none"><i class="fas fa-spin fa-compact-disc text-success"></i> mengirim ulasan</h5>
				</div>
			</div>
			</form>
		<?php
		}
		?>

	</div>
</div>

<script type="text/javascript">
	$(function() {
		$("#addreview").on("submit", function(e) {
			e.preventDefault();
			$(".submit").hide();
			$(".loaders").show();

			var nonempty = $('.nilai').filter(function() {
				return this.value == '0';
			});

			if (nonempty.length == 0) {
				$.post("<?php echo site_url("assync/tambahulasan"); ?>", $(this).serialize(), function(msg) {
					var data = eval("(" + msg + ")");
					if (data.success == true) {
						swal.fire("Berhasil!", "berhasil mengirim ulasan", "success").then((value) => {
							location.reload();
						});
					} else {
						swal.fire("Gagal!", "terjadi kendala pada saat mengirim ulasan, cobalah beberapa saat lagi", "error");
						$(".submit").show();
						$(".loaders").hide();
					}
				});
			} else {
				swal.fire("Gagal!", "Semua produk harus diberi nilai", "error").then((value) => {
					$(".submit").show();
					$(".loaders").hide();
				})
			}
		});
	});

	function nilai(trx, num) {
		for (i = 1; i <= num; i++) {
			$("#ulasan_" + trx + " .nilai_" + i).removeClass("text-medium");
			$("#ulasan_" + trx + " .nilai_" + i).addClass("text-warning");
		}
		var nam = num + 1;
		for (i = nam; i <= 5; i++) {
			$("#ulasan_" + trx + " .nilai_" + i).addClass("text-medium");
			$("#ulasan_" + trx + " .nilai_" + i).removeClass("text-warning");
		}
	}
</script>