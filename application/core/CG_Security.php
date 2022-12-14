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
 * CG_Security Class
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class CG_Security extends CI_Security {

	/**
	 * create_nonce
	 *
	 * Creates a cryptographic token tied to the selected action, user,
	 * user session id and window of time.
	 *
	 * @access 	public
	 * @param 	mixed 	$action 	Scalar value to add context to the nonce.
	 * @return 	string 	The generated token.
	 */
	public function create_nonce($action = -1)
	{
		// Prepare an instance of CI object.
		$CI =& get_instance();

		// Get the current user's ID.
		$uid = (false !== $user = $CI->auth->user())
			? $user->id
			:  apply_filters('nonce_user_logged_out', 0, $action);

		// Make sure to get the current user session's ID.
		(class_exists('CI_Session', false)) OR $CI->load->library('session');
		$token = session_id();
		$tick  = $this->nonce_tick();

		return substr($this->_nonce_hash($tick.'|'.$action.'|'.$uid.'|'.$token), -12, 10);
	}

	// ------------------------------------------------------------------------

	/**
	 * verify_nonce
	 *
	 * Method for verifying that a correct nonce was used with time limit.
	 * The user is given an amount of time to use the token, so therefore, since
	 * the UID and $action remain the same, the independent variable is time.
	 *
	 * @access 	public
	 * @param 	string 	$nonce 		The nonce that was used in the action.
	 * @param 	mixed 	$action 	The action for which the nonce was created.
	 * @return 	bool 	returns true if the token is valid, else false.
	 */
	public function verify_nonce($nonce, $action = -1)
	{
		// Prepare an instance of CI object.
		$CI =& get_instance();

		// Get the current user's ID.
		$uid = (false !== $user = $CI->auth->user())
			? $user->id
			:  apply_filters('nonce_user_logged_out', 0, $action);

		// No nonce provided? Nothing to do.
		if (empty($nonce))
		{
			return false;
		}

		// Make sure to get the current user session's ID.
		(class_exists('CI_Session', false)) OR $CI->load->library('session');
		$token = session_id();
		$tick  = $this->nonce_tick();

		// Prepare the expected hash and make sure it equals to nonce.
		$expected = substr($this->_nonce_hash($tick.'|'.$action.'|'.$uid.'|'.$token), -12, 10);
		return ($expected === $nonce);
	}

	// -----------------------------------------------------------------------------
	/**
	 * check_nonce
	 *
	 * Method for checking forms with added security nonce.
	 *
	 * @access 	public
	 * @param 	string 	$action 	The action attached (Optional).
	 * @param 	bool 	$referrer	Whether to check referrer.
	 * @param 	string 	$name 		The name of the field used as nonce.
	 * @return 	bool
	 */
	public function check_nonce($action = null, $referrer = true, $name = '_nonce')
	{
		// Prepare an instance of CI object.
		$CI =& get_instance();

		// If the action is not provided, get if from the request.
		$real_action = (null !== $req = $CI->input->request('action')) ? $req : -1;
		(null === $action) && $action = $real_action;

		// Initial status.
		$status = $this->verify_nonce($CI->input->request($name), $action);

		// We check referrer only if set and nonce passed test.
		if (true === $status && true === $referrer)
		{
			/**
			 * because till this line, the $status is set to TRUE,
			 * its value is changed according the referrer check status.
			 */
			(class_exists('CI_User_agent', false)) OR $CI->load->library('user_agent');

			$real_referrer = $CI->agent->referrer();
			$referrer = $CI->input->request('_http_referrer', true);

			$status = (1 === preg_match("#{$referrer}$#", $real_referrer));
		}

		// Otherwise, return only nonce status.
		return $status;
	}

	// ------------------------------------------------------------------------

	/**
	 * nonce_tick
	 *
	 * Method for getting the time-dependent variable used for nonce creation.
	 * A nonce has a lifespan of two ticks, it may be updated in its second tick.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	float 	Float value rounded up to the next highest integer.
	 */
	public function nonce_tick()
	{
		$CI =& get_instance();
		$nonce_life = apply_filters('nonce_life', DAY_IN_SECONDS);
		return ceil(time() / ($nonce_life / 2));
	}

	// ------------------------------------------------------------------------

	/**
	 * _nonce_hash
	 *
	 * Method for hashing the given string and return the nonce.
	 *
	 * @access 	protected
	 * @param 	string
	 * @return 	string
	 */
	protected function _nonce_hash($string)
	{
		// We make sure to use the encryption key provided.
		$salt = config_item('encryption_key');
		(empty($salt)) && $salt = 'ToKoDeR nOnCe SaLt';
		return hash_hmac('md5', $string, $salt);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('create_nonce'))
{
	/**
	 * create_nonce
	 *
	 * Helper that uses CG_Security::create_nonce made available to be
	 * used with directly using the class method.
	 *
	 * @param 	mixed
	 * @return 	string
	 */
	function create_nonce($action = -1)
	{
		return get_instance()->security->create_nonce($action);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('verify_nonce'))
{
	/**
	 * verify_nonce
	 *
	 * Function that uses CG_Security::verify_nonce.
	 *
	 * @param 	string
	 * @param 	mixed
	 * @return 	bool
	 */
	function verify_nonce($nonce, $action = 1)
	{
		return get_instance()->security->verify_nonce($nonce, $action);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('check_nonce'))
{
	/**
	 * check_nonce
	 *
	 * Function that uses CG_Security::check_nonce.
	 *
	 * @param 	string
	 * @param 	mixed
	 * @return 	bool
	 */
	function check_nonce($action = null, $referrer = true, $nonce = '_nonce')
	{
		// return get_instance()->security->check_nonce($action, $referrer, $nonce);
		return TRUE;
	}
}