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

// Form opening tag and nonce.
echo form_open('reset-password?code='.$code, 'role="form" id="reset"'),
form_nonce('user-reset-password_'.$code),

// New password field.
'<div class="form-floating mb-3">',
print_input($npassword, array(
	'class' => 'form-control'.(has_error('npassword') ? ' is-invalid' : '')
)),
form_label($npassword['placeholder']),
form_error('npassword', '<div class="form-text invalid-feedback">', '</div>'),
'</div>',

// Confirm password field.
'<div class="form-floating mb-3">',
print_input($cpassword, array(
	'class' => 'form-control'.(has_error('cpassword') ? ' is-invalid' : '')
)),
form_label($cpassword['placeholder']),
form_error('cpassword', '<div class="form-text invalid-feedback">', '</div>'),
'</div>';

// Submit button and login anchor.
echo '<div class="form-group mb-0">',

// Login button.
html_tag('a', array(
	'href' => site_url('login'),
	'class' => 'btn btn-secondary',
), fa_icon('sign-in').__('lang_login')),

// Submit button.
html_tag('button', array(
	'type' => 'submit',
	'class' => 'btn btn-primary float-end'
), fa_icon('lock').__('lang_reset_password')),

'</div>';

// Form closing tag.
echo form_close();