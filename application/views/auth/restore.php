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

// Restore tip.
echo '<p class="mb-3">', __('Enter your username/email address and password to restore your account'), '</p>',

// Form opening tag and nonce.
form_open('restore-account', 'role="form" id="restore"'),
form_nonce('user-restore'),

// Identity field.
'<div class="form-floating mb-3">',
print_input($identity, array(
	'class' => 'form-control'.(has_error('identity') ? ' is-invalid' : '')
)),
form_label($identity['placeholder']),
form_error('identity', '<div class="form-text invalid-feedback">', '</div>'),
'</div>',

// Password field.
'<div class="form-floating mb-3">',
print_input($password, array(
	'class' => 'form-control'.(has_error('password') ? ' is-invalid' : '')
)),
form_label($password['placeholder']),
form_error('password', '<div class="form-text invalid-feedback">', '</div>'),
'</div>';

// Captcha field.
echo $this->load->view('auth/captcha', null, true);

/**
 * Fires before the closing restore account form tag.
 */
do_action('resend-form-after');

// Submit and login button.
echo '<div class="form-group mb-0">',

html_tag('a', array(
	'href' => site_url('login'),
	'class' => 'btn btn-secondary'
), fa_icon('sign-in').__('lang_login')),

html_tag('button', array(
	'type' => 'submit',
	'class' => 'btn btn-primary float-end',
), fa_icon('unlock-alt').__('lang_restore_account')),

'</div>';

// Form closing tag.
echo form_close();