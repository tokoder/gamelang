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
?>

<?= get_partial('header'); ?>

<main class="my-5 pt-4">
	<div class="container">
		<?php the_alert(); ?>

		<?php the_content(); ?>
	</div>
</main>

<?= get_partial('footer'); ?>
