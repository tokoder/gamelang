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

/**
 * CG_Encryption Class
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class CG_Encryption extends CI_Encryption {

	/**
	 * @var string - glue used for encryption and imploding;
	 */
	public $glue = '~';

	/**
	 * Class constructor
	 *
	 * @param	array	$params	Configuration parameters
	 * @return	void
	 */
	public function __construct(array $params = array())
	{
		parent::__construct($params);
	}

	// --------------------------------------------------------------------

	/**
	 * Encrypt
	 *
	 * @param	string	$data	Input data
	 * @param	array	$params	Input parameters
	 * @return	string
	 */
	public function encode()
	{
		if ( ! empty($args = func_get_args()))
		{
			(is_array($args[0])) && $args = $args[0];

			return parent::encrypt(implode($this->glue, $args));
		}

		return null;
	}

	// -----------------------------------------------------------------------------

	/**
	 * Takes a string and try to decrypt it using encryption library.
	 * @param 	string 	$str 	the string to decrypt
	 * @return 	array|null
	 */
	public function decode($str)
	{
		if ( ! empty($str))
		{
			$decoded = parent::decrypt($str);

			return (empty($decoded)) ? null : explode($this->glue, $decoded);
		}

		return null;
	}

	// ------------------------------------------------------------------------

	/**
	 * Checks whether the given password is valid after comparison
	 * against a stored hashed password.
	 *
	 * @param 	string 	$password
	 * @param 	string 	$stored_hash
	 * @return 	bool 	true if valid, else false.
	 */
	function verify($password, $stored_hash)
	{
		// Fall-back to "password_verify".
		if (function_exists('password_verify')) {
			return password_verify($password, $stored_hash);
		}

		return false;
	}

	// -----------------------------------------------------------------------------

	/**
	 * Hashes a given string, aka password, using phpass library.
	 *
	 * @param 	string 	$password
	 * @return 	string 	the password after being hashed.
	 */
	function hash($password)
	{
		// Fall-back to "password_hash".
		if (function_exists('password_hash')) {
			return password_hash($password, PASSWORD_BCRYPT);
		}

		return $password;
	}
}