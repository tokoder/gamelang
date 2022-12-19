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

<!-- Footer
============================================= -->
<footer id="footer" class="bg-transparent" style="border-top: 1px solid #EEE;">

	<div class="container">

		<!-- Footer Widgets
		============================================= -->
		<div class="footer-widgets-wrap pb-5">
			<div class="row">
				<div class="col-md-3 col-sm-6">
					<h4 class="text-uppercase">Metode Pembayaran</h4>
					<img src="<?php echo base_url('assets/img/Partner-Logo.png'); ?>" alt="">
				</div>
				<div class="col-md-3 col-sm-6">
					<h4 class="text-uppercase">Selalu Terhubung</h4>
					<a target="_blank" onclick="fbq('track','Contact')" href="https://wa.me/<?= $this->setting->getRandomWasap() ?>/?text=Halo,%20mohon%20infonya%20untuk%20menjadi%20reseller%20*<?= $this->setting->getSetting("nama") ?>*%20caranya%20bagaimana%20ya?%20dan%20syaratnya%20apa%20saja,%20terima%20kasih" class="btn button-green btn-block"><i class="fab fa-whatsapp"></i> Hubungi Admin</a>
					<p>Dapatkan potongan harga khusus untuk reseller.</p>
				</div>
				<div class="col-md-3 col-sm-6">
					<h4 class="text-uppercase">Layanan Pelanggan</h4>
					<abbr title="Phone Number"><strong>Phone:</strong></abbr> <?= $set->wasap ?><br>
					<abbr title="Email Address"><strong>Email:</strong></abbr> <?= $set->email ?><br>

					<a onclick="fbq('track','Contact')" href="<?= $set->facebook ?>" class="social-icon si-small si-light si-rounded si-facebook mt-4 mr-2">
						<i class="icon-facebook"></i>
						<i class="icon-facebook"></i>
					</a>
					<a onclick="fbq('track','Contact')" href="<?= $set->instagram ?>" class="social-icon si-small si-light si-rounded si-instagram mt-4 mr-2">
						<i class="icon-instagram"></i>
						<i class="icon-instagram"></i>
					</a>
					<a onclick="fbq('track','Contact')" href="mailto:<?= $set->email ?>" class="social-icon si-small si-light si-rounded si-youtube mt-4 mr-2">
						<i class="icon-envelope"></i>
						<i class="icon-envelope"></i>
					</a>
				</div>
				<div class="col-md-3 col-sm-6">
					<h4 class="text-uppercase">Toko Pusat Kami</h4>
					<address>
						<?= $set->alamat ?>
					</address>
				</div>
			</div>

		</div><!-- .footer-widgets-wrap end -->

	</div>

	<!-- Copyrights
	============================================= -->
	<div id="copyrights" class="bg-light">

		<div class="container clearfix">

			<div class="row justify-content-between align-items-center">
				<div class="col-md-6">
					Copyrights &copy; <?= date('Y'); ?> <?= ucwords(strtolower($set->nama)) ?><br>
				</div>
			</div>

		</div>

	</div><!-- #copyrights end -->

</footer><!-- #footer end -->