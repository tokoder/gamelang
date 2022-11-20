<div class="position-sticky" style="top: 2rem;">
	<div class="p-4 mb-3 bg-light rounded">
		<h4 class="fst-italic">About</h4>
		<p class="mb-0">Customize this section to tell your visitors a little bit about your publication, writers, content, or something else entirely. Totally up to you.</p>
	</div>

	<div class="p-4">
		<h4 class="fst-italic">Archives</h4>
		<ol class="list-unstyled mb-0">
			<li><a href="#">December 2017</a></li>
			<li><a href="#">November 2022</a></li>
		</ol>
	</div>

	<div class="p-4">
		<h4 class="fst-italic">Elsewhere</h4>
		<ol class="list-unstyled">
			<li><a href="#">GitHub</a></li>
			<li><a href="#">Facebook</a></li>
		</ol>
	</div>

	<div class="p-4">
		<h4 class="fst-italic">Legal</h4>
		<ol class="list-unstyled">
			<?php
            $this->menus->set_items(apply_filters('legal_menu', []));
            echo $this->menus->render([
				'nav_tag_open'=>'',
				'nav_tag_close'=>'',
                'item_tag_open' => '<li>',
                'item_anchor' => '<a href="%s">%s</a>'
			]);
            ?>
		</ol>
	</div>
</div>