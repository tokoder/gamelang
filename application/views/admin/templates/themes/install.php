<?php
/**
 * CodeIgniter Gamelang
 *
 * An open source codeigniter management system
 *
 * @package 	CodeIgniter Gamelang
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link		https://github.com/tokoder/gamelang
 * @since		1.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row<?php if ( ! form_error('themezip')): ?> collapse<?php endif; ?> justify-content-center mb-3" id="theme-install">
	<div class="col-xs-12 col-sm-8 col-md-6 text-center">
		<p><?php _e('lang_upload_tip'); ?></p>
		<div class="card">
			<div class="card-body text-center">
				<?php
				echo form_open_multipart(
					admin_url('themes/upload'),
					'class="row row-cols-lg-auto g-3 align-items-center '.(form_error('themezip') ? ' has-error' : '').'" id="theme-upload"'
				),
				form_nonce('upload-theme'),
				form_upload('themezip', null, 'id="themezip"'),
				form_error('themezip', '<div class="help-block">', '</div>'),
				form_submit('theme-install', __('lang_INSTALL'), array(
					'class' => 'btn btn-primary btn-sm ms-auto'
				));
				?>
				</form>
			</div>
		</div>
	</div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded mb-3" role="navigation">
	<div class="container-fluid">
		<a class="navbar-brand"><span class="badge bg-white text-danger">0</span></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#themes-filter" aria-controls="themes-filter" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="themes-filter">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
			<?php
			// Featured.
			echo html_tag('li', array(
				'class' => 'nav-item'
			), html_tag('a', array(
				'href' => 'javascript:void(0)',
				'class' => 'nav-link',
			), __('lang_FILTER_FEATURED'))),

			// Popular.
			html_tag('li', array(
				'class' => 'nav-item'
			), html_tag('a', array(
				'href' => 'javascript:void(0)',
				'class' => 'nav-link',
			), __('lang_FILTER_POPULAR'))),

			// New.
			html_tag('li', array(
				'class' => 'nav-item'
			), html_tag('a', array(
				'href' => 'javascript:void(0)',
				'class' => 'nav-link',
			), __('lang_FILTER_NEW')));
			?>
			</ul>
			<form class="row row-cols-lg-auto g-3 align-items-center" role="search" method="get" action="javascript:void(0)">
				<div class="col-12">
					<select name="type" id="type" class="form-select form-select-sm">
						<option value="name" selected="selected"><?php _e('lang_NAME'); ?></option>
						<option value="tags"><?php _e('lang_tags'); ?></option>
						<option value="author"><?php _e('lang_AUTHOR'); ?></option>
					</select>
				</div>
				<div class="col-12">
					<input type="text" class="form-control form-control-sm" id="search" name="search" placeholder="<?php _e('lang_SEARCH'); ?>">
				</div>
			</form>
		</div><!-- /.navbar-collapse -->
	</div>
</nav>
<div class="alert alert-info"><strong>NOTE</strong>: This section will be developed soon.</div>
<div id="theme-modal-container"></div>
