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
 * Gamelang form Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// -----------------------------------------------------------------------------

if ( ! function_exists('print_input'))
{
	/**
	 * Prints a form input with possibility to add extra
	 * attributes instead of using array_merge on views.
	 * @param 	array 	$input 	form input details.
	 * @param 	array 	$attrs 	additional attributes.
	 * @return 	string 	the full form input string.
	 */
	function print_input($input = array(), array $attrs = array())
	{
		// If $input is empty, nothing to do.
		if ( ! is_array($input) OR empty($input))
		{
			return '';
		}

		// Merge all attributes if there any.
		if ( ! empty($attrs))
		{
			foreach ($attrs as $key => $val)
			{
				if (is_int($key))
				{
					$input[$val] = $val;
					continue;
				}

				/**
				 * We make sure to concatenate CSS classes.
				 */
				if ('class' === $key && isset($input['class']))
				{
					$input['class'] = trim($input['class'].' '.$val);
					continue;
				}

				$input[$key] = $val;
			}
		}

		if ( ! isset($input['value']))
		{
			$input['value'] = '';
		}

		// Array of attributes not to transfigure.
		$_ignored = array(
			'autocomplete', 'autofocus',
			'disabled',
			'form', 'formaction', 'formenctype', 'formmethod', 'formtarget',
			'list',
			'multiple',
			'readonly', 'rel', 'required',
			'step',
		);
		array_walk($input, function(&$val, $key) use ($_ignored) {
			(in_array($key, $_ignored)) OR $val = _transfigure($val);
		});

		/**
		 * Here we loop through all input elements only if it's found,
		 * otherwise, it will simply fall back to "form_input".
		 */
		if (isset($input['type']))
		{
			switch ($input['type'])
			{
				// In case of a textarea.
				case 'textarea':
					unset($input['type']);
					return form_textarea($input, $input['value']);
					break;

				// In case of a dropdwn/select.
				case 'select':
				case 'dropdown':
					$name = $input['name'];
					$options = array_map('_transfigure', $input['options']);
					$selected = array();
					unset($input['name'], $input['options']);
					if (isset($input['selected']))
					{
						$selected = $input['selected'];
						unset($input['selected']);
					}
					return form_dropdown($name, $options, $selected, $input);
					break;

				// Default one.
				default:
					return form_input($input, $input['value']);
					break;
			}
		}

		// Fall-back to form input.
		return form_input($input, $input['value']);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('_transfigure'))
{
	/**
	 * _transfigure
	 *
	 * It checks if the string contains the
	 * "config:" keywords first, then the
	 * "lang:" if the first one was not found.
	 *
	 * @param 	string 	$string 	The string to run test on.
	 * @return 	string
	 */
	function _transfigure($string)
	{
		if (is_string($string) && sscanf($string, 'config:%s', $config) === 1)
		{
			$string = get_option($config, $string);
		}
		elseif (is_string($string) && sscanf($string, 'lang:%s', $lang) === 1)
		{
			$string = __($lang);
		}

		return $string;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('form_label'))
{
	/**
	 * Form Label Tag
	 *
	 * @param	string	The text to appear onscreen
	 * @param	string	The id the label applies to
	 * @param	mixed	Additional attributes
	 * @return	string
	 */
	function form_label($label_text = '', $id = '', $attributes = array())
	{

		$label = '<label';

		if ($id !== '')
		{
			$label .= ' for="'.$id.'"';
		}

		$label .= _attributes_to_string($attributes);

		return $label.'>'._transfigure($label_text).'</label>';
	}
}

// -----------------------------------------------------------------------------

if ( ! function_exists('form_nonce'))
{
	/**
	 * form_nonce
	 *
	 * Function for creating hidden nonce fields for form.
	 *
	 * The once field is used to make sure that the contents of the form came
	 * from the location on the current site and not from somewhere else. This
	 * is not an absolute protection option, but bu should protect against most
	 * cases. Make sure to always use it for forms you want to protect.
	 *
	 * Both $action and $name are optional, but it is highly recommended that
	 * you provide them. Anyone who inspects your code (PHP) would simply guess
	 * what should be used to cause damage. So please, provide them.
	 *
	 * Make sure to always check again your fields values after submission before
	 * your process.
	 *
	 * @param 	string 	$action 	The action used to generate nonce.
	 * @param 	string 	$name 		The name of the nonce field.
	 * @param 	bool 	$referrer 	Whether to add the referrer field.
	 * @return 	string
	 */
	function form_nonce($action = -1, $name = '_nonce', $referrer = true)
	{
		empty($name) && $name = '_nonce';

		$output = '<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.html_escape(create_nonce($action)).'" />';

		(true === $referrer) && $output .= form_referrer();

		return $output;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('form_referrer'))
{
	/**
	 * form_referrer
	 *
	 * Function for creating HTTP referrer hidden field for forms.
	 *
	 * @param 	string 	$name 	Optional field name.
	 * @return 	string
	 */
	function form_referrer($name = '_http_referrer')
	{
		empty($name) && $name = '_http_referrer';
		$uri = strtok($_SERVER['REQUEST_URI'], '?');
		return form_hidden($name, $uri);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('has_error'))
{
	/**
	 * has_error
	 *
	 * Function for checking whether the selected field has any errors.
	 *
	 * @access 	public
	 * @param 	string 	$field 	The field's name to check.
	 * @return 	bool 	true if there are error, else false.
	 */
	function has_error($field = NULL)
	{
		return ( ! empty(form_error($field)));
	}
}