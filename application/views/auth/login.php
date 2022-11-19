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

// Form open tag.
echo form_open('login', 'id="login"'),
form_nonce('user-login');

do_action('auth-form-before');

// Username form.
echo '<div class="form-floating mb-3">',
print_input($login, array(
	'class' => 'form-control'.(has_error($login_type) ? ' is-invalid' : '')
)),
form_label(__("lang_{$login_type}")),
form_error($login_type, '<div class="form-text invalid-feedback">', '</div>'),
'</div>';

// Password field.
echo '<div class="form-floating mb-3">',
print_input($password, array(
	'class' => 'form-control'.(has_error('password') ? ' is-invalid' : '')
)),
form_label(__('lang_password')),
form_error('password', '<div class="form-text invalid-feedback">', '</div>'),
'</div>';

// Password field.
echo '<div class="form-group mb-3">',
	form_checkbox(FALSE, FALSE, FALSE, 'id="show_password" class="form-check-input"'),
	form_label(__('lang_show_password'), 'show_password', 'class="form-check-label"');

	// Lost password button.
	echo html_tag('a', array(
		'href'     => site_url('lost-password'),
		'class'    => 'float-end text-decoration-none text-muted',
		'tabindex' => '-1',
	), fa_icon('question-circle').__('lang_lost_password'));
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

/**
 * Fires before the closing login form tag.
 */
do_action('auth-form-after');

echo '<div class="form-group clearfix mb-0">';

// Display registration page if enabled.
if (false !== get_option('allow_registration', false)) {
echo html_tag('a', array(
	'href' => site_url('register'),
	'class' => 'btn btn-secondary'
), __('lang_create_account'));
}

// Login button.
echo
html_tag('button', array(
	'type' => 'submit',
	'class' => 'btn btn-primary float-end',
), fa_icon('sign-in').__('lang_login'));

echo '</div>';

/**
 * Fires before the closing login form tag.
 */
do_action('login_form');

// Form closing tag.
echo form_close();