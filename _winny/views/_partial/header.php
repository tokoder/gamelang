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

<!-- Header
============================================= -->
<header id="header" class="header-size-custom border-bottom-0 full-header" data-sticky-shrink="false">
	<div id="header-wrap">
		<div class="container">
			<div class="header-row justify-content-lg-between align-items-lg-stretch">

				<!-- Logo
				============================================= -->
				<div id="logo" class="mr-xl-5">
					<a href="<?= site_url() ?>"><h2 class="my-2"><?= $set->nama ?></h2></a>
				</div><!-- #logo end -->

				<div class="header-misc m-0 align-items-lg-stretch">
					<?php if ($this->setting->cekLogin() == false) { ?>
					<ul class="header-extras d-none d-xl-flex mr-4">
						<li>
							<i class="i-plain icon-call m-0"></i>
							<div class="he-text fw-normal">
								Call Us:
								<span><a href="tel:<?= $this->setting->getRandomWasap() ?>" class="fw-medium"><?= $this->setting->getRandomWasap() ?></a></span>
							</div>
						</li>
						<li>
							<i class="i-plain icon-line2-envelope m-0"></i>
							<div class="he-text fw-normal">
								Email Us:
								<span><a href="mailto:<?= $set->email ?>" target="_top" class="fw-medium"><?= $set->email ?></a></span>
							</div>
						</li>
					</ul>
					<?php } ?>

					<!-- Top Login
					============================================= -->
					<?php if ($this->setting->cekLogin() == true) { ?>
					<a class="header-misc-icon" href="<?php echo site_url("account"); ?>">
						<img src="<?=base_url('uploads/users/avatar.jpg');?>" class="img-circle img-thumbnail my-0" alt="Avatar" style="max-width: 40px;">
					</a><!-- #top-search end -->
					<?php } else { ?>
					<div class="header-misc-icon">
						<a href="<?php echo site_url("auth"); ?>"><i class="icon-line2-user"></i></a>
					</div><!-- #top-search end -->
					<?php } ?>

					<!-- Top Cart
					============================================= -->
					<?php if ($this->setting->cekLogin() == true) { ?>
					<div class="header-misc-icon">
						<a href="<?= site_url('order/cart') ?>">
							<i class="icon-line-bag"></i>
							<span class="top-cart-number"><?= $this->sales_model->getSaleByCart() ?></span>
						</a>
					</div><!-- #top-cart end -->
					<?php } ?>

					<!-- Wishlist
					============================================= -->
					<?php if ($this->setting->cekLogin() == true) { ?>
					<div class="header-misc-icon">
						<a href="<?= site_url('order/wishlist') ?>">
							<i class="icon-line-heart"></i>
							<span class="top-cart-number"><?= $this->sales_model->getWishlistCount() ?></span>
						</a>
					</div><!-- #Wishlist end -->
					<?php } ?>

					<!-- Top Search
					============================================= -->
					<div id="top-search" class="header-misc-icon">
						<a href="#" id="top-search-trigger"><i class="icon-line-search"></i><i class="icon-line-cross"></i></a>
					</div><!-- #top-search end -->
				</div>

				<div id="primary-menu-trigger">
					<svg class="svg-trigger" viewBox="0 0 100 100"><path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"></path><path d="m 30,50 h 40"></path><path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"></path></svg>
				</div>

				<?=form_open(site_url("product/search"), 'class="top-search-form" method="get"');?>
					<input type="text" name="cari" class="form-control" value="" placeholder="Type &amp; Hit Enter.." autocomplete="off">
				</form>

				<!-- Primary Navigation
				============================================= -->
				<nav class="primary-menu with-arrows mr-lg-auto">

					<ul class="menu-container align-self-start">
						<li class="menu-item"><span class="menu-bg col-auto align-self-start d-flex"></span></li>
						<li class="menu-item"><a class="menu-link" href="#" class="pl-0"><div><i class="icon-line-grid"></i>All Categories</div></a>
							<ul class="sub-menu-container p-0">
								<?php
								$this->db->where("parent", 0);
								$db = $this->db->get("@kategori");
								foreach ($db->result() as $r) {
								?>
								<li class="menu-item"><a class="menu-link" href="<?= site_url("product?cat=" . strtolower($r->url)) ?>"><div><?= $r->nama ?></div></a></li>
								<?php
								}
								?>
							</ul>
						</li>
					</ul>

				</nav><!-- #primary-menu end -->

			</div>
		</div>
	</div>
	<div class="header-wrap-clone"></div>
</header><!-- #header end -->

