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
 * Gamelang Array Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// -----------------------------------------------------------------------------

if ( ! function_exists('force_array'))
{
	function force_array($array)
	{
		return (is_array($array)) ? $array : array();
	}
}

// --------------------------------------------------------------------

// Returns if array key exists. If not, return false if $default is not set or return $default instead
if (! function_exists('riake'))
{
    function riake($key, $subject, $default = false)
    {
        if (is_array($subject)) {
            return array_key_exists($key, $subject)
				? $subject[ $key ]
				: $default;
        }

        return $default;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('array_clean')) {
	/**
	 * This function make sure to clean the given array by first removing
	 * white-spaces from array values, then removing empty elements and
	 * final keep unique values.
	 *
	 * @param 	array
	 * @return 	array
	 */
	function array_clean($array = array()) {
		if ( ! empty($array)) {
			$array = array_map('trim', $array);
			$array = array_unique(array_filter($array));
		}

		return $array;
	}
}