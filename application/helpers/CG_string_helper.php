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
 * Gamelang string Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// -----------------------------------------------------------------------------

if ( ! function_exists('str2bool'))
{
	/**
	 * Coverts a string boolean representation to a true boolean
	 * @access  public
	 * @param   string
	 * @param   boolean
	 * @return  boolean
	 */
	function str2bool($str, $strict = false) {
		// If no string is provided, we return 'false'
		if (empty($str)) {
			return false;
		}

		// If the string is already a boolean, no need to convert it
		if (is_bool($str)) {
			return $str;
		}

		$str = strtolower( @(string) $str);

		if (in_array($str, array('no', 'n', 'false', 'off'))) {
			return false;
		}

		if ($strict) {
			return in_array($str, array('yes', 'y', 'true', 'on'));
		}

		return true;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('_deep_replace'))
{
	/**
	 * Performs a deep string replace operation to ensure the values in
	 * $search are replace with values from $replace
	 * @param 	mixed 	$search
	 * @param 	mixed 	$replace
	 * @param 	mixed 	$subject 	String for single item, or array.
	 * @return 	mixed
	 */
	function _deep_replace($search='', $replace = '', $subject ='') {
		if ( ! is_array($subject)) {
			$subject = (string) $subject;
			$count = 1;
			while($count) {
				$subject = str_replace($search, $replace, $subject, $count);
			}

			return $subject;
		}

		foreach ($subject as $key => $val) {
			$subject[$key] = _deep_replace($search, $replace, $val);
		}

		return $subject;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('maybe_unserialize'))
{
	/**
	 * maybe_unserialize
	 *
	 * Turns a serialized string into its nature.
	 *
	 * @param 	string 	$string
	 * @return 	mixed
	 */
    function maybe_unserialize($string) {
		is_serialized($string) && $string = unserialize($string);
		return $string;
    }
}

// ------------------------------------------------------------------------
// PHP check serialized, serialize and unserialize.
// ------------------------------------------------------------------------

if ( ! function_exists('is_serialized'))
{
	/**
	 * is_serialized
	 *
	 * Checks whether the given string is a serialized.
	 *
	 * @param 	string
	 * @return 	bool
	 */
	function is_serialized($string) {
		$array = @unserialize($string);
		return ! ($array === false and $string !== 'b:0;');
	}
}

// ------------------------------------------------------------------------
// Value preparation before inserting and after getting from database.
// ------------------------------------------------------------------------

if ( ! function_exists('to_bool_or_serialize'))
{
	/**
	 * Takes any type of arguments and turns it into its string
	 * representations before inserting into databases.
	 * @param 	mixed 	$value
	 * @return 	string 	the string representation of "$value".
	 */
	function to_bool_or_serialize($value) {
		$value = (is_bool($value))
			? (true === $value ? 'true' : 'false')
			: maybe_serialize($value);
		return $value;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('maybe_serialize'))
{
	/**
	 * maybe_serialize
	 *
	 * Turns Array an Objects into serialized strings;
	 *
	 * @param 	mixed 	$value
	 * @return 	string
	 */
    function maybe_serialize($value) {
		(is_array($value) OR is_object($value)) && $value = serialize($value);
		return $value;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('from_bool_or_serialize'))
{
	/**
	 * Takes any type of data retrieved from database and turns it into
	 * it's original data type.
	 * @param 	string 	$str
	 * @return 	mixed
	 */
	function from_bool_or_serialize($string) {

		return is_str2bool($string, true)
			? str2bool($string)
			: maybe_unserialize($string);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('is_str2bool'))
{
	/**
	 * is_str2bool
	 *
	 * Function for checking whether the given string is a string
	 * representation of a boolean.
	 *
	 * @param 	string 	$str
	 * @param 	bool 	$string
	 * @return 	bool
	 */
	function is_str2bool($str, $strict = false)
	{
		if ($strict === false) {
			$str_test = @(string) $str;

			if (is_numeric($str_test)) {
				return true;
			}
		}

		return (!str2bool($str) OR str2bool($str, true));
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('deep_htmlentities'))
{
	/**
	 * Function for using "htmlentities" on anything.
	 *
	 * @param 	mixed 	$value
	 * @param 	int 	$flags
	 * @param 	string 	$encoding
	 * @param 	bool 	$double_encode
	 * @return 	string
	 */
	function deep_htmlentities($value, $flags = null, $encoding = null, $double_encode = null) {
		static $cached = array();

		(null === $flags) && $flags = ENT_QUOTES;
		(null === $encoding) && $encoding = 'UTF-8';
		(null === $double_encode) && $double_encode = false;

		if ( ! is_array($value)) {
			if ( ! isset($cached[$value])) {
				$cached[$value] = htmlentities($value, $flags, $encoding, $double_encode);
			}

			return $cached[$value];
		}

		foreach ($value as $key => $val) {
			$value[$key] = deep_htmlentities($val, $flags, $encoding, $double_encode);
		}

		return $value;
	}
}