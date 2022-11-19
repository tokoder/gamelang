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
 * Gamelang path Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// -----------------------------------------------------------------------------

if ( ! function_exists('normalize_path'))
{
	/**
	 * Normalizes a filesystem path.
	 *
	 * @param 	string 	$path 	Path to normalize.
	 * @return 	string 	Normalized path.
	 */
	function normalize_path($path)
	{
		$path = str_replace('\\', '/', $path);
		$path = preg_replace('|(?<=.)/+|', '/', $path);

		// Upper-case driver letters on windows systems.
		if (':' === substr($path, 1, 1) && ! ctype_upper($path[0]))
		{
			$path = ucfirst($path);
		}

		return $path;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('path_join'))
{
	/**
	 * Joins two filesystem paths together.
	 *
	 * @param 	string 	$base 	The base path.
	 * @param 	string 	$path 	The relative path to $base.
	 * @return 	string 	The path with base or absolute path.
	 */
	function path_join($base, $path) {
		// If the provided $path is not an absolute path, we prepare it.
		if ( ! path_is_absolute($path)) {
			$base = rtrim(str_replace('\\', '/', $base), '/').'/';
			$path = ltrim(str_replace('\\', '/', $path), '/');
			$path = $base.$path;
		}

		return $path;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('path_is_absolute'))
{
	/**
	 * Tests if the given path is absolute.
	 *
	 * @param 	string 	$path 	File path.
	 * @return 	bool 	true if the path is absolute, else false.
	 */
	function path_is_absolute($path) {

		/**
		 * This is definitely true bu fails if $path does not exist or
		 * contains a symbolic link.
		 */
		if (realpath($path) == $path) {
			return true;
		}

		// Ignore parent directory.
		if (strlen($path) == 0 OR $path[0] == '.') {
			return false;
		}

		// Windows absolute paths.
		if (preg_match('#^[a-zA-Z]:\\\\#', $path)) {
			return true;
		}

		/**
		 * Path starting with / or \ is absolute.
		 * Anything else is relative.
		 */
		return ($path[0] == '/' || $path[0] == '\\');
	}
}

// -----------------------------------------------------------------------------

if ( ! function_exists('import'))
{
	/**
	 * Function for loading files.
	 *
	 * @param 	string 	$path 	The path to the file.
	 * @param 	string 	$folder 	The folder where the file should be.
	 * @return 	void
	 */
	function import($path, $folder = 'core')
	{
		$path = normalize_path($path);

		// Does the application has an override (on new class).
		if (false !== is_file($apppath = APPPATH.$folder.'/'.$path.'.php')) {
			require_once($apppath);
		}
	}
}