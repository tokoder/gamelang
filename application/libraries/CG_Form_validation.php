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
 * CG_Form_validation Class
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class CG_Form_validation extends CI_Form_validation
{
	/**
	 * Class constructor.
	 * @return 	void
	 */
	public function __construct($rules = array())
	{
		$this->ci =& get_instance();

		/**
		 * Here we merge super-global $_FILES to $_POST to allow for
		 * better file validation or Form_validation library.
		 * @see 	https://goo.gl/NpsmMJ (Bonfire)
		 */
		( ! empty($_FILES) && is_array($_FILES)) && $_POST = array_merge($_POST, $_FILES);

		log_message('info', 'CG_Form_validation Class Initialized');

		// Call parent's constructor.
		parent::__construct($rules);
	}

	// ------------------------------------------------------------------------

	/**
	 * Run the form validation.
	 * @access 	public
	 * @param 	string 	$package
	 * @param 	string 	$group
	 * @return 	boolean
	 */
	public function run($package = '', $group = '')
	{
		(is_object($package)) && $this->ci =& $package;
		return parent::run($group);
	}

	// ------------------------------------------------------------------------

	/**
	 * Return form validation errors in custom HTML list.
	 * Default: unordered list.
	 * @access 	public
	 * @return 	string 	if found, else empty string.
	 */
	public function validation_errors_list()
	{
		$errors = parent::error_string('<li>', '</li>');
		return (empty($errors)) ? '' : '<ul class="m-0">'.PHP_EOL.$errors.'</ul>';
	}

	// ------------------------------------------------------------------------

	/**
	 * Allow alpha-numeric characters with periods, underscores,
	 * spaces and dashes.
	 * @access 	public
	 * @param 	string 	$str 	The string to check.
	 * @return 	boolean
	 */
	public function alpha_extra($str)
	{
		return (preg_match("/^([\.\s-a-z0-9_-])+$/i", $str));
	}

	// ------------------------------------------------------------------------

	/**
	 * Make sure the entered username is unique.
	 *
	 * @access 	public
	 * @param 	string 	$str 	the usernme to check.
	 * @return 	boolean
	 */
	public function unique_username($str)
	{
		$from_file = in_array($str, $this->_forbidden_usernames());
		$from_db = parent::is_unique($str, 'entities.username');
		return (! $from_file && $from_db);
	}

	// ------------------------------------------------------------------------

	/**
	 * Make sure the selected email address is unique.
	 * @access 	public
	 * @param 	string 	$str 	the email address to check.
	 * @return 	boolean
	 */
	public function unique_email($str)
	{
		return (parent::is_unique($str, 'users.email')
			&& parent::is_unique($str, 'metadata.value')
			&& parent::is_unique($str, 'variables.params'));
	}

	// ------------------------------------------------------------------------

	/**
	 * Make sure the user exists using ID, username or email address.
	 *
	 * @access 	public
	 * @param 	string 	$str
	 * @return 	boolean
	 */
	public function user_exists($str)
	{
		return (false !== $this->ci->users->get($str));
	}

	// ------------------------------------------------------------------------

	/**
	 * user_admin
	 *
	 * Method for making sure the user trying to login is an admin.
	 *
	 * @access 	public
	 * @param 	mixed 	User username or email address.
	 * @return 	bool
	 */
	public function user_admin($str)
	{
		if (false !== ($user = $this->ci->users->get($str)))
		{
			return ('administrator' === $user->subtype);
		}

		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Check user's credentials on login page.
	 * @access 	public
	 * @param 	string 	$password
	 * @param 	string 	$login 	The login field (username or email)
	 * @return 	boolean
	 */
	public function check_credentials($password, $login)
	{
		$user = $this->ci->users->get($this->ci->input->post($login, true));
		return ($user && password_verify($password, $user->password));
	}

	// ------------------------------------------------------------------------

	/**
	 * Checks user's current password.
	 * @access 	public
	 * @param 	string 	$str 	The current password.
	 * @return 	boolean
	 */
	public function current_password($str)
	{
		/**
		 * 1. The user is logged in.
		 * 2. The password is correct.
		 */
		return ($this->ci->auth->online()
			&& (password_verify($str, $this->ci->auth->user()->password)));
	}

	// ------------------------------------------------------------------------

	/**
	 * Edit original min_length to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	boolean
	 */
	public function min_length($str, $val)
	{
		$val = (is_numeric($val)) ? $val : $this->ci->config->item($val);
		return parent::min_length($str, $val);
	}

	// ------------------------------------------------------------------------

	/**
	 * Edit original max_length to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	boolean
	 */
	public function max_length($str, $val)
	{
		$val = (is_numeric($val)) ? $val : $this->ci->config->item($val);
		return parent::max_length($str, $val);
	}

	// ------------------------------------------------------------------------

	/**
	 * Edit original exact_length to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	boolean
	 */
	public function exact_length($str, $val)
	{
		$val = (is_numeric($val)) ? $val : $this->ci->config->item($val);
		return parent::exact_length($str, $val);
	}

	// ------------------------------------------------------------------------

	/**
	 * Edit original greater_than to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	boolean
	 */
	public function greater_than($str, $min)
	{
		$min = (is_numeric($min)) ? $min : $this->ci->config->item($min);
		return parent::greater_than($str, $min);
	}

	// ------------------------------------------------------------------------

	/**
	 * Edit original greater_than_equal_to to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	boolean
	 */
	public function greater_than_equal_to($str, $min)
	{
		$min = (is_numeric($min)) ? $min : $this->ci->config->item($min);
		return parent::greater_than_equal_to($str, $min);
	}

	// ------------------------------------------------------------------------

	/**
	 * Edit original less_than to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	boolean
	 */
	public function less_than($str, $max)
	{
		$max = (is_numeric($max)) ? $max : $this->ci->config->item($max);
		return parent::less_than($str, $max);
	}

	// ------------------------------------------------------------------------

	/**
	 * Edit original less_than_equal_to to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	boolean
	 */
	public function less_than_equal_to($str, $max)
	{
		$max = (is_numeric($max)) ? $max : $this->ci->config->item($max);
		return parent::less_than_equal_to($str, $max);
	}

	// ------------------------------------------------------------------------

	/**
	 * Makes sure the input is not in the given array.
	 * @access 	public
	 * @param 	string 	$value 	The value to check.
	 * @param 	string 	$list 	The list used to check.
	 * @return 	bool 	true if not found in the list, else false.
	 */
	public function not_in_list($value, $list)
	{
		return (true !== in_array($value, explode(',', $list), TRUE));
	}

	// ------------------------------------------------------------------------


	/**
	 * Build an error message using the field and param with the possibility
	 * to have $param stored in config.
	 * @param	string	The error message line
	 * @param	string	A field's human name
	 * @param	mixed	A rule's optional parameter
	 * @return	string
	 */
	protected function _build_error_msg($line, $field = '', $param = '')
	{
		// Look for $param in config.
		(is_string($param) && $nparam = config_item($param)) && $param = $nparam;

		// Let the parent do the rest.
		return parent::_build_error_msg($line, $field, $param);
	}

	// -----------------------------------------------------------------------------

	/**
	 * This function returns an array of all possible forbidden usernames.
	 * @return 	array
	 */
	function _forbidden_usernames()
	{
        global $CFG;
		static $usernames;

		if (empty($usernames))
		{
			$usernames = $CFG->load('usernames', true, true)
				? $CFG->config['usernames']
				: [];
		}

		return $usernames;
	}

	// ----------------------------------------------------------------------------

	/**
	 * Check captcha.
	 *
	 * @access 	public
	 * @param 	string 	$str 	captcha word
	 * @return 	bool
	 */
	public function check_captcha($str)
	{
		// Using Google reCAPTCHA?
		if (get_option('use_recaptcha', false) === true && ! empty(get_option('recaptcha_site_key', null)))
		{
			// Catch reCAPTCHA field.
			$recaptcha = $this->input->post('g-recaptcha-response');

			// Not set? Set the error message and return false.
			if (empty($recaptcha))
			{
				return false;
			}

			$data = array(
				'secret'   => get_option('recaptcha_private_key'),
				'remoteip' => $this->input->ip_address(),
				'response' => $recaptcha,
			);

			// cURL is enabled?
			if (function_exists('curl_init'))
			{
				$verify = curl_init();
				curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
				curl_setopt($verify, CURLOPT_POST, true);
				curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
				curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($verify);
			}
			else
			{
				// Prepare the verification URL.
				$verify_url = 'https://www.google.com/recaptcha/api/siteverify?'.http_build_query($data);

				// Catch response and decode it.
				$response = file_get_contents($verify_url);
			}

			// Decode the response.
			$response = json_decode($response, true);

			// Valid captcha?
			if (isset($response['success']) && $response['success'] === true)
			{
				return true;
			}
			// Invalid captcha?
			else
			{
				return false;
			}
		}

		// No captcha set?
		if (empty($str))
		{
			return false;
		}

		// Check if the captcha exists or not.
		$var = $this->ci->variables->get_by(array(
			'name'          => 'captcha',
			'BINARY(value)' => $str,
			'params'        => $this->ci->input->ip_address(),
		));

		// Not found? Generate the error.
		if ( ! $var)
		{
			return false;
		}

		// Found?
		return true;
	}
}