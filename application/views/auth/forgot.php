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

// Recover tip.
echo '<p class="mb-3">', __('Enter your username or email address and we will send you a link to activate your account'), '</p>';

// Form opening tag and nonce.
echo form_open('lost-password', 'role="form" id="forgot"'),
form_nonce('user-lost-password');

// email field.
echo '<div class="form-floating mb-3">',
print_input($email, array(
	'class' => 'form-control'.(has_error('email') ? ' is-invalid' : '')
)),
form_label($email['placeholder']),
form_error('email', '<div class="form-text invalid-feedback">', '</div>'),
'</div>';

// Captcha field.
echo $this->load->view('auth/captcha', null, true);

/**
 * Fires before the closing lost password form tag.
 */
do_action('forgot-form-after');

// Login button.
echo
html_tag('a', array(
	'href' => site_url('login'),
	'class' => 'btn btn-outline-secondary',
), fa_icon('arrow-left').__('lang_login'));

// Submit button.
echo
html_tag('button', array(
	'type' => 'submit',
	'class' => 'btn btn-primary float-end'
), fa_icon('paper-plane').__('lang_send_link'));

// Form closing tag.
echo form_close();