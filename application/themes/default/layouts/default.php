<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?= get_partial('header'); ?>

<main class="wrapper">
	<div class="container">
		<?php the_alert(); ?>
	</div>

	<?php the_content(); ?>
</main>

<?= get_partial('footer'); ?>
