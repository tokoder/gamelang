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
        <h1>Hasil untuk pencarian produk: </h1>
		"<?php echo $query; ?>"
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Home</a></li>
            <li class="breadcrumb-item">Search</li>
        </ol>
    </div>
</section><!-- #page-title end -->

<!-- Content
============================================= -->
<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">

			<!-- Shop
			============================================= -->
			<div id="shop" class="shop row grid-container gutter-30" data-layout="fitRows">
				<!-- Product -->
				<?php
				$totalproduk = 0;
				foreach ($produk->result() as $r) {
					$level = isset($_SESSION["gid"]) ? $_SESSION["gid"] : 0;
					if ($level == 5) {
						$result = $r->hargadistri;
					} elseif ($level == 4) {
						$result = $r->hargaagensp;
					} elseif ($level == 3) {
						$result = $r->hargaagen;
					} elseif ($level == 2) {
						$result = $r->hargareseller;
					} else {
						$result = $r->harga;
					}
					$ulasan = $this->sales_model->getBintang($r->id);

					$dbv = $this->produk_model->getVariasiWhere($r->id);
					$totalstok = ($dbv->num_rows() > 0) ? 0 : $r->stok;
					$hargs = 0;
					$harga = array();
					foreach ($dbv->result() as $rv) {
						$totalstok += $rv->stok;
						if ($level == 5) {
							$harga[] = $rv->hargadistri;
						} elseif ($level == 4) {
							$harga[] = $rv->hargaagensp;
						} elseif ($level == 3) {
							$harga[] = $rv->hargaagen;
						} elseif ($level == 2) {
							$harga[] = $rv->hargareseller;
						} else {
							$harga[] = $rv->harga;
						}
						$hargs += $rv->harga;
					}

					if ($totalstok > 0) {
						$totalproduk += 1;
						$wishis = ($this->sales_model->cekWishlist($r->id)) ? "active" : "";
						?>
						<div class="product col-lg-3 col-md-4 col-sm-6 col-12">
							<div class="grid-inner">
								<div class="product-image position-relative"
									style="background-image: url('<?= $this->produk_model->getFoto($r->id, "utama"); ?>'); background-position: center center; background-repeat: no-repeat; background-size: cover; min-height: 250px">
									<!-- <div class="sale-flash badge badge-secondary p-2">Out of Stock</div> -->
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between">
											<div class="btn btn-dark mr-2" onclick="tambahWishlist(<?= $r->id ?>,'<?= $r->nama ?>')"><i class="fas fa-heart <?= $wishis ?>"></i></div>
											<a class="btn btn-dark" href="<?php echo site_url('product?url=' . strtolower($r->url)); ?>"><i class="icon-shopping-basket"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc">
									<div class="product-title mb-0"><h3><a href="<?php echo site_url('product?url=' . strtolower($r->url)); ?>"><?= $r->nama ?></a></h3></div>
									<div class="product-price">
										<del>
										<?php if ($r->hargacoret > 0) {
											echo "Rp. " . $this->setting->formUang($r->hargacoret);
										} ?></del> 
										
										<ins>
										<?php
										if ($hargs > 0) {
											echo "Rp. " . $this->setting->formUang(min($harga)) . " - " . $this->setting->formUang(max($harga));
										} else {
											echo "Rp. " . $this->setting->formUang($result);
										}
										?></ins>
									</div>
									<div class="product-rating row">
										<div class='col-6'>
											<small><?= $ulasan['ulasan'] ?> Ulasan</small>
										</div>
										<div class='col-6 text-right'>
											<span class="font-bold text-warning"><i class='fa fa-star'></i> <?= $ulasan['nilai'] ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				}

				if ($rows == 0 or $totalproduk == 0) {
					echo "<div class='col-12 text-center m-tb-40'><h3>Data tidak ditemukan.</h3></div>";
				}
				?>
			</div><!-- #shop end -->

			<!-- Pagination
			============================================= -->
			<?php echo $this->setting->createPagination($rows, $page, $perpage, "refreshPage"); ?>

		</div>
	</div>
</section><!-- #content end -->
	
<script type="text/javascript">
	function refreshTabel(page){
		window.location.href="<?=site_url('product/search')?>?page="+page;
	}
</script>