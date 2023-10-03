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
 * Gamelang html Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// -----------------------------------------------------------------------------

if ( ! function_exists('number_format_short'))
{
	/**
	 * Formats a numbers as K,M,B,T and adds the appropriate suffix
	 *
	 * M = million (juta)
	 * B = billion (miliar)
	 * T = trilion (triliun)
	 *
	 * @param	mixed	will be cast as int
	 * @param	int
	 * @return	string
	 */
	function number_format_short($num, $precision = 1)
	{
		if ($num < 900)
		{
			// 0 - 900
			$num = number_format($num, $precision);
			$suffix = '';
		}
		elseif ($num < 900000)
		{
			// 0.9k-850k
			$num = number_format($num / 1000, $precision);
			$suffix = 'K';
		}
		elseif ($num < 900000000)
		{
			// 0.9m-850m
			$num = number_format($num / 1000000, $precision);
			$suffix = 'M';
		}
		elseif ($num < 900000000000)
		{
			// 0.9b-850b
			$num = number_format($num / 1000000000, $precision);
			$suffix = 'B';
		}
		else
		{
			// 0.9t+
			$num = number_format($num / 1000000000000, $precision);
			$suffix = 'T';
		}

		if ( $precision > 0 )
		{
			$dotzero = '.' . str_repeat( '0', $precision );
			$num = str_replace( $dotzero, '', $num );
		}

		return $num . $suffix;
	}
}
