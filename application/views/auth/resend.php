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

// Tip.
echo '<p class="mb-3">', __('Enter your username or email address and we will send you a link to activate your account'), '</p>',

// Form opening tag and nonce.
form_open('resend-link', 'role="form" id="resend" autocomplete="off"'),
form_nonce('user-resend-link'),

// Identity field.
'<div class="form-floating mb-3 ">',
print_input($identity, array(
	'class' => 'form-control'.(has_error('identity') ? ' is-invalid' : '')
)),
form_label($identity['placeholder']),
form_error('identity', '<div class="form-text invalid-feedback">', '</div>'),
'</div>';

// Captcha field.
echo $this->load->view('auth/captcha', null, true);

/**
 * Fires before the closing resend link form tag.
 */
do_action('resend-form-after');

// Submit and login buttons.
echo '<div class="form-group clearfix mb-0">',

html_tag('a', array(
	'href' => site_url('login'),
	'class' => 'btn btn-secondary',
), fa_icon('sign-in').__('lang_login')),

html_tag('button', array(
	'type' => 'submit',
	'class' => 'btn btn-primary float-end'
), fa_icon('paper-plane').__('lang_resend_link')),

'</div>';

// Form closing tag.
echo form_close();