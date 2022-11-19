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

add_script('packages.js');

// Form opening tag.
echo form_open('admin/packages', 'class="form-inline card"'),

// Form nonce field.
form_nonce('bulk-update-packages'),

// Bulk action section.
'<div class="table-bulk-actions card-header">',
	'<div class="row row-cols-lg-auto g-3 align-items-center">',
		'<div class="col-12">',
		form_dropdown('action', array(
			'activate-selected'   => __('lang_activate'),
			'deactivate-selected' => __('lang_deactivate'),
			'delete-selected'     => __('lang_delete'),
		), 'activate-selected', 'class="form-select form-select-sm"'),
		'</div>',

		'<div class="col-12">',
		form_submit('doaction', __('lang_apply'), 'class="btn btn-primary btn-sm ml-1"'),
		'</div>',
	'</div>',
'</div>',

// Packages list table.
'<div class="table-responsive-sm">',
'<table class="table table-hover table-striped mb-0">',
	// Table heading.
	'<thead>',
	'<tr>',
		// Bulk Selection?
		'<th class="w-2">',
			form_label(__('select_all'), null, 'class="sr-only"'),
			form_checkbox('check-all', null, false),
		'</th>',

		'<th class="w-20">', __('lang_package'), '</th>',
		'<th class="w-50">', __('lang_description'), '</th>',
		'<th class="w-30 text-end">', __('lang_actions'), '</th>',
	'</tr>',
	'</thead>';

	// Packages list.
	if ($packages) {
		echo '<tbody>';
		foreach ($packages as $folder => $package) {
			echo '<tr id="package-'.$folder.'" data-package="'.$folder.'" data-name="'.$package['name'].'">',
				// Package selection.
				'<td>',
					form_label('Select '.$package['name'], 'checkbox-'.$folder, 'class="sr-only"'),
					form_checkbox('selected[]', $folder, false, 'id="checkbox-'.$folder.'" class="check-this"'),
				'</td>',

				'<td>',
					html_tag(($package['enabled'] ? 'strong' : 'span'), array(
						'data-package' => $folder,
					), $package['name']),
				'</td>',

				'<td>',
					html_tag('p', ['class'=>'m-0'], $package['description']),
					implode(' &#124; ', $package['details']),
				'</td>',
				'<td class="text-end">',
					implode('', $package['actions']),
				'</td>',
			'</tr>';
		}
		echo '</tbody>';
	}

echo '</table></div>',
form_close();
