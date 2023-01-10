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
echo '<p class="mb-3">', __('lang_RESTORE_tip'), '</p>',

// Form opening tag and nonce.
form_open('restore-account', 'role="form" id="restore"'),
form_nonce('user-restore'),

// Identity field.
'<div class="form-floating mb-3">',
print_input($identity, array(
	'class' => 'form-control'.(has_error('identity') ? ' is-invalid' : '')
)),
form_label(__('lang_identity')),
form_error('identity', '<div class="form-text invalid-feedback">', '</div>'),
'</div>',

// Password field.
'<div class="form-floating mb-3">',
print_input($password, array(
	'class' => 'form-control'.(has_error('password') ? ' is-invalid' : '')
)),
form_label(__('lang_password')),
form_error('password', '<div class="form-text invalid-feedback">', '</div>'),
'</div>';

if (get_option('use_captcha', false) === true) :
	// Captcha field.
	$captcha = generate_captcha();
	echo '<div class="form-group mb-3">';
	if (get_option('use_recaptcha', false) === true && ! empty(get_option('recaptcha_site_key', null))) {
		echo $captcha['captcha'];
	} else {
		echo
		'<div class="input-group">
			<div class="input-group-text pos-rel">'
				.$captcha['image'].
				html_tag('a', array(
					'class' => 'btn p-0 pos-abs',
					'id' => 'refresh_captcha',
				), fa_icon('rotate')),
			'</div>',
			print_input($captcha['captcha'], array(
				'class' => 'form-control'.(has_error('captcha') ? ' is-invalid' : ''),
				'tabindex' => '-1',
			)),
			form_error('captcha', '<div class="invalid-feedback">', '</div>'),
		'</div>';
	}
	echo '</div>';
endif;

/**
 * Fires before the closing restore account form tag.
 */
do_action('auth-form-after');

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