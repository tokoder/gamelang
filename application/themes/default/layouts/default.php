<?= get_partial('header'); ?>

<main class="my-5 pt-5">
	<div class="container">
		<?php the_alert(); ?>

		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-9">
				<?php the_content(); ?>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<?= get_partial('sidebar'); ?>
			</div>
		</div>
	</div>
</main>

<?= get_partial('footer'); ?>
