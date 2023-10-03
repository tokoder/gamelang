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
 * Gamelang directory Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// -----------------------------------------------------------------------------

if ( ! function_exists('directory_delete'))
{
	/**
	 * Delete all directory's files and subdirectories.
	 * @param 	string 	$dir 	The directory path to delete.
	 * @return 	bool 	true if delete, else false.
	 */
	function directory_delete($dir)
	{
		// We make sure $dir is a valid directory first.
		if (is_dir($dir))
		{
			// Let's collect its elements.
			$elements = scandir($dir);
			foreach ($elements as $element)
			{
				// We ignore some of elements.
				if ( ! in_array($element, array('.', '..', '.git', '.github')))
				{
					// Directory?
					if (is_dir($dir.'/'.$element))
					{
						directory_delete($dir.'/'.$element);
					}
					// A file?
					else
					{
						unlink($dir.'/'.$element);
					}
				}
			}

			// Now we remove the main directory.
			rmdir($dir);
			return true;
		}

		return false;
	}
}
