<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?= get_partial('header'); ?>

<main>
	<div class="container">
		<?php the_alert(); ?>

		<?php the_content(); ?>
	</div>
</main>

<?= get_partial('footer'); ?>
