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

// -----------------------------------------------------------------------------

/**
 * Setup session data at login and autologin.
 *
 * @access
 * @param 	int 	$user_id 	the user's ID.
 * @param 	bool 	$remember 	whether to remember the user.
 * @param 	string 	$token 		the user's online token.
 * @param 	string 	$language 	the user's language.
 * @return 	bool
 */
function _set_session($user_id, $remember = false, $token = null, $language = null)
{
	// Make sure all neded data are present.
	if (empty($user_id))
	{
		return false;
	}

	// If no $token is provided, we generate a new one.
	if (empty($token))
	{
		get_instance()->load->library('encryption');
		$token = get_instance()->encryption->hash($user_id.session_id().rand());
	}

	// Fires before logging in the user.
	do_action('after_user_login', $user_id);

	// Prepare session data.
	$sess_data = array(
		'user_id'  => $user_id,
		'token'    => $token,
	);

	// Add user language only if available.
	if ($language && in_array($language, (array) get_instance()->config->item('languages')))
	{
		$sess_data['language'] = $language;
	}

	// Now we set session data.
	get_instance()->session->set_userdata($sess_data);

	// Now we create/update the variable.
	get_instance()->variables->set_var($user_id, 'online_token', $token, get_instance()->input->ip_address);

	// Put the user online.
	get_instance()->users->update($user_id, array('online' => 1));

	// The return depends on $remember.
	return (true === $remember) ? _set_cookie($user_id, $token) : true;
}

// ------------------------------------------------------------------------

/**
 * Sets the cookie for the user after a login.
 * @access
 * @param 	int 	$user_id 	the user's ID.
 * @param 	string 	$token 		the user's online token.
 * @return 	bool
 */
function _set_cookie($user_id, $token)
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
	get_instance()->input->set_cookie($cookie_name, $cookie_value, $cookie_expire);
	return true;
}

// ------------------------------------------------------------------------

/**
 * Attempt to retrieve and decode the current user's cookie.
 * @access
 * @param 	none
 * @return 	array if found, else false.
 */
function _get_cookie()
{
	// Allow themes and plug-ins to alter the cookie name.
	$cookie_name = apply_filters('user_cookie_name', 'c_user');

	// Check whether the cookie exists.
	$cookie = get_instance()->input->cookie($cookie_name, true);
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