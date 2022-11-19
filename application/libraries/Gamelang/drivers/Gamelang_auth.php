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
 * Gamelang_auth Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class Gamelang_auth extends CI_Driver
{
	/**
	 * Holds the currently logged in user's ID.
	 * @var 	integer
	 */
	private $id = 0;

	/**
	 * Holds the currently logged in user's object.
	 * @var object
	 */
	private $user;

	/**
	 * Holds whether the current user is an admin or not.
	 * @var 	boolean
	 */
	private $admin;

	// ------------------------------------------------------------------------

	/**
	 * Get all autoloaded options from database and assign
	 * them to CodeIgniter config array.
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function initialize()
	{
		// Make sure to load users language file.
		$this->ci->load->helper('security');

		// Attempt to authenticate the current user.
		$this->_authenticate();

		log_message('info', 'Gamelang_auth Class Initialized');
	}

	// ------------------------------------------------------------------------

	/**
	 * Attempt to authenticate the current user.
	 * @access 	private
	 * @param 	none
	 * @return 	void
	 */
	private function _authenticate()
	{
		// Let's make sure the cookie is set first.
		$this->ci->load->helper('cookie');
		list($user_id, $token, $random) = _get_cookie();
		if (empty($user_id) OR empty($token))
		{
			return;
		}

		// Let's get the variable from database.
		$var = $this->_parent->variables->get_by(array(
			'guid'          => $user_id,
			'name'          => 'online_token',
			'BINARY(value)' => $token,
			'created_at >'  => time() - (DAY_IN_SECONDS * 2),
		));
		if ( ! $var)
		{
			return;
		}

		// Let's get the user from database.
		$user = $this->_parent->users->get($user_id);
		if ( ! $user)
		{
			return;
		}

		/**
		 * This is useful if the user is disabled, deleted or
		 * banned  while he/she is logged in, we log him/her out.
		 */
		if ($user->enabled < 1 OR $user->deleted > 0)
		{
			$this->logout($user_id);
			return;
		}

		// If the session is already set, nothing to do.
		if ($this->ci->session->user_id)
		{
			return;
		}

		// If the session is not set, we set it.
		_set_session($user->id, true, $token, $user->language);
	}

	// ------------------------------------------------------------------------

	/**
	 * Return the currently logged in user's object.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	object if found, else false.
	 */
	public function user()
	{
		/**
		 * Make this method remember the current user.
		 * @var 	object
		 */
		static $current_user;

		/**
		 * If the method does not remember the current user, we see if this
		 * class has already cached the object. In case it hasn't, we simply
		 * attempt to get the user from database.
		 */
		if (empty($current_user))
		{
			// Not already cached? Get from database and cache the object.
			if ( ! isset($this->user))
			{
				// Unset any previously cached data.
				$this->user = false;

				do {
					// Nothing stored in session? Nothing to do.
					if ( ! $this->ci->session->user_id
						OR ! $this->ci->session->token)
					{
						break;
					}

					/**
					 * If multiple sessions are not allowed, we compare
					 * stored tokens and make sure only a single user
					 * per session is allowed.
					 */
					if (false === $this->ci->config->item('allow_multi_session'))
					{
						// Get the variable from database.
						$var = $this->_parent->variables->get_by(array(
							'guid'  => $this->ci->session->user_id,
							'name'  => 'online_token',
							'value' => $this->ci->session->token,
						));
						if ( ! $var)
						{
							break;
						}
					}

					// Get the user from database.
					$user = $this->_parent->users->get($this->ci->session->user_id);
					if (false === $user)
					{
						break;
					}

					/**
					 * This is useful if the user is disabled, deleted or
					 * banned  while he/she is logged in, we log him/her out.
					 */
					if ($user->enabled < 1 OR $user->deleted > 0)
					{
						$this->logout($user->id);
						break;
					}

					/**
					 * Now that everything went well, we make sure to cache
					 * the current user as well as the ID.
					 */
					$this->user  = $user;
					$this->id    = $user->id;
					$this->admin = $user->admin;
					break;

				// We make sure required data are stored in session.
				} while (false);
			}

			$current_user = $this->user;
		}

		/**
		 * Filters the current user.
		 *
		 * This was added so we can filter the final result in order to add,
		 * modify or remove any unwanted before returning the current user.
		 *
		 */
		if (false !== $current_user &&  has_filter('get_current_user'))
		{
			$current_user =  apply_filters('get_current_user', $current_user);
		}

		return $current_user;
	}

	// ------------------------------------------------------------------------

	/**
	 * Whether the current user is logged in.
	 * @access 	public
	 * @param 	none
	 * @return 	bool
	 */
	public function online()
	{
		return (false !== $this->user());
	}

	// ------------------------------------------------------------------------

	/**
	 * is_admin
	 *
	 * Method for checking whether the current user is an administrator.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	bool 	true if the user is an admin, else false.
	 */
	public function is_admin()
	{
		static $is_admin;

		if (empty($is_admin))
		{
			if ( ! isset($this->admin)
			&& false !== $this->online()
			&& isset($this->user->admin))
			{
				$this->admin = $this->user->admin;
			}

			$is_admin = $this->admin;
		}

		return $is_admin;
	}

	// ------------------------------------------------------------------------

	/**
	 * user_id
	 *
	 * Method for returning the currently logged in user's ID
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	int 	Returns the current user's ID.
	 */
	public function user_id()
	{
		static $current_user_id;

		if (empty($current_user_id))
		{
			if ($this->id <= 0
				&& false !== $this->online()
				&& isset($this->user->id))
			{
				$this->id = $this->user->id;
			}

			$current_user_id = $this->id;
		}

		return $current_user_id;
	}

	// ------------------------------------------------------------------------
	// Authentication methods.
	// ------------------------------------------------------------------------

	/**
	 * Login method.
	 *
	 * @access 	public
	 * @param 	string 	$identity 	username or emaila address.
	 * @param 	string 	$password 	the password.
	 * @param 	bool 	$remember 	whether to remember the user.
	 * @return 	bool
	 */
	public function login($identity, $password, $remember = false)
	{
		if (empty($identity) OR empty($password))
		{
			$this->_parent->themes->set_alert(__('ERROR_FIELDS_REQUIRED'), 'error');
			return false;
		}

		// Fires before processing.
		do_action_ref_array('before_user_login', array(&$identity, &$password));

		// What type of login to use?
		$login_type = $this->ci->config->item('login_type');
		switch ($login_type)
		{
			// Get the user by username.
			case 'username':
				$user = $this->_parent->users
					->get_by('entities.username', $identity);
				if ( ! $user)
				{
					$this->_parent->themes->set_alert(__('lang_ERROR_LOGIN_CREDENTIALS'), 'error');
					return false;
				}
				break;

			// Get user by email address.
			case 'email':
				$user = $this->_parent->users
					->get_by('users.email', $identity);
				if ( ! $user)
				{
					$this->_parent->themes->set_alert(__('lang_ERROR_LOGIN_CREDENTIALS'), 'error');
					return false;
				}
				break;

			// Get user by username or email address.
			case 'both':
			default:
				$user = $this->_parent->users
					->get($identity);

				if ( ! $user)
				{
					$this->_parent->themes->set_alert(__('lang_ERROR_LOGIN_CREDENTIALS'), 'error');
					return false;
				}

				break;
		}

		// Check the password.
		$this->ci->load->library('encryption');
		if ( ! $this->ci->encryption->verify($password, $user->password))
		{
			$this->_parent->themes->set_alert(__('lang_ERROR_LOGIN_CREDENTIALS'), 'error');
			return false;
		}

		// Make sure the account is enabled.
		if ($user->enabled == 0)
		{
			$this->_parent->themes->set_alert(sprintf(
				__('lang_ERROR_ACCOUNT_INACTIVE'),
				anchor('register/resend', __('lang_CLICK_HERE'), ['class'=>'alert-link'])
			), 'error');
			return false;
		}

		// Make sure the account is not banned.
		if ($user->enabled < 0)
		{
			$this->_parent->themes->set_alert(__('lang_ERROR_ACCOUNT_BANNED'), 'error');
			return false;
		}

		// Make sure the account is not deleted.
		if ($user->deleted > 0)
		{
			$this->_parent->themes->set_alert(sprintf(
				__('lang_ERROR_ACCOUNT_DELETED'),
				anchor('login/restore', __('lang_CLICK_HERE'), ['class'=>'alert-link'])
			), 'error');

			return false;
		}

		// Setup the session.
		if (true === _set_session($user->id, $remember, null, $user->language))
		{
			// TODO: Log the activity.
			// NOTE: This is temporary.
			log_activity($user->id, 'Logged in.');

			return true;
		}

		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Quick login method without passing by all filters found in login().
	 *
	 * @access 	public
	 * @param 	object 	$user 	the user's object to login.
	 * @return 	bool
	 */
	public function quick_login($user, $language = null)
	{
		// ID, username or email provided?
		if ( ! $user instanceof CG_User OR ! is_object($user))
		{
			$user = $this->_parent->users->get($user);
		}

		// Make sure the user exists.
		if (false === $user)
		{
			return false;
		}

		$language OR $language = $user->language;

		if (false !== _set_session($user->id, true, null, $language))
		{
			// Change users language if needed.
			if ($language !== $user->language
				&& in_array($language, $this->ci->config->item('languages')))
			{
				$user->update('language', $language);
			}

			return true;
		}

		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Logout method.
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function logout()
	{
		// Catch the user's ID for later use.
		$user_id = 0;
		(true === $this->online()) && $user_id = $this->user()->id;

		// Fires before logging out the user.
		do_action('before_user_logout', $user_id);

		// Delete the cookie.
		$this->ci->input->set_cookie('c_user', '', '');

		// Delete online tokens.
		$this->delete_online_tokens($user_id);

		// Put the user offline.
		$this->_parent->users->update($user_id, array('online' => 0));

		// Destroy the session.
		if (PHP_SESSION_NONE !== session_status())
		{
			$this->ci->session->sess_destroy();
		}

		// Fires After user is logged out, cookie deleted and session destroyed.
		do_action('after_user_logout', $user_id);
	}

	// ------------------------------------------------------------------------
	// General Cleaners.
	// ------------------------------------------------------------------------

	/**
	 * Delete user's online token and perform a clean up of older tokens.
	 * @access 	public
	 * @param 	int 	$user_id
	 * @return 	void
	 */
	public function delete_online_tokens($user_id = 0)
	{
		if (is_numeric($user_id) && $user_id > 0)
		{
			$this->_parent->variables->delete_by(array(
				'guid' => $user_id,
				'name' => 'online_token',
			));
		}

		/**
		 * Filters online token line so plugin can change it.
		 */
		$expired =  apply_filters('user_cookie_life', MONTH_IN_SECONDS * 2);
		(is_int($expired) && $expired <= 0) OR $expired = MONTH_IN_SECONDS * 2;

		// Perform a clean up of older tokens.
		$this->_parent->variables->delete_by(array(
			'name'         => 'online_token',
			'created_at <' => time() - $expired,
		));
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete user's password code and perform a clean up of older ones.
	 * @access 	public
	 * @param 	int 	$user_id
	 * @return 	void
	 */
	public function delete_password_codes($user_id = 0)
	{
		if (is_numeric($user_id) && $user_id > 0)
		{
			$this->_parent->variables->delete_by(array(
				'guid' => $user_id,
				'name' => 'password_code',
			));
		}

		// Perform a clean up of older tokens.
		$this->_parent->variables->delete_by(array(
			'name' => 'password_code',
			'created_at <' => time() - (DAY_IN_SECONDS * 2)
		));
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete account's activation code and clean up old ones.
	 * @access 	public
	 * @param 	int 	$user_id
	 * @return 	void
	 */
	public function delete_activation_codes($user_id)
	{
		if (is_numeric($user_id) && $user_id > 0)
		{
			$this->_parent->variables->delete_by(array(
				'guid' => $user_id,
				'name' => 'activation_code',
			));
		}

		// Perfrom a clean up of older activation codes.
		$this->_parent->variables->delete_by(array(
			'name'         => 'activation_code',
			'created_at <' => time() - (DAY_IN_SECONDS * 2)
		));
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete old captcha from database.
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function delete_captcha()
	{
		// Delete captcha of the current ip address.
		$this->_parent->variables->delete_by(array(
			'name'   => 'captcha',
			'params' => $this->ci->input->ip_address(),
		));

		// Delete old captcha.
		$this->_parent->variables->delete_by(array(
			'name'         => 'captcha',
			'created_at <' => time() - 7200
		));
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('user_anchor'))
{
	/**
	 * user_anchor
	 *
	 * Function fro generating an HTML anchor for the user's profile page.
	 *
	 * @param 	mixed 	$id
	 * @param 	string 	$title
	 * @param 	mixed 	$attrs
	 * @return 	string
	 */
	function user_anchor($id = 0, $title = '', $attrs = array())
	{
		$user = ($id instanceof CG_User) ? $id : get_user($id);

		if (false === $user)
		{
			return null;
		}

		// No title provided? Use full name.
		if ('' === $title)
		{
			$title = isset($user->full_name) ? $user->full_name  : $user->username;
		}
		// Display the avatar?
		elseif (0 === strpos($title, 'user.avatar') && isset($user->avatar))
		{
			$title = (1 === sscanf($title, 'user.avatar.%d', $size))
				? user_avatar($size, $user->avatar)
				: user_avatar(50, $user->avatar);
		}
		// Any other key?
		elseif (1 === sscanf($title, 'user.%s', $key) && isset($user->{$key}))
		{
			$title = $user->{$key};
		}
		// Translatable string?
		elseif (1 === sscanf($title, 'lang:%s', $line))
		{
			$title = line($line);
		}

		// Add required attributes first.
		$attributes = array(
			'href' => site_url($user->username),
			'data-userid' => $user->id,
		);

		// Merge all attributes.
		if (is_array($attrs))
		{
			$attributes = array_merge($attributes, $attrs);
		}
		else
		{
			$attributes = $attrs._stringify_attributes($attributes);
		}

		// Render the final anchor tag.
		return html_tag('a', $attributes, $title);
	}
}