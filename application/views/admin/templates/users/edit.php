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

echo '<div class="row">',
'<div class="col-xs-12 col-sm-8 col-md-6">',

// Form opening tag and nonce.
form_open(admin_url('users/edit/'.$user->id), 'role="form" id="edit-user"'),
form_nonce('edit-user_'.$user->id);

// Display inputs.
foreach ($inputs as $name => $input) {
	echo '<div class="form-group mb-3">',
	form_label($input['placeholder'], $input['name']),
	print_input($input, array(
		'class' => 'form-control'.(has_error($name) ? ' is-invalid' : ''),
	)),
	form_error($name, '<div class="form-text invalid-feedback">', '</div>'),
	'</div>';
}

echo '<div class="form-group mb-3">',
// Account status
form_checkbox('enabled', 1, (1 == $user->enabled), 'id="enabled"'),
html_tag('label', array('for' => 'enabled'), __('lang_active')),
'</div>',

// Submit button and cancel.
'<div class="form-group mb-0">',
html_tag('button', array(
	'type' => 'submit',
	'class' => 'btn btn-primary '
), __('lang_save_changes')),
html_tag('a', array(
	'href'  => admin_url('users'),
	'class' => 'btn btn-secondary float-end',
), __('lang_cancel')),
'</div>',

// Form closing tag.
form_close(),

// End of column
'</div>',

// End of row.
'</div>';