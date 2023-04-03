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

// Form open tag.
echo form_open(current_url(), 'id="login"'),
form_nonce('user-login');

if (ENVIRONMENT == 'development') :
echo '<div class="list-group">',
	html_tag('li', ['class'=>'list-group-item list-group-item-dark'], __('Demo Account'));
	echo html_tag('a', array(
		'href' => 'javascript:void(0)',
		'onclick' => 'copy(\'admin\', \'admin123\')',
		'class' => 'list-group-item list-group-item-action d-flex justify-content-between align-items-center',
	),fa_icon('external-link').__('admin'));

	do_action('account_demo');
echo '</div>
<!-- Gradient divider -->
<hr data-content="Test with Demo Account" class="hr-text my-4 opacity-100">';
endif;

do_action('login-form-before', true);

// Username form.
echo '<div class="form-floating mb-3">',
	print_input($login, array(
		'class' => 'form-control'.(has_error($login_type) ? ' is-invalid' : '')
	)),
	form_label($login['placeholder']),
	form_error($login_type, '<div class="form-text invalid-feedback">', '</div>'),
'</div>';

// Password field.
echo '<div class="form-floating mb-3">',
	print_input($password, array(
		'class' => 'form-control'.(has_error('password') ? ' is-invalid' : '')
	)),
	form_label($password['placeholder']),
	form_error('password', '<div class="form-text invalid-feedback">', '</div>'),
'</div>';

echo '<div class="form-group mb-3">';
	// Show password.
	echo html_tag('label', 'class="d-inline-flex gap-2"',
		form_checkbox(FALSE, FALSE, FALSE, 'id="show_password" class="form-check-input"')
		.html_tag('span', [], __('lang_show_password'))
	);

	// Lost password button.
	echo html_tag('a', array(
		'href' => site_url('lost-password'),
		'class' => 'float-end text-decoration-none text-muted',
	), fa_icon('question-circle').__('lang_lost_password'));
echo '</div>';

// Captcha field.
echo $this->load->view('auth/captcha', null, true);

/**
 * Fires before the closing login form tag.
 */
do_action('login-form-after');

echo '<div class="form-group mb-3">';
	// Remember field.
	echo html_tag('label', 'class="d-inline-flex gap-2"',
		form_checkbox('remember', 1, FALSE, 'class="form-check-input"')
		.html_tag('span', [], __('lang_remember'))
	);

	// Display registration page if enabled.
	if (false !== get_option('allow_registration', false) && apply_filters('host_user', false) == false) {
		echo html_tag('a', array(
			'href' => site_url('register'),
			'class' => 'float-end text-decoration-none text-muted'
		), fa_icon('external-link').__('lang_create_account'));
	}
echo '</div>';

// Login button.
echo html_tag('button', array(
	'type' => 'submit',
	'class' => 'btn btn-block btn-primary w-100 mb-3',
), fa_icon('sign-in').__('lang_login'));

// Form closing tag.
echo form_close();