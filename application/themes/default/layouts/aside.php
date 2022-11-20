<?= get_partial('navbar'); ?>

<div class="container mt-3">
	<?php the_alert(); ?>

	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-3">
			<?php
            $this->menus->set_items(apply_filters('settings_menu', []));
            echo $this->menus->render();
            ?>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-9">
			<?php the_content(); ?>
		</div>
	</div>
</div>

<?= get_partial('footer'); ?>
