<?php
defined('BASEPATH') OR exit('No direct script access allowed');

add_filter( 'after_scripts', function ( $output ) {
	$output .= <<<EOT
	<script>
	$(document).ready(function () {
		$(document).on('click', "#show_password", function(){
			if($(this).is(':checked')){
				$("input[name='password'], input[name='cpassword']").attr('type','text');
			}else{
				$("input[name='password'], input[name='cpassword']").attr('type','password');
			}
		});
	});
	function copy(email, password)
	{
		$("#identity").val(email);
		$("#password").val(password);
	}
	</script>
	EOT;
	return $output;
});

// logo filter.
$login_src = apply_filters('login_img_src', "");
$login_alt = apply_filters('login_img_alt', config_item('site_name'));
$login_des = apply_filters('login_img_des', config_item('site_description'));
$login_url = apply_filters('login_img_url', site_url());

if ( ! empty($login_src))
{
	$login_img = html_tag('img', array(
		'src'   => $login_src,
		'class' => 'login-logo',
		'alt'   => $login_alt
	));

	$login_alt = empty($login_url) ? $login_img : "<a href=\"{$login_url}\" tabindex=\"-1\">{$login_img}</a>";
}
?>
<main class="py-5 bg-light">
	<div class="container">
		<div class="row align-items-center g-lg-5 py-5">
			<div class="col-lg-7">
				<h1 class="display-4 fw-bold lh-1 mb-3"><?=$login_alt?></h1>
				<p class="col-lg-11 fs-4 d-none d-sm-block"><?=$login_des?></p>
			</div>
			<div class="col-lg-5 mx-auto">
                <div class="card card-body position-relative">
				<?php
				// Display the alert.
				echo $this->themes->get_alert();

				// Display the content.
				echo $this->themes->print_content();
				?>
                </div>
			</div>
		</div>
	</div>
</main>

<footer class="footer text-muted py-5">
    <div class="container">
        <?php
        $in_footer[] = array(
            'parent' => NULL,
            'id'     => 'homepage',
            'slug'   => $login_url,
            'icon'   => 'fa fa-external-link',
            'name'   => __('lang_go_homepage'),
        );
        $in_footer[] = array(
            'parent' => NULL,
            'id'     => 'GitHub',
            'slug'   => prep_url('github.com/tokoder/tokoder'),
            'name'   => __('GitHub'),
            'attributes'   => ['target'=>'_blank'],
        );
        $in_footer[] = array(
            'parent' => NULL,
            'id'     => 'Facebook',
            'slug'   => prep_url('facebook.com/tokoder'),
            'name'   => __('Facebook'),
            'attributes'   => ['target'=>'_blank'],
        );
        $in_footer[] = array(
            'parent' => NULL,
            'id'     => 'instagram',
            'slug'   => prep_url('instagram.com/tokodercom'),
            'name'   => __('instagram'),
            'attributes'   => ['target'=>'_blank'],
        );
        $in_footer[] = array(
            'parent' => NULL,
            'id'     => 'Website',
            'slug'   => prep_url('tokoder.com'),
            'name'   => __('Website'),
            'attributes'   => ['target'=>'_blank'],
        );
        $this->menus->set_items($in_footer);
        echo $this->menus->render([
            'nav_tag_open'  => '<ul class="nav border-bottom">',
            'item_tag_open' => '<li class="nav-item">',
            'item_anchor'   => '<a href="%s" class="nav-link ps-0 text-muted">%s</a>',
        ]);

		// footer copyright
        $footer_copyright = sprintf('&copy; %s %s - All Rights Reserved.', date('Y'), anchor($login_url, $login_alt));
        $footer_copyright = apply_filters('login_copyright', $footer_copyright);
        echo html_tag('div', array(
            'id' => 'footer-copyright',
            'class' => 'py-3',
        ), $footer_copyright);
        ?>
    </div>
</footer>