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
 * Gamelang Cookie Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// ------------------------------------------------------------------------

/**
 * Sets the cookie for the user after a login.
 * @access
 * @param 	int 	$user_id 	the user's ID.
 * @param 	string 	$token 		the user's online token.
 * @return 	bool
 */
function user_set_cookie($user_id, $token)
{
	// If no data provided, nothing to do.
	if (empty($user_id) OR empty($token))
	{
		return false;
	}

	/**
	 * The idea behind this is to generate a new random string
	 * and append it to the user's ID and token then encode
	 * everything. IT will be harder to crack the cookie and when
	 * we try to get the cookie back, we only need the two first
	 * elements of the exploded cookie.
	 */
	get_instance()->load->library('encryption');
	$random = random_string('alnum', 128);
	$cookie_value = get_instance()->encryption->encode($user_id, $token, $random);

	// Allow themes and plug-ins to alter the cookie name.
	$cookie_name = apply_filters('user_cookie_name', 'c_user');

	/**
	 * Filters online token line so plugin can change it.
	 */
	$expire = apply_filters('user_cookie_life', MONTH_IN_SECONDS * 2);
	(is_int($expire) && $expire <= 0) OR $expire = MONTH_IN_SECONDS * 2;
	$cookie_expire = $expire;

	// Now we set the cookie.
	set_cookie($cookie_name, $cookie_value, $cookie_expire);
	return true;
}

// ------------------------------------------------------------------------

/**
 * Attempt to retrieve and decode the current user's cookie.
 * @access
 * @param 	none
 * @return 	array if found, else false.
 */
function user_get_cookie()
{
	// Allow themes and plug-ins to alter the cookie name.
	$cookie_name = apply_filters('user_cookie_name', 'c_user');

	// Check whether the cookie exists.
	$cookie = get_cookie($cookie_name, true);
	if ( ! $cookie)
	{
		return false;
	}

	// We load the hash library and decode the cookie.
	get_instance()->load->library('encryption');
	$cookie = get_instance()->encryption->decode($cookie);

	/**
	 * For the cookie to be valid, it has to not to be
	 * empty and MUST contain three (3) elements:
	 * 1. The user's ID.
	 * 2. The online token.
	 * 3. The random string generated when encoding the cookie.
	 */
	return (empty($cookie) OR count($cookie) !== 3) ? false : $cookie;
}