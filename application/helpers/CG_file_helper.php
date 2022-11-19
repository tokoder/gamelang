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
 * Gamelang file Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// -----------------------------------------------------------------------------

if ( ! function_exists('json_read_file'))
{
	/**
	 * json_read_file
	 *
	 * Function for reading JSON encoded files content.
	 *
	 * @param 	string 	$path 	The path to the file to read.
	 * @return 	mixed 	Array if the file is found and valid, else false.
	 */
	function json_read_file($path)
	{
		// Make sure function remember read files.
		static $cached = array();

		// No already cached?
		if ( ! isset($cached[$path])) {
			// Make sure the file exists.
			if (true !== is_file($path)) {
				return false;
			}

			// Get the content of the file and cache it if found.
			$content = file_get_contents($path);
			$content = json_decode($content, true);
			is_array($content) && $cached[$path] = $content;
		}

		return isset($cached[$path]) ? $cached[$path] : false;
	}
}

// -----------------------------------------------------------------------------

if ( ! function_exists('validate_file'))
{
	/**
	 * Functions for validating a file name and path against an allowed set of rules.
	 * @param 	string 	$file 			The file path.
	 * @param 	array 	$allowed_files 	Array of allowed files.
	 * @return 	bool 	returns TRUE if valid, else FALSE.
	 */
	function validate_file($file, $allowed_files = array())
	{
		$status = TRUE;

		// "../" on its own is not allowed:
		if ('../' === $file)
		{
			$status = FALSE;
		}

		// More than one occurence of "../" is not allowed:
		elseif (preg_match_all('#\.\./#', $file, $matches, PREG_SET_ORDER) && (count($matches) > 1))
		{
			$status = FALSE;
		}

		// "../" which does not occur at the end of the path is not allowed:
		elseif (FALSE !== strpos($file, '../') && '../' !== mb_substr($file, -3, 3))
		{
			$status = FALSE;
		}

		// Files not in the allowed file list are not allowed:
		elseif ( ! empty($allowed_files) && ! in_array($file, $allowed_files))
		{
			$status = FALSE;
		}

		// Absolute Windows drive paths are not allowed:
		elseif (':' == substr($file, 1, 1))
		{
			$status = FALSE;
		}

		return $status;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('unzip_file'))
{
	/**
	 * Unzips a specified ZIP file to a location on the disk.
	 * @param 	string 	$file 	Full path and filename of zip archive.
	 * @param	string 	$path 	Full path to extract archive to.
	 * @return 	bool 	TRUE if everything is okey, else false.
	 */
	function unzip_file($file, $path)
	{
		// Prepare an array directories to be created.
		$needed_dirs = array();

		// Format our path.
		$path = rtrim($path, '/\\');

		// Determine any needed parent directories.
		if (FALSE !== is_dir($path))
		{
			$_path = preg_split('![/\\\]!', $path);
			$path_len = count($_path);
			for ($i = $path_len; $i >= 0; $i--)
			{
				if (empty($_path[$i]))
				{
					continue;
				}

				$dir = implode('/', array_slice($_path, 0, $i + 1));

				// Ignore it if it looks like Windows Drive letter.
				if (preg_match('!^[a-z]:$!i', $dir))
				{
					continue;
				}

				// The folder does not exist? Add it.
				if (FALSE === is_dir($dir))
				{
					$needed_dirs[] = $dir;
				}
				// Otherwise, no further action needed.
				else
				{
					break;
				}
			}
		}

		return _unzip_file_ziparchive($file, $path.DIRECTORY_SEPARATOR, $needed_dirs);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('_unzip_file_ziparchive'))
{
	/**
	 * Function for unzipping an archive using the ZipArhive class.
	 * @param 	string 	$file 			The full path to the file.
	 * @param 	string 	$path 			Full path where the archive should be extracted.
	 * @param 	array 	$needed_dirs 	Array of required folders to be created.
	 * @return 	bool 	TRUE if everything goes well, else false.
	 * NOTE: make sure to use "unzip_file" instead, it handles things better.
	 */
	function _unzip_file_ziparchive($file, $path, $needed_dirs = array())
	{
		// Prepare instance of ZipArchive class.
		$zip = new ZipArchive();

		// We open the file and make sure it exists.
		$zip_open = $zip->open($file, ZIPARCHIVE::CHECKCONS);
		if (TRUE !== $zip_open)
		{
			return FALSE;
		}

		// Prepare uncompressed file size.
		$uncompressed_size = 0;

		// We make sure to keep only valid files.
		for ($i = 0; $i < $zip->numFiles; $i++)
		{
			// Could not retrieve file from archive?
			if (FALSE === $info = $zip->statIndex($i))
			{
				return FALSE;
			}

			/**
			 * Now we make sure to skip the OS X-Created __MACOSX directory
			 * then make sure the file is valid, otherwise, skip it.
			 */
			if ('__MACOSX/' === substr($info['name'], 0, 9)
				OR TRUE !== validate_file($info['name']))
			{
				continue;
			}

			// Increment uncompressed size.
			$uncompressed_size += $info['size'];

			// Is it a directory? Added to needed_dirs array.
			if ('/' === substr($info['name'], -1))
			{
				$needed_dirs[] = $path.rtrim($info['name'], '/\\');
			}
			// Path to a file?
			elseif ('.' !== $dirname = dirname($info['name']))
			{
				$needed_dirs[] = $path.rtrim($dirname, '/\\');
			}
		}

		// We make sure we have enough disk space to proceed.
		$disk_space = @disk_free_space($path);
		if ($disk_space && $disk_space < ($uncompressed_size * 2.1))
		{
			return false;
		}

		// We make sure to keep unique values.
		$needed_dirs = array_unique($needed_dirs);

		// We make sure parent folder or folders all exists within the array.
		foreach ($needed_dirs as $dir)
		{
			/**
			 * We simply skip the working directory because it exists or
			 * will be created. we also skip the directory if it is not
			 * within the working directory.
			 */
			if (rtrim($path, '/\\') == $dir OR false === strpos($dir, $path))
			{
				continue;
			}

			// We make sure the parent folder is within the array.
			$parent_folder = dirname($dir);
			while ( ! empty($parent_folder)
				&& rtrim($path, '/\\') != $parent_folder
				&& ! in_array($parent_folder, $needed_dirs))
			{
				$needed_dirs[] = $parent_folder;
				$parent_folder = dirname($parent_folder);
			}
		}

		// Sort an array and maintain index association.
		asort($needed_dirs);

		// Now we make sure to create directory if needed.
		foreach ($needed_dirs as $_dir)
		{
			if (true !== is_dir($_dir) && false === mkdir($_dir, 0755))
			{
				return false;
			}
		}

		// No longer needed, we remove it.
		unset($needed_dirs);

		// We proceed to creating files.
		for ($i = 0; $i < $zip->numFiles; $i++)
		{
			// Invalid file? Nothing to do.
			if (false === $info = $zip->statIndex($i))
			{
				return false;
			}

			// We skip directories, Mac OSX directory and make sure the file is valid.
			if ('/' == substr($info['name'], -1)
				OR '__MACOSX/' === substr($info['name'], 0, 9)
				OR true !== validate_file($info['name']))
			{
				continue;
			}

			// Get file contents and stop if invalid or couldn't be written.
			$contents = $zip->getFromIndex($i);
			if (false === $contents
				OR false === file_put_contents($path.$info['name'], $contents, 0644))
			{
				return false;
			}
		}

		// We are cool, we close the zip and return TRUE.
		$zip->close();
		return true;
	}
}