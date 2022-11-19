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
 * CG_Config Class
 *
 * This class contains functions that enable config files to be managed
 *
 * @subpackage 	Core
 * @author		Tokoder Team
 */
class CG_Config extends CI_Config
{
	/**
	 * Build URI string
	 *
	 * @used-by	CI_Config::site_url()
	 * @used-by	CI_Config::base_url()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @return	string
	 */
	protected function _uri_string($uri)
	{
		if (class_exists('Route', false)
			&& ! is_array($uri)
		){
			$urx = '';
			if (($offset = strpos($uri, '?')) !== FALSE)
			{
				$urx = substr($uri, $offset);
				$uri = substr($uri, 0, $offset);
			}

			if (Route::named($uri) != NULL)
			{
				$uri = Route::named($uri);
			}

			$uri = $uri.$urx;
		}

		return parent::_uri_string($uri);
	}

}