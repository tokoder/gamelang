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

<!-- Floating Contact
============================================= -->
<div class="floating-contact-wrap">
	<a target="_blank" onclick="fbq('track','Contact')" href="https://wa.me/<?= $this->setting->getRandomWasap() ?>/?text=Halo,%20mohon%20infonya%20untuk%20menjadi%20reseller%20*<?= $this->setting->getSetting("nama") ?>*%20caranya%20bagaimana%20ya?%20dan%20syaratnya%20apa%20saja,%20terima%20kasih" class="floating-contact-btn shadow">
		<i class="floating-contact-icon btn-unactive icon-whatsapp"></i>
	</a>
</div>

<?php include 'modal.php'; ?>

</div><!-- #wrapper end -->

<!-- Go To Top
============================================= -->
<div id="gotoTop" class="icon-angle-up rounded-circle"></div>

<input type="hidden" id="names" value="<?= $this->security->get_csrf_token_name() ?>" />
<input type="hidden" id="tokens" value="<?= $this->security->get_csrf_hash(); ?>" />

<?php 
$this->load->add_assets(array(
	'functions.js',
	'clipboard.js',
), 'foot');
echo $this->load->get_assets('foot');

echo $custom_js;
?>
<script>
	// Update Tokens CSRF
    function updateToken(token) {
        $("#tokens,.tokens").val(token);
    }

	// Tambah wishlist
	function tambahWishlist(id, nama) {
		$.post("<?php echo site_url("order/wishlist/add/"); ?>" + id, {
				[$("#names").val()]: $("#tokens").val()
			},
			function(msg) {
				var data = eval("(" + msg + ")");
				var wish = parseInt($(".wishlistcount").html());
				updateToken(data.token);
				if (data.success == true) {
					$(".wishlistcount").html(wish + 1);
					Swal.fire(nama, "berhasil ditambahkan ke wishlist", "success");
				} else {
					Swal.fire("Gagal", data.msg, "error");
				}
			});
	}
</script>

<!-- Facebook Pixel Code -->
<script>
	! function(f, b, e, v, n, t, s) {
		if (f.fbq) return;
		n = f.fbq = function() {
			n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments)
		};
		if (!f._fbq) f._fbq = n;
		n.push = n;
		n.loaded = !0;
		n.version = '2.0';
		n.queue = [];
		t = b.createElement(e);
		t.async = !0;
		t.src = v;
		s = b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t, s)
	}(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '<?= $set->fb_pixel ?>');
	fbq('track', 'PageView');
</script>
<!-- 
<noscript>
<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= $set->fb_pixel ?>&ev=PageView&noscript=1" />
</noscript> -->
<!-- End Facebook Pixel Code -->

</body>
</html>