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
 * Gamelang html Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// -----------------------------------------------------------------------------

if ( ! function_exists('meta_tag'))
{
	/**
	 * Output a <meta> tag of almost any type.
	 * @param   mixed   $name   the meta name or array of meta.
	 * @param   string  $content    the meta tag content.
	 * @param   string  $type       the type of meta tag.
	 * @param   mixed   $attrs      array of string of attributes.
	 * @return  string
	 */
	function meta_tag($name, $content = null, $type = 'meta', $attrs = array())
	{
		// Loop through multiple meta tags
		if (is_array($name))
		{
			$meta = array();
			foreach ($name as $key => $val)
			{
				$meta[] = meta_tag($key, $val, $type, $attrs);
			}

			return implode("\n\t", $meta);
		}

		// The tag by default is "meta"
		$tag = 'meta';

		$attributes = array();
		switch ($type)
		{
			case 'rel':
				$tag                = 'link';
				$attributes['rel']  = $name;
				$attributes['href'] = $content;
				break;

			case 'itemprop':
				$attributes['itemprop']  = $name;
				$attributes['href'] = $content;
				break;

			// In case of a meta tag.
			case 'meta':
			default:
				if ($name == 'charset')
				{
					return "<meta charset=\"{$content}\" />";
				}

				if ($name == 'base')
				{
					return "<base href=\"{$content}\" />";
				}

				// In case of using Open Graph tags,
				// we user 'property' instead of 'name'.

				$type OR $type = 'name';

				if ($content === null)
				{
					$attributes[$type] = $name;
				}
				else
				{
					$attributes[$type]     = html_escape($name);
					$attributes['content'] = html_escape($content);
				}

				break;
		}

		$attributes = (is_array($attrs)) ? _stringify_attributes(array_merge($attributes, $attrs)) : _stringify_attributes($attributes).' '.$attrs;

		return "<{$tag}{$attributes}/>";
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('info_box'))
{
	/**
	 * Generates an info box
	 *
	 * @param 	string 	$head
	 * @param 	string 	$text
	 * @param 	string 	$icon
	 * @param 	string 	$url
	 * @param 	string 	$color
	 * @return 	string
	 */
	function info_box($head = null, $text = null, $icon = null, $url = null, $color = 'primary')
	{
		$color && $color = 'bg-'.$color;

		// Opening tag.
		$output = "<div class=\"small-box {$color}\">";

		// Info box content.
		if ($head OR $text)
		{
			$output .= '<div class="inner">';
			$output .= '<h3>'.$head.'</h3>';
			$output .= '<p>'.$text.'</p>';
			$output .= '</div>';
		}

		// Add the icon.
		$icon && $output .= '<div class="icon position-absolute top-0 end-0">'.fa_icon($icon).'</div>';

		if ($url)
		{
			$output .= html_tag('a', array(
				'href'  => $url,
				'class' => 'small-box-footer',
			), __('lang_manage').'&nbsp;'.fa_icon('arrow-right-long'));
		}

		// Closing tag.
		$output .= '</div>';

		return $output;
	}
}

// ------------------------------------------------------------------------

/**
 * Create a XHTML tag
 *
 * @param	string			The tag name
 * @param	array|string	The tag attributes
 * @param	string|bool		The content to place in the tag, or false for no closing tag
 * @return	string
 */
if ( ! function_exists('html_tag'))
{
	function html_tag($tag, $attr = array(), $content = false)
	{
		if (empty($tag))
		{
			return $content;
		}

		// list of void elements (tags that can not have content)
		static $void_elements = array(
			// html4
			"area","base","br","col","hr","img","input","link","meta","param",
			// html5
			"command","embed","keygen","source","track","wbr",
			// html5.1
			"menuitem",
		);

		/**
		 * Add a custom tag so we can define language direction.
		 */
		if ('login' !== get_instance()->router->fetch_class()
			&& 'rtl' === get_instance()->lang->lang_detail('direction')
			&& ('input' === $tag OR ! in_array($tag, $void_elements)))
		{
			if (is_array($attr) && ! isset($attr['dir']))
			{
				$attr['dir'] = 'rtl';
			}
			elseif (is_string($attr) && false === stripos($attr, 'dir="'))
			{
				$attr .= ' dir="rtl"';
			}
		}

		// construct the HTML
		$html = '<'.$tag;
		$html .= ( ! empty($attr)) ? (is_array($attr) ? _stringify_attributes($attr) : ' '.$attr) : '';

		// a void element?
		if (in_array(strtolower($tag), $void_elements))
		{
			// these can not have content
			$html .= ' />';
		}
		else
		{
			// add the content and close the tag
			$html .= '>'.$content.'</'.$tag.'>';
		}

		return $html;
	}
}

// -----------------------------------------------------------------------------

if ( ! function_exists('label_condition'))
{
	/**
	 * This is a dummy function used to display Boostrap labels
	 * depending on a given condition.
	 *
	 * @param 	bool 	$cond 	The conditions result.
	 * @param 	string 	$true 	String to output if true.
	 * @param 	string 	$false 	String to output if false.
	 * @return 	string
	 */
	function label_condition($cond, $true = 'lang:lang_yes', $false = 'lang:lang_no')
	{
		// Prepare the empty label.
		$label = '<span class="badge bg-%s">%s</span>';

		// Should strings be translated?
		if (sscanf($true, 'lang:%s', $true_line) === 1)
		{
			$true = __($true_line);
		}

		if (sscanf($false, 'lang:%s', $false_line) === 1)
		{
			$false = __($false_line);
		}

		return ($cond === true)
			? sprintf($label, 'success', $true)
			: sprintf($label, 'danger', $false);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists( 'fa_icon' ) ) {
	/**
	 * Useful to generate a fontawesome icon.
	 * @param  string $icon the icon to generate.
	 * @return string       the full FA tag.
	 */
	function fa_icon( $icon = 'user' ) {
		return "<i class=\"fa fa-{$icon}\"></i>";
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('strip_all_tags'))
{
	/**
	 * strip_all_tags
	 *
	 * Properly strip all HTML tags including script and style.
	 *
	 * @param 	string 	$string 	The string containing HTML.
	 * @param 	bool 	$breaks 	Whether to remove left over line breaks.
	 * @return 	string
	 */
	function strip_all_tags($string, $breaks = false) {
		$string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
		$string = strip_tags($string);

		$breaks && $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
		return trim($string);
	}
}