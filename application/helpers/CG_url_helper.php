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
 * Gamelang uri Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// -----------------------------------------------------------------------------

if ( ! function_exists('anchor'))
{
	/**
	 * Anchor Link
	 *
	 * Creates an anchor based on the local URL.
	 *
	 * @param	string	the URL
	 * @param	string	the link title
	 * @param	mixed	any attributes
	 * @return	string
	 */
	function anchor($uri = '', $title = '', $attributes = '')
	{
		$title = (string) $title;

		$site_url = is_array($uri)
			? site_url($uri)
			: (preg_match('#^(\w+:)?//#i', $uri) ? $uri : site_url($uri));

		if ($title === '')
		{
			$title = $site_url;
		}
		elseif (1 === sscanf($title, 'lang:%s', $line))
		{
			$title = __($line);
		}

		if ($attributes !== '')
		{
			$attributes = _stringify_attributes($attributes);
		}

		return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('current_url'))
{
	/**
	 * Current URL
	 *
	 * Returns the full URL (including segments) of the page where this
	 * function is placed
	 *
	 * @param 	bool 	$query_string 	Whether to add QUERY STRING.
	 * @return	string
	 */
	function current_url($query_string = true)
	{
		$CI =& get_instance();
		$url = $CI->config->site_url($CI->uri->uri_string());

		if ($query_string && ! empty($_SERVER['QUERY_STRING']))
		{
			$url .= '?'.$_SERVER['QUERY_STRING'];
		}

		return $url;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('previous_url'))
{
	/**
	 * Returns the last page the user visited.
	 *
	 * @param 	string 	$default 	Default value if no lat page exists.
	 * @param 	bool 	$uri_only 	Whether to return only the URI.
	 * @return 	bool
	 */
	function previous_url($default = null, $uri_only = false)
	{
		$prev_url = $default;

		if (isset($_SERVER['HTTP_REFERER'])
			&& $_SERVER['HTTP_REFERER'] != current_url()
			// We make sure the previous URL from the same site.
			&& false !== preg_match('#^'.site_url().'#', $prev_url))
		{
			$prev_url = $_SERVER['HTTP_REFERER'];
		}

		if ($prev_url)
		{
			$prev_url = (true === $uri_only)
				? str_replace(site_url(), '', $prev_url)
				: site_url($prev_url);
		}

		return $prev_url;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('uri_string'))
{
	/**
	 * URL String
	 *
	 * Overrides CodeIgniter default function in order to optionally
	 * include GET parameters.
	 *
	 * @param 	bool 	$include_get 	Whether to include GET parameters.
	 * @return 	string
	 */
	function uri_string($include_get = false)
	{
		return get_instance()->uri->uri_string($include_get);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('nonce_url'))
{
	/**
	 * nonce_url
	 *
	 * Function for generating site URLs with appended security nonce.
	 *
	 * @param 	string 	$uri 		The URI used to generate the URL.
	 * @param 	mixed 	$action 	Action to attach to the URL.
	 * @return 	string
	 */
	function nonce_url($uri = '', $action = -1)
	{
		$uri    = str_replace('&amp;', '&', $uri);
		$url    = site_url($uri);
		$params = parse_url($url);

		// Prepare URL query string then add the nonce token.
		$query  = array();
		(isset($params['query'])) && parse_str($params['query'], $query);
		$query['_nonce'] = create_nonce($action);

		// Build the query then add it to params group.
		$query = http_build_query($query);
		$params['query'] = $query;

		// We build the final URL.
		$output = (isset($params['scheme'])) ? "{$params['scheme']}://": '';
		$output .= (isset($params['host'])) ? "{$params['host']}": '';
		$output .= (isset($params['port'])) ? ":{$params['port']}": '';
		$output .= (isset($params['path'])) ? "{$params['path']}": '';
		$output .= (isset($params['query'])) ? "?{$params['query']}": '';
		return htmlentities($output, ENT_QUOTES, 'UTF-8');
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('admin_url'))
{
	/**
	 * Admin URL
	 *
	 * Returns the full URL to admin sections of the site.
	 *
	 * @param 	string 	$uri
	 * @param 	string 	$protocol
	 * @return 	string
	 */
	function admin_url($uri = '', $protocol = null)
	{
		if ('' === $uri)
		{
			return site_url(CG_ADMIN, $protocol);
		}

		$CI =& get_instance();

		$exp = explode('/', $uri);

		if (count($exp) === 1)
		{
			list($package, $method, $controller) = array_pad($exp, 3, null);

			if ( ! empty($contexts = $CI->router->package_contexts(strtok($package, '?'))))
			{
				foreach ($contexts as $context => $status)
				{
					if ('admin' !== $context && true === $status)
					{
						$uri = $context.'/'.ltrim(str_replace($context, '', $uri), '/');
					}
					elseif('admin' == $context) {
						$exp = explode('/', $uri);
					}
					break;
				}
			}
		}

		return site_url(CG_ADMIN.'/'.$uri, $protocol);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('admin_anchor'))
{
	/**
	 * Admin Anchor
	 *
	 * Creates and anchor that links to an admin section.
	 *
	 * @param  string 	$uri 	the section to link to.
	 * @param  string 	$title 	the string to display.
	 * @param  string 	$attrs 	attribites to add to anchor.
	 * @return string
	 */
	function admin_anchor($uri = '', $title = '', $attrs = '')
	{
		if ('' === $uri)
		{
			return anchor(CG_ADMIN, $title, $attrs);
		}

		$CI =& get_instance();

		$exp = explode('/', $uri);

		if (count($exp) === 1)
		{
			list($package, $method, $controller) = array_pad($exp, 3, null);

			if ( ! empty($contexts = $CI->router->package_contexts(strtok($package, '?'))))
			{
				foreach ($contexts as $context => $status)
				{
					if ('admin' !== $context && true === $status)
					{
						$uri = $context.'/'.ltrim(str_replace($context, '', $uri), '/');
					}
					elseif('admin' == $context) {
						$exp = explode('/', $uri);
					}
					break;
				}
			}
		}

		return anchor(CG_ADMIN.'/'.$uri, $title, $attrs);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('nonce_admin_url'))
{
	/**
	 * nonce_admin_url
	 *
	 * Function for creating nonce URLs for the dashboard area.
	 *
	 * @param 	string 	$uri 	The URI used to generate the URL.
	 * @return 	string 	$action The action to attach to the URL.
	 */
	function nonce_admin_url($uri = '', $action = -1)
	{
		if ('' === $uri)
		{
			return nonce_url(CG_ADMIN, $action);
		}

		$CI =& get_instance();

		$exp = explode('/', $uri);

		if (count($exp) === 1)
		{
			list($package, $method, $controller) = array_pad($exp, 3, null);

			if ( ! empty($contexts = $CI->router->package_contexts(strtok($package, '?'))))
			{
				foreach ($contexts as $context => $status)
				{
					if ('admin' !== $context && true === $status)
					{
						$uri = $context.'/'.ltrim(str_replace($context, '', $uri), '/');
					}
					elseif('admin' == $context) {
						$exp = explode('/', $uri);
					}
					break;
				}
			}
		}

		return nonce_url(CG_ADMIN.'/'.$uri, $action);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('process_url'))
{
	/**
	 * Process URL
	 *
	 * Returns the full URL to process sections of the site.
	 *
	 * @param 	string 	$uri
	 * @param 	string 	$protocol
	 * @return 	string
	 */
	function process_url($uri = '', $protocol = null)
	{
		$uri = ($uri == '') ? 'process' : 'process/'.$uri;
		return site_url($uri, $protocol);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('ajax_url'))
{
	/**
	 * AJAX URL
	 *
	 * Returns the full URL to ajax sections of the site.
	 *
	 * @param 	string 	$uri
	 * @param 	string 	$protocol
	 * @return 	string
	 */
	function ajax_url($uri = '', $protocol = null)
	{
		$uri = ($uri == '') ? 'ajax' : 'ajax/'.$uri;
		return site_url($uri, $protocol);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('nonce_ajax_url'))
{
	/**
	 * nonce_ajax_anchor
	 *
	 * Function for creating nonce URLs for Ajax context.
	 *
	 * @param 	string 	$uri 	The URI used to generate the URL.
	 * @param 	mixed 	$action The action to attach to the URL.
	 * @return 	string
	 */
	function nonce_ajax_url($uri = '', $action = -1)
	{
		$uri = ($uri == '') ? 'ajax' : 'ajax/'.$uri;
		return nonce_url($uri, $action);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('check_nonce_url'))
{
	/**
	 * check_nonce_url
	 *
	 * Function for checking the selected URL noncety.
	 *
	 * @param 	string 	$action
	 * @param 	string 	$url
	 * @return 	bool
	 */
	function check_nonce_url($action = -1, $url = null)
	{
		// If no URL provided, we use the current one, then format it.
		(null === $url) && $url = current_url();
		$url = str_replace('&amp;', '&', $url);

		$args = parse_url($url, PHP_URL_QUERY);
		parse_str($args, $query);

		if ( ! isset($query['_nonce']))
		{
			return false;
		}

		return verify_nonce($query['_nonce'], $action);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('esc_url'))
{
	/**
	 * esc_url
	 *
	 * Removes certain characters from URL.
	 *
	 * @see 	http://uk1.php.net/manual/en/function.urlencode.php#97969
	 *
	 * @param 	string
	 * @return 	string
	 */
	function esc_url($url) {
		$search  = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$replace = array('!', '*', "'", '(', ')', ';', ':', '@', '&', '=', '+', '$', ',', '/', '?', '%', '#', '[', ']');
		return _deep_replace($search, $replace, $url);
	}
}