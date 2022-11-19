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

/**
 * Gamelang CAPTCHA Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// ------------------------------------------------------------------------

if ( ! function_exists('generate_captcha'))
{
	/**
	 * Generate a captcha field.
	 * @access 	public
	 * @param 	int 	$guid 	the user's ID.
	 * @return 	array 	captcha image URL and form details.
	 */
	function generate_captcha($guid = 0)
	{
		// Using reCAPTCHA?
		if (get_option('use_recaptcha', false) === true && ! empty(get_option('recaptcha_site_key', null)))
		{
			// Add reCAPTCHA script tag.
			get_instance()->theme->add('js', 'https://www.google.com/recaptcha/api.js', 'recaptcha');

			// Return both captcha field and empty image.
			$captcha = array(
				'captcha' => '<div class="g-recaptcha" data-sitekey="'.get_option('recaptcha_site_key').'"></div>',
				'image'   => null,
			);

			return $captcha;
		}

		// First, we delete old captcha
		get_instance()->variables->delete_by(array(
			'name'         => 'captcha',
			'created_at <' => time() - (MINUTE_IN_SECONDS * 5),
		));

		// Load captcha config file.
		get_instance()->load->config('captcha', true);

		// Generate the new captcha.
		$cap = create_captcha(get_instance()->config->item('captcha'));
		if( ! $cap) {
			return show_error('create_captcha():');
			exit;
		}

		// Insert catpcha details into database if not found.
		$var = get_instance()->variables->get_by(array(
			'guid'   => $guid,
			'name'   => 'captcha',
			'params' => get_instance()->input->ip_address(),
		));

		// If not found, create it.
		if ( ! $var)
		{
			get_instance()->variables->add_var(
				$guid,
				'captcha',
				$cap['word'],
				get_instance()->input->ip_address()
			);
		}
		// Found? Update it.
		else
		{
			get_instance()->variables->update($var->id, array(
				'value'      => $cap['word'],
				'created_at' => time(),
				'params'     => get_instance()->input->ip_address(),
			));
		}

		$captcha = array(
			'filename' => $cap['filename'],
			'image'    => $cap['image'],
			'captcha'  => array(
				'type'        => 'text',
				'name'        => 'captcha',
				'id'          => 'captcha-input',
				'placeholder' => 'Type the text you see on the image',
				'maxlength'   => get_instance()->config->item('word_length', 'captcha'),
				'required' 	  => '',
			),
		);

		return $captcha;
	}
}
