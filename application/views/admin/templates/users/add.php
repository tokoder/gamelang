<?php
/**
 * tokoder
 *
 * An Open-source online ordering and management system for store
 *
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link			https://github.com/tokoder/tokoder
 * @since		1.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

echo '<div class="container">';
// Form opening tag and nonce.
echo form_open(admin_url('users/add'), 'role="form" id="add-user" class="row"'),
form_nonce('add-user');

// Display inputs.
echo '<div class="col-xs-12 col-md-6">';
	foreach ($inputs as $name => $input) {
		if (in_array($input['name'], ['password', 'cpassword'])) {
			continue;
		}
		echo '<div class="form-group mb-3">',
		form_label($input['placeholder'], $input['name']),
		print_input($input, array(
			'autofocus' => 'autofocus',
			'class' => 'form-control'.(has_error($name) ? ' is-invalid' : ''),
		)),
		form_error($name, '<div class="form-text invalid-feedback">', '</div>'),
		'</div>';
	}
// End of column
echo '</div>';

// Display metas.
echo '<div class="col-xs-12 col-md-6">';
	foreach ($inputs as $name => $input) {
		if ( ! in_array($input['name'], ['password', 'cpassword'])) {
			continue;
		}
		echo '<div class="form-group mb-3">',
		form_label($input['placeholder'], $input['name']),
		print_input($input, array(
			'required' => 'required',
			'class' => 'form-control'.(has_error($name) ? ' is-invalid' : ''),
		)),
		form_error($name, '<div class="form-text invalid-feedback">', '</div>'),
		'</div>';
	}
	echo '<div class="form-group mb-3">';
		echo html_tag('label', 'class="d-inline-flex gap-2"',
			form_checkbox(FALSE, FALSE, FALSE, 'id="show_password" class="form-check-input"')
			.html_tag('span', [], __('lang_show_password'))
		);
		// Account status
		echo html_tag('label', 'class="d-inline-flex gap-2 float-end"',
			form_checkbox('enabled', 1, set_checkbox('enabled', '1', false), 'id="enabled" class="form-check-input"')
			.html_tag('span', [], __('lang_active'))
		);
	echo '</div>',

	// Submit button and cancel.
	'<div class="form-group mb-3 border-top pt-3">',
	html_tag('button', array(
		'type' => 'submit',
		'class' => 'btn btn-primary '
	), __('lang_add_user')),
	html_tag('a', array(
		'href'  => admin_url('users'),
		'class' => 'btn btn-secondary float-end',
	), __('lang_cancel')),
	'</div>';

// End of column
echo '</div>';

// Form closing tag.
echo form_close();
echo '</div>';

add_filter( 'after_admin_scripts', function ( $output ) {
	$output .= <<<EOT
	<script>
	$(document).ready(function () {
		$(document).on('click', "#show_password", function(){
			if($(this).is(':checked')){
				$("input[name='password'], input[name='cpassword']").attr('type','text');
			}else{
				$("input[name='password'], input[name='cpassword']").attr('type','password');
			}
		});
	});
	</script>
	EOT;
	return $output;
});