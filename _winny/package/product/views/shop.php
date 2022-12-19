<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

$page = (isset($_GET["page"]) and $_GET["page"] != "") ? $_GET["page"] : 1;
$orderby = (isset($_GET["orderby"]) and $_GET["orderby"] != "") ? $_GET["orderby"] : "stok DESC, tglupdate DESC";
$cari = (isset($_GET["cari"]) and $_GET["cari"] != "") ? $_GET["cari"] : "";
$perpage = 12;
?>

<!-- Page Title
============================================= -->
<section id="page-title" class="page-title-mini">
    <div class="container clearfix">
        <h1>SEMUA PRODUK </h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Home</a></li>
            <li class="breadcrumb-item">Shop</li>
        </ol>
    </div>
</section><!-- #page-title end -->

<!-- Content
============================================= -->
<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<!-- <div class="row">
				<div class="col-12">
					<div class="btn-group" role="group" aria-label="Basic example">
						<button type="button" class="btn btn-sm btn-block f-sm-12" data-toggle="modal" data-target="#modal_filter"   id="btn_filter"><i class="fa fa-list"></i> Filter</button>
						<button type="button" class="btn btn-sm btn-block f-sm-12" data-toggle="modal" data-target="#modal_urutkan"   id="btn_urutkan"></i> Urutkan <i class="fa fa-sort"></i></button>
						<button type="button" class="btn btn-sm btn-block f-sm-12" data-toggle="modal" data-target="#modal_kategori"   id="btn_kategori"> Kategori <i class="fa fa-align-center"></i></button>
					</div>
				</div>
			</div> -->
			<!-- Shop
			============================================= -->
			<div id="shop" class="shop row grid-container gutter-30" data-layout="fitRows">
				<?php
				// GET VARIASI
				$dbvar = $this->produk_model->getVariasiSum();
				$notin = array();
				foreach ($dbvar->result() as $not) {
					if ($not->stok <= 0) {
						$notin[] = $not->idproduk;
					}
				}

				$where = "(nama LIKE '%$cari%' OR harga LIKE '%$cari%' OR hargareseller LIKE '%$cari%' OR hargaagen LIKE '%$cari%' OR deskripsi LIKE '%$cari%') AND status = 1 AND preorder_id != 1 AND stok > 0";
				$dbs = $this->produk_model->getProdukBySearch($where, $notin);

				$db = $this->produk_model->getProdukBySearch($where, $notin, $orderby, $perpage, $page);
				$totalproduk = 0;
				foreach ($db->result() as $r) {
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

				if ($db->num_rows() == 0 or $totalproduk == 0) {
					echo "<div class='col-12 text-center m-tb-40'><h3>Stok Kosong / Belum ada Produk.</h3></div>";
				}
				?>
			</div><!-- #shop end -->

			<!-- Pagination
			============================================= -->
			<?php
			if ($totalproduk > 0) {
				echo $this->setting->createPagination($dbs->num_rows(), $page, $perpage);
			}
			?>
		</div>
	</div>
</section>

<div class="modal animate__animated animate__slideInUp animate__slow" id="modal_filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal_fixed">
			<div class="modal-body">
				<div class="">
					<h1>Fitur Ini belum tersedia</h1>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal animate__animated animate__slideInUp animate__slow" id="modal_urutkan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal_fixed">
			<div class="modal-body">
				<div class="">
					<h1>urutkan</h1>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal animate__animated animate__slideInUp animate__slow" id="modal_kategori" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal_fixed">
			<div class="modal-header">
				<h5 class="fs-14 color1 modal-title">Cari Berdasarkan Kategori</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float: left">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="">
					<div class="cat-button" style="display: inline-block;">
						<a href="javascript:void(0)" class="btn btn-colorbutton bo-rad-23 p-l-16 p-r-16 m-r-4 m-b-12 button-kategori"> Semua</a>
						<?php
						$this->db->where("parent", 0);
						$db = $this->db->get("@kategori");
						foreach ($db->result() as $r) {
						?>
							<a href="<?= site_url("product?cat=" . strtolower($r->url)) ?>" class="btn btn-outline-colorbutton bo-rad-23 p-l-16 p-r-16 m-r-4 m-b-12 button-kategori">
								<?= ucwords(strtolower($r->nama)) ?>
							</a>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function refreshTabel(page) {
		window.location.href = "<?= site_url("shop?cari=" . $cari) ?>&page=" + page;
	}
</script>