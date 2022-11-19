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

add_script('reports.js');

echo '<div class="table-responsive-sm">',
	'<table class="table mb-0 table-hover table-striped">',
		'<thead>',
			'<tr>',
				'<th class="w-20">', __('lang_user'), '</th>',
				'<th class="w-15">', __('lang_package'), '</th>',
				'<th class="w-15">', __('lang_controller'), '</th>',
				'<th class="w-15">', __('lang_method'), '</th>',
				'<th class="w-15">', __('lang_ip_address'), '</th>',
				'<th class="w-15">', __('lang_date'), '</th>',
				'<th class="w-5 text-end">', __('lang_action'), '</th>',
			'</tr>',
		'</thead>';

		if ($reports) {
			echo '<tbody id="reports-list">';

			foreach ($reports as $report) {
				echo '<tr id="report-'.$report->id.'" data-id="'.$report->id.'" class="report-item">',
					'<td>', $report->user_anchor, '</td>',
					'<td>', $report->package_anchor, '</td>',
					'<td>', $report->controller_anchor, '</td>',
					'<td>', $report->method_anchor, '</td>',
					'<td>', $report->ip_address, '</td>',
					'<td>', date('Y/m/d H:i', $report->created_at), '</td>',
					'<td class="text-end">',
						html_tag('button', array(
							'type' => 'button',
							'data-endpoint' => nonce_ajax_url(
								'admin/reports/delete/'.$report->id,
								'delete-report_'.$report->id
							),
							'class' => 'btn btn-outline-danger btn-sm btn-icon report-delete',
						), fa_icon('trash').__('lang_delete')),
					'</td>',

				'</tr>';
			}
			echo '</tbody>';
		}
echo '</table>',
'</div>',
$pagination;