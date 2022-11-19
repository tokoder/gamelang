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
 * @link		https://github.com/tokoder
 * @since		1.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row<?php if ( ! form_error('packagezip')): ?> collapse<?php endif; ?> justify-content-center mb-3" id="package-install">
	<div class="col-sm-8 col-md-6 text-center">
		<p><?php _e('lang_upload_tip'); ?></p>
		<div class="card">
			<div class="card-body text-center">
				<?php
				echo form_open_multipart(
					'admin/packages/upload',
					'class="row row-cols-lg-auto g-3 align-items-center '.(form_error('packagezip') ? ' has-error' : '').'" id="package-upload"'
				),
				form_nonce('upload-package'),
				'<div class="col-12 me-auto">',
				form_upload('packagezip', null, 'id="packagezip"'),
				form_error('packagezip', '<div class="help-block">', '</div>'),
				'</div>',
				'<div class="col-12">',
				form_submit('package-install', __('lang_install'), array(
					'class' => 'btn btn-primary btn-sm'
				)),
				'</div>';
				?>
				</form>
			</div>
		</div>
	</div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded mb-3" role="navigation">
	<div class="container-fluid">
		<a class="navbar-brand"><span class="badge bg-white text-danger">0</span></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#packages-filter" aria-controls="packages-filter" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="packages-filter">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<?php
				// Featured.
				echo html_tag('li', array(
					'class' => 'nav-item'
				), html_tag('a', array(
					'href' => 'javascript:void(0)',
					'class' => 'nav-link',
				), __('lang_filter_featured'))),

				// Recommended
				html_tag('li', array(
					'class' => 'nav-item'
				), html_tag('a', array(
					'href' => 'javascript:void(0)',
					'class' => 'nav-link',
				), __('lang_filter_recommended'))),

				// Popular.
				html_tag('li', array(
					'class' => 'nav-item'
				), html_tag('a', array(
					'href' => 'javascript:void(0)',
					'class' => 'nav-link',
				), __('lang_filter_popular'))),

				// New.
				html_tag('li', array(
					'class' => 'nav-item'
				), html_tag('a', array(
					'href' => 'javascript:void(0)',
					'class' => 'nav-link',
				), __('lang_filter_new')));
				?>
			</ul>
			<form class="row row-cols-lg-auto g-3 align-items-center" role="search" method="get" action="javascript:void(0)">
				<div class="col-12">
					<select name="type" id="type" class="form-select form-select-sm">
						<option value="name" selected="selected"><?php _e('lang_name'); ?></option>
						<option value="tags"><?php _e('lang_tags'); ?></option>
						<option value="author"><?php _e('lang_author'); ?></option>
					</select>
				</div>
				<div class="col-12">
					<input type="text" class="form-control form-control-sm" id="search" name="search" placeholder="<?php _e('lang_search'); ?>">
				</div>
			</form>
		</div><!-- /.navbar-collapse -->
	</div>
</nav>
<div class="alert alert-info"><strong>NOTE</strong>: This section will be developed soon.</div>
<div id="package-modal-container"></div>
