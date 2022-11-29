<div class="position-sticky" style="top: 2rem;">
	<div class="mb-3 p-4 bg-light rounded">
		<h4 class="fst-italic">About</h4>
		<p class="mb-0"><?=get_option('site_description')?></p>
	</div>

	<div class="mb-3">
		<h4 class="fst-italic">Archives</h4>
		<ol class="list-unstyled mb-0">
			<li><a href="#">December 2017</a></li>
			<li><a href="#">November 2022</a></li>
		</ol>
	</div>

	<div class="mb-3">
		<h4 class="fst-italic">Social</h4>
		<ol class="list-unstyled">
			<li><a href="https://github.com/tokoder/gamelang" target="_blank">GitHub</a></li>
			<li><a href="https://www.facebook.com/tokoder/" target="_blank">Facebook</a></li>
		</ol>
	</div>

	<?php
	$legal_menu = apply_filters('legal_menu', []);
	if ( ! empty($legal_menu)) : ?>
	<div class="mb-3">
		<h4 class="fst-italic">Legal</h4>
		<?php
		$this->menus->set_items($legal_menu);
		echo $this->menus->render([
			'nav_tag_open'=>'<ol class="list-unstyled">',
			'nav_tag_close'=>'</ol>',
			'item_tag_open' => '<li>',
			'item_anchor' => '<a href="%s">%s</a>'
		]);
		?>
	</div>
	<?php endif; ?>
</div>