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
 * Gamelang uri Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// ------------------------------------------------------------------------

if ( ! function_exists('__'))
{
    /**
     * Alias of CG_Lang::line with optional arguments.
     *
     * @param 	string 	$line 		the line the retrieve.
     * @param 	string 	$index 		whether to look under an index.
     * @param 	string 	$before 	Whether to put something before the line.
     * @param 	string 	$after 		Whether to put something after the line.
     * @return 	string
     */
    function __($line, $index = '', $before = '', $after = '')
    {
        // Shall we translate the before?
        if ('' !== $before && 1 === sscanf($before, 'lang:%s', $b_line))
        {
            $before = __($b_line, $index);
        }

        // Shall we translate the after?
        if ('' !== $after && 1 === sscanf($after, 'lang:%s', $a_line))
        {
            $after = __($a_line, $index);
        }

        return $before.get_instance()->lang->line($line, $index).$after;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('_e'))
{
	/**
	 * Alias of CG_Lang::line with optional arguments.
	 *
	 * @param 	string 	$line 		the line the retrieve.
	 * @param 	string 	$index 		whether to look under an index.
	 * @param 	string 	$before 	Whether to put something before the line.
	 * @param 	string 	$after 		Whether to put something after the line.
	 * @return 	string
	 */
	function _e($line, $index = '', $before = '', $after = '')
	{
		echo __($line, $index, $before, $after);
	}
}
