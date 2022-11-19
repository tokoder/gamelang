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
 * CG_Input Class
 *
 * Pre-processes global input data for security
 *
 * @category 	Core
 * @author		Tokoder Team
 */
class CG_Input extends CI_Input
{
	/**
	 * request
	 *
	 * Method for fetching an item from the REQUEST array
	 *
	 * @access 	public
	 * @param 	string 	$index 		Index of the item to be fetched from $_REQUEST.
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	mixed
	 */
	public function request($index = null, $xss_clean = null)
	{
		return $this->_fetch_from_array($_REQUEST, $index, $xss_clean);
	}

	// ------------------------------------------------------------------------

	/**
	 * protocol
	 *
	 * Method for returning the protocol that the request was make with.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	public function protocol()
	{
		if ($this->server('HTTPS') == 'on' OR
			$this->server('HTTPS') == 1 OR
			$this->server('SERVER_PORT') == 443)
		{
			return 'https';
		}

		return 'http';
	}

	// ------------------------------------------------------------------------

	/**
	 * referrer
	 *
	 * Method for returning the REFERRER.
	 *
	 * @access 	public
	 * @param 	string 	$default 	What to return if no referrer is found.
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering
	 * @return 	string
	 */
	public function referrer($default = '', $xss_clean = NULL)
	{
		$referrer = $this->server('HTTP_REFERER', $xss_clean);
		return ($referrer) ? $referrer : $default;
	}

	// ------------------------------------------------------------------------

	/**
	 * query_string
	 *
	 * Methods for returning the QUERY_STRING from $_SERVER array.
	 *
	 * @access 	public
	 * @param 	string 	$default 	What to return if nothing found.
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering
	 * @return 	string
	 */
	public function query_string($default = '', $xss_clean = null)
	{
		$query_string = $this->server('QUERY_STRING', $xss_clean);
		return ($query_string) ? $query_string : $default;
	}

	// ------------------------------------------------------------------------

	/**
	 * is_post_request
	 *
	 * Method for making sure the request is a POST request.
	 *
	 * @access 	public
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	bool 	true if it is a POST request, else false.
	 */
	public function is_post_request($xss_clean = NULL)
	{
		return ($this->server('REQUEST_METHOD', $xss_clean) === 'POST');
	}

	// ------------------------------------------------------------------------

	/**
	 * is_get_request
	 *
	 * Method for making sure the request is a GET request.
	 *
	 * @access 	public
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	bool 	true if it is a GET request, else false.
	 */
	public function is_get_request($xss_clean = NULL)
	{
		return ($this->server('REQUEST_METHOD', $xss_clean) === 'GET');
	}

	// ------------------------------------------------------------------------

	/**
	 * is_head_request
	 *
	 * Method for making sure the request is a HEAD request.
	 *
	 * @access 	public
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	bool 	true if it is a HEAD request, else false.
	 */
	public function is_head_request($xss_clean = NULL)
	{
		return ($this->server('REQUEST_METHOD', $xss_clean) === 'HEAD');
	}

	// ------------------------------------------------------------------------

	/**
	 * is_pust_request
	 *
	 * Method for making sure the request is a PUT request.
	 *
	 * @access 	public
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	bool 	true if it is a PUT request, else false.
	 */
	public function is_put_request($xss_clean = NULL)
	{
		return ($this->server('REQUEST_METHOD', $xss_clean) === 'PUT');
	}

}