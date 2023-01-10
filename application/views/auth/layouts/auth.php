<?php
defined('BASEPATH') OR exit('No direct script access allowed');

add_filter( 'html_class', function ( $class ) {
	$class[] = 'h-100';
	return $class;
});
add_filter( 'body_class', function ( $class ) {
	$class[] = 'd-flex flex-column h-100';
	return $class;
});
add_filter( 'after_scripts', function ( $output ) {
	$output .= get_partial('script');
	return $output;
});

// Layout opening tags.
echo '<main class="container vh-100 d-flex flex-column justify-content-center">';
echo '<div class="row">';
echo '<div class="col-lg-5 col-md-8 mx-auto my-5">';

// logo filter.
$login_src = apply_filters('login_img_src', "");
$login_alt = apply_filters('login_img_alt', config_item('site_name'));
$login_url = apply_filters('login_img_url', site_url());

if ( ! empty($login_src))
{
	echo '<div class="card-body card-logo">';
	$login_img = html_tag('img', array(
		'src'   => $login_src,
		'class' => 'login-logo',
		'alt'   => $login_alt
	));

	echo empty($login_url) ? $login_img : "<a href=\"{$login_url}\" tabindex=\"-1\">{$login_img}</a>";
	echo '</div>';
}
else {
	echo "<h1>{$login_alt}</h1>";
}

// Display the alert.
echo $this->themes->get_alert();

// Display the content.
echo $this->themes->print_content();

echo '</div>';
echo '</div>';
echo '</main>';

// Footer section.
echo get_partial('footer');