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

add_script('auth.js');

// Form opening tag and nonce.
echo form_open('register', 'role="form" id="register" autocomplete="off"'),
form_nonce('user-register');

do_action('auth-form-before');

foreach ($inputs as $name => $input) {
	echo '<div class="form-floating mb-3">',
	print_input($input, array(
		'autofocus' => 'autofocus',
		'class' => 'form-control'.(has_error($name) ? ' is-invalid' : ''),
	)),
	form_label(__(strtoupper('lang_'.$name))),
	form_error($name, '<div class="form-text invalid-feedback">', '</div>'),
	'</div>';
}

// Password field.
echo '<div class="form-group mb-3">',
	form_checkbox(FALSE, FALSE, FALSE, 'id="show_password" class="form-check-input"'),
	form_label(__('lang_show_password'), 'show_password', 'class="form-check-label"');
echo '</div>';

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

// Fires before the closing registration form tag.
do_action('auth-form-after');

// Submit and login button.
echo '<div class="form-group mb-0">',

html_tag('a', array(
	'href' => site_url('login'),
	'class' => 'btn btn-secondary'
), __('lang_login')),

html_tag('button', array(
	'type' => 'submit',
	'class' => 'btn btn-primary float-end',
), __('lang_create_account')),

'</div>';

// Form closing tag.
echo form_close();