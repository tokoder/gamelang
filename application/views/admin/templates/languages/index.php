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

add_script('language.js');

echo '<div class="table-responsive-sm">',
	'<table class="table table-striped table-hover mb-0">',
		'<thead>',
			'<tr>',
				'<th class="w-25">', __('lang_language'), '</th>',
				'<th class="w-15">', __('lang_ABBREVIATION'), '</th>',
				'<th class="w-15">', __('lang_FOLDER'), '</th>',
				'<th class="w-10">', __('lang_is_default'), '</th>',
				'<th class="w-10">', __('lang_enabled'), '</th>',
				'<th class="w-25 text-end">', __('lang_actions'), '</th>',
			'</tr>',
		'</thead>';

		if ($languages) {
			echo '<tbody id="languages-list">';
			foreach ($languages as $folder => $lang) {
				echo '<tr id="lang-'.$folder.'" data-name="'.$lang['name'].'">',

					'<td>';

					if (true === $lang['available']) {
						echo $lang['name_en'];
					} else {
						echo html_tag('del', array(
							'class' => 'text-danger',
							'title' => __('lang_missing_folder'),
						), $lang['name_en']);
					}
					echo html_tag('span', array(
						'class' => 'text-muted ms-2'
					), $lang['name']),
					'</td>',

					'<td>', $lang['code'], html_tag('small', array(
						'class' => 'text-muted ms-2'
					), $lang['locale']), '</td>',

					'<td>', $lang['folder'], '</td>',

					'<td>', label_condition($folder === $language), '</td>',

					'<td>', label_condition(in_array($folder, $available_languages)), '</td>',

					'<td class="text-end"><span class="btn-group">', implode('', $lang['actions']), '</span></td>',

				'</tr>';
			}
			echo '</tbody>';
		}
echo '</table>',
'</div>';
