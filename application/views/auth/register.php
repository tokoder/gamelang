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

// Form opening tag and nonce.
echo form_open('register', 'role="form" id="register" autocomplete="off"'),
form_nonce('user-register');

do_action('register-form-before');

foreach ($inputs as $name => $input) {
	echo '<div class="form-floating mb-3">',
	print_input($input, array(
		'autofocus' => 'autofocus',
		'class' => 'form-control'.(has_error($name) ? ' is-invalid' : ''),
	)),
	form_label($input['placeholder']),
	form_error($name, '<div class="form-text invalid-feedback">', '</div>'),
	'</div>';
}

// Password field.
echo '<div class="form-group mb-3">';
	echo html_tag('label', 'class="d-inline-flex gap-2"',
		form_checkbox(FALSE, FALSE, FALSE, 'id="show_password" class="form-check-input"')
		.html_tag('span', [], __('lang_show_password'))
	);
echo '</div>';

// Captcha field.
echo $this->load->view('auth/captcha', null, true);

// Fires before the closing registration form tag.
do_action('register-form-after');

// Submit and login button.
echo '<div class="form-group mb-0">',

html_tag('a', array(
	'href' => site_url('login'),
	'class' => 'btn btn-outline-secondary'
), fa_icon('arrow-left').__('lang_login')),

html_tag('button', array(
	'type' => 'submit',
	'class' => 'btn btn-primary float-end',
), fa_icon('paper-plane').__('lang_create_account')),

'</div>';

// Form closing tag.
echo form_close();