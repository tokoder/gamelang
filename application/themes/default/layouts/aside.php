<?= get_partial('header'); ?>

<main class="my-5 pt-5">
	<div class="container">
		<?php the_alert(); ?>

		<div class="row mb-5">
			<div class="col-xs-12 col-sm-6 col-md-3">
				<?php
				$this->menus->set_items(apply_filters('settings_menu', []));
				echo $this->menus->render([
					'nav_tag_open'=>'<ul class="list-group">',
					'item_tag_open' => '<li class="list-group-item list-group-item-action list-group-item-dark">',
					'item_anchor' => '<a href="%s">%s</a>'
				]);
				?>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-9">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</main>

<?= get_partial('footer'); ?>
