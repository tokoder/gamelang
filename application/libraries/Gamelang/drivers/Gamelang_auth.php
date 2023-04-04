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
	 * @var 	object
	 */
	private $user;

	/**
	 * Holds whether the current user is an admin or not.
	 * @var 	boolean
	 */
	private $admin;

	/**
	 * Holds the package.
	 * @var 	string
	 */
	protected $package;

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
		$this->ci->load->helper('cookie');
		$this->package = '';

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
		list($user_id, $token, $random) = user_get_cookie();
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
		user_set_session($user->id, true, $token, $user->language);
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
	// Authorization methods.
	// ------------------------------------------------------------------------

	//check user permission
	public function checkUserPermission($permission = NULL, $user = null)
	{
        if ( ! $this->online()) {
			return false;
        }

		if ($this->is_admin()) {
			return true;
		}

        if ($user == null) {
			$user = $this->user();
        }

		$userType = $user->subtype;
		$userRole = $this->_parent->groups->get($userType);
		$rolePermission = $userRole ? $userRole->permissions : [];

		$permission = ($permission != null)
			? strtolower($permission)
			: $this->ci->router->fetch_package();

		if(in_array($permission, force_array($rolePermission))) {
			return apply_filters_ref_array('role_permission', [$permission]);
		}

		return false;
	}

	public function checkPermission($permission = NULL)
	{
        if ( ! $this->online()) {
			set_alert(__('You must be logged in to access this page'), 'error');
			redirect('login?next='.rawurlencode(uri_string()),'refresh');
			exit;
        }

		if ( ! checkUserPermission($permission)) {
			set_alert(__('You do not have permission to access '.$permission.'. Please contact with administrator'), 'error');
            redirect('');
            exit();
		}
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
			set_alert(__('All fields are required.'), 'error');
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
					set_alert(__('Invalid username/email address and/or password'), 'error');
					return false;
				}
				break;

			// Get user by email address.
			case 'email':
				$user = $this->_parent->users
					->get_by('users.email', $identity);
				if ( ! $user)
				{
					set_alert(__('Invalid username/email address and/or password'), 'error');
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
					set_alert(__('Invalid username/email address and/or password'), 'error');
					return false;
				}

				break;
		}

		// Check the password.
		$this->ci->load->library('encryption');
		if ( ! $this->ci->encryption->verify($password, $user->password))
		{
			set_alert(__('Invalid username/email address and/or password'), 'error');
			return false;
		}

		// Make sure the account is enabled.
		if ($user->enabled == 0)
		{
			set_alert(sprintf(
				__('You account is not yet active. Use the link that was sent to you or %s to receive a new one'),
				anchor('register/resend', __('Click Here'), ['class'=>'alert-link'])
			), 'error');
			return false;
		}

		// Make sure the account is not banned.
		if ($user->enabled < 0)
		{
			set_alert(__('This user is banned from the site'), 'error');
			return false;
		}

		// Make sure the account is not deleted.
		if ($user->deleted > 0)
		{
			set_alert(sprintf(
				__('Your account has been deleted but not yet removed from database. %s if you wish to restore it'),
				anchor('login/restore', __('Click Here'), ['class'=>'alert-link'])
			), 'error');

			return false;
		}

		// Setup the session.
		if (true === user_set_session($user->id, $remember, null, $user->language))
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

		if (false !== user_set_session($user->id, true, null, $language))
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
		set_cookie('c_user', '', '');

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
		$expired = apply_filters('user_cookie_life', HOUR_IN_SECONDS);
		(is_int($expired) && $expired <= 0) OR $expired = HOUR_IN_SECONDS;

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

if ( ! function_exists('checkUserPermission'))
{
	/**
	 * checkUserPermission
	 * @return 	string
	 */
	function checkUserPermission($permission)
	{
		return get_instance()->auth->checkUserPermission($permission);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('checkPermission'))
{
	/**
	 * checkPermission
	 * @return 	string
	 */
	function checkPermission($permission)
	{
		return get_instance()->auth->checkPermission($permission);
	}
}

// -----------------------------------------------------------------------------

if ( ! function_exists('user_online'))
{
	function user_online($user = null)
	{
		// $timestamp OR $timestamp = time();
		$timestamp = $user->last_seen;
		$current_time = time();
		$time_difference = $current_time - $timestamp;
		$seconds = $time_difference;
		$minutes = round($seconds / 60);

		// Perform a clean up of older tokens.
		$result = ($minutes <= 3) ? true : false;
		if ( ! $result) {
			update_user($user->id, array('online' => 0));
		}

		return $result;
	}
}

// -----------------------------------------------------------------------------

if ( ! function_exists('user_set_session'))
{
	/**
	 * Setup session data at login and autologin.
	 *
	 * @access
	 * @param 	int 	$user_id 	the user's ID.
	 * @param 	bool 	$remember 	whether to remember the user.
	 * @param 	string 	$token 		the user's online token.
	 * @param 	string 	$language 	the user's language.
	 * @return 	bool
	 */
	function user_set_session($user_id, $remember = false, $token = null, $language = null)
	{
		// Make sure all neded data are present.
		if (empty($user_id))
		{
			return false;
		}

		// If no $token is provided, we generate a new one.
		if (empty($token))
		{
			get_instance()->load->library('encryption');
			$token = get_instance()->encryption->hash($user_id.session_id().rand());
		}

		// Fires before logging in the user.
		do_action('after_user_login', $user_id);

		// Prepare session data.
		$sess_data = array(
			'user_id'  => $user_id,
			'token'    => $token,
		);

		// Add user language only if available.
		if ($language && in_array($language, (array) get_instance()->config->item('languages')))
		{
			$sess_data['language'] = $language;
		}

		// Now we set session data.
		get_instance()->session->set_userdata($sess_data);

		// Now we create/update the variable.
		get_instance()->variables->set_var($user_id, 'online_token', $token, get_instance()->input->ip_address);

		// Put the user online.
		get_instance()->users->update($user_id, array(
			'online' => 1,
			'last_seen' => time()
		));

		// The return depends on $remember.
		return (true === $remember) ? user_set_cookie($user_id, $token) : true;
	}
}