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

echo '<div class="row">';

	echo '<div class="col-sm-6">';

		echo form_open_multipart('', array(
			'role'  => 'form',
			'id'    => 'settings-'.$tab,
			'class' => 'mb-3'
		));

		echo form_nonce('settings-'.$tab);

		foreach ($inputs as $name => $input)
		{
			echo '<div class="form-group mb-3">',
				form_label(__('lang_'.$name), $name),
				print_input($input, array('class' => 'form-control'));

				if ( ! empty(form_error($name)) ) {
					echo form_error($name, '<div class="form-text invalid-feedback">', '</div>');
				} else {
					echo html_tag('div', 'class="form-text text-muted"', __('lang_'.$name.'_tip'));
				}
			echo '</div>';
		}

		echo html_tag('button', array(
			'type'  => 'submit',
			'class' => 'btn btn-primary'
		), __('lang_save_changes'));

		echo form_close();

	echo '</div>';

	/**
	 * Fires on settings.
	 */
	if ( has_action('settings-'.$tab)) {
		echo '<div class="col-sm-6">';
		do_action('settings-'.$tab);
		echo '</div>';
	}

echo '</div>';