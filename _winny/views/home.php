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

<!-- Slider
============================================= -->
<section id="slider" class="slider-element swiper_wrapper min-vh-50" data-loop="true" data-speed="1000" data-autoplay="5000">
	<div class="slider-inner">

		<div class="swiper-container swiper-parent">
			<div class="swiper-wrapper">
				<?php
				$this->db->where("tgl<=", date("Y-m-d H:i:s"));
				$this->db->where("tgl_selesai>=", date("Y-m-d H:i:s"));
				$this->db->where("jenis", 1);
				$this->db->where("status", 1);
				$this->db->order_by("id", "DESC");
				$sld = $this->db->get("@promo");
				if ($sld->num_rows() > 0) {
					foreach ($sld->result() as $s) {
					?>
					<div class="swiper-slide dark">
						<div class="container">
							<div class="slider-caption">
								<div>
									<h2 class="nott" data-animate="fadeInUp"><?=$s->caption;?></h2>
								</div>
							</div>
						</div>
						<div class="swiper-slide-bg" style="background: url('<?= base_url('uploads/promo/' . $s->gambar); ?>') no-repeat center center; background-size: cover;"></div>
					</div>
					<?php
					}
				}
				?>
			</div>
			<div class="swiper-navs">
				<div class="slider-arrow-left"><i class="icon-line-arrow-left"></i></div>
				<div class="slider-arrow-right"><i class="icon-line-arrow-right"></i></div>
			</div>
			<div class="swiper-scrollbar">
				<div class="swiper-scrollbar-drag">
				<div class="slide-number"><div class="slide-number-current"></div><span>/</span><div class="slide-number-total"></div></div></div>
			</div>
		</div>

	</div>
</section><!-- #Slider End -->

<!-- Site Content
============================================= -->
<section id="content">
	<div class="content-wrap">
		<div class="container clearfix">
			<div class="fancy-title title-border title-center">
				<h4>Produk Kami</h4>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="row">
						<?php
						$db = $this->produk_model->getProdukOrder();
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
			
							if ($totalstok > 0 and $totalproduk < 12) {
								$totalproduk += 1;
								$wishis = ($this->sales_model->cekWishlist($r->id)) ? "active" : "";
								?>

								<!-- Shop Item
								============================================= -->
								<div class="col-lg-3 col-md-3 col-6 px-2">
									<div class="product">
										<div class="product-image position-relative" 
											style="background-image: url('<?= $this->produk_model->getFoto($r->id, "utama"); ?>'); background-position: center center; background-repeat: no-repeat; background-size: cover; min-height: 250px">
											<div class="bg-overlay">
												<div class="bg-overlay-content align-items-end justify-content-between">
													<a class="btn btn-dark" href="<?php echo site_url('product?url=' . strtolower($r->url)); ?>"><i class="icon-shopping-basket"></i></a>
												</div>
												<div class="bg-overlay-bg bg-transparent"></div>
											</div>
										</div>
										<div class="product-desc">
											<div class="product-title">
												<h3><a href="<?php echo site_url('product?url=' . strtolower($r->url)); ?>"><?= $r->nama ?></a></h3>
												<!-- <span><a href="#">Nike</a></span> -->
											</div>
											<div class="product-price">
												<del><?php if ($r->hargacoret > 0) { echo "IDR " . $this->setting->formUang($r->hargacoret); } ?></del> 
												<ins>
												<?php
												if ($hargs > 0) {
													echo "IDR " . $this->setting->formUang(min($harga)) . " - " . $this->setting->formUang(max($harga));
												} else {
													echo "IDR " . $this->setting->formUang($result);
												}
												?>
												</ins>
											</div>
										</div>
										
									</div>
								</div>
								<?php
							}
						}

						if ($totalproduk == 0) {
							echo "<div class='col-12 text-center m-tb-40'><h3>Produk Kosong.</h3></div>";
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>