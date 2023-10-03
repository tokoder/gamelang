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
 * CG_URI Class
 *
 * Parses URIs and determines routing
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class CG_URI extends CI_URI
{
	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		$this->input =& load_class('Input', 'core');
		parent::__construct();
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetches the URI string.
	 *
	 * Overrides CodeIgniter default function in order to optionally
	 * include GET parameters.
	 *
	 * @param 	bool 	$include_get 	Whether to include GET parameters.
	 * @return 	string
	 */
	public function uri_string($include_get = false)
	{
		if ($include_get
			&& function_exists('http_build_query')
			&& ! empty($get = $this->input->get(null, true)))
		{
			$this->uri_string .= '?'.http_build_query($get);
		}

		return $this->uri_string;
	}
}
