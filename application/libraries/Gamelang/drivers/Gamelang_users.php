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
 * Gamelang_users Class
 *
 * Menangani semua operasi yang dilakukan pada akun pengguna.
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class Gamelang_users extends CI_Driver implements Gamelang_crud_interface
{
	/**
	 * Holds users table fields.
	 * @var array
	 */
	private $fields;

	/**
	 * Initialize class preferences.
	 */
	public function initialize()
	{
		// Make sure to load encryption library.
		if ( ! class_exists('CI_Encryption', false))
		{
			$this->ci->load->library('encryption');
		}

		log_message('info', 'Gamelang_users Class Initialized');
	}

    // ------------------------------------------------------------------------

    /**
     * Generates the SELECT portion of the query
     */
    public function select($select = '*', $escape = null)
    {
		$this->ci->db->select($select, $escape);
		return $this;
    }

	// ------------------------------------------------------------------------

	/**
	 * Return an array of users table fields.
	 * @return 	array
	 */
	public function fields()
	{
		if (isset($this->fields))
		{
			return $this->fields;
		}

		$this->fields = $this->ci->db->list_fields('users');
		return $this->fields;
	}

	// ------------------------------------------------------------------------
	// Create.
	// ------------------------------------------------------------------------

	/**
	 * Create a new user.
	 * @param 	array 	$data
	 * @return 	int 	the user's ID if created, else false.
	 */
	public function create(array $data = array())
    {
		// Nothing provided? Nothing to do.
		if (empty($data))
		{
			return false;
		}

		// Multiple users?
		if (isset($data[0]) && is_array($data[0]))
		{
			$ids = array();
			foreach ($data as $_data)
			{
				$ids[] = $this->create($_data);
			}
			return $ids;
		}

		// Split data.
		list($entity, $user, $meta) = $this->_split_data($data);

		// Make sure to alwayas add the entity's type.
		$entity['type'] = 'user';

		// Izinkan peran pengguna default.
		if ( ! isset($entity['subtype']))
		{
			$role = apply_filters('default_users_role', 'regular');
			empty($role) && $role = 'regular';
			$entity['subtype'] = $role;
		}

		// Make sure user's subtype is always set.
		(isset($entity['subtype'])) OR $entity['subtype'] = 'regular';

		// The user should be enabled or not?
		(isset($entity['enabled'])) OR $entity['enabled'] = 0;

		// Add the language if it's not set.
		if ( ! isset($data['language']))
		{
			$data['language'] = ($this->ci->session->language)
				? $this->ci->session->language
				: $this->ci->lang->lang_detail('folder');
		}

		// Let's insert the entity first and make sure it's created.
		$guid = $this->_parent->entities->create($entity);
		if ( ! $guid)
		{
			return false;
		}

		// Add the id to user.
		$user['guid'] = $guid;

		// Hash the password if present.
		if (isset($user['password']) && ! empty($user['password']))
		{
			$user['password'] = $this->ci->encryption->hash($user['password']);
		}

		// Make sure the the gender is valid.
		if (isset($user['gender'])
			&& ! in_array($user['gender'], array('unspecified', 'male', 'female')))
		{
			$user['gender'] = 'unspecified';
		}

		// Insert the user.
		$this->ci->db->insert('users', $user);

		// Some metadata?
		if ( ! empty($meta))
		{
			$this->_parent->metadata->add_meta($guid, $meta);
		}

		return $guid;

    }

	// ------------------------------------------------------------------------
	// Getters.
	// ------------------------------------------------------------------------

	/**
	 * Retrieve a single user by ID, username OR email address.
	 * @param 	mixed 	$id 	The primary key value.
	 * @return 	object 	if found, else null
	 */
	public function get($id)
    {
		// Getting by ID?
		if (is_numeric($id))
		{
			return $this->get_by('id', $id);
		}

		// Retrieving by email address?
		if (false !== filter_var($id, FILTER_VALIDATE_EMAIL))
		{
			return $this->get_by('email', $id);
		}

		// Retrieve by username.
		if (is_string($id))
		{
			return $this->get_by('username', $id);
		}

		// Fall-back to get_by method.
		return $this->get_by($id);
    }

	// ------------------------------------------------------------------------

	/**
	 * Retrieve a single user by arbitrary WHERE clause.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @return 	object 	if found, else null.
	 */
	public function get_by($field, $match = null)
    {
		// We start with an empty user.
		$user = false;

		// We make sure to join "users" table first.
		$this->ci->db
			->where('entities.type', 'user')
			->join('users', 'users.guid = entities.id');

		// Attempt to get the user from database.
		$db_user = $this->_parent
			->where($field, $match, 1, 0)
			->order_by('entities.id', 'DESC')
			->get('entities')
			->row();

		// If found, we create its object.
		if ($db_user)
		{
			$user = new CG_User($db_user);
		}

		// Return the final result.
		return $user;
    }

	// ------------------------------------------------------------------------

	/**
	 * Retrieve multiple users by arbitrary WHERE clause.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @param 	int 	$limit 	Limit to use for getting records.
	 * @param 	int 	$offset Database offset.
	 * @return 	array o objects if found, else null.
	 */
	public function get_many($field = null, $match = null, $limit = 0, $offset = 0)
    {
		// We start with empty users.
		$users = false;

		// We make sure to select users and join their table.
		$this->ci->db
			->where('entities.type', 'user')
			->join('users', 'users.guid = entities.id');

		// Attempt to retrieve users from database.
		$db_users = $this->_parent
			->where($field, $match, $limit, $offset)
			->get('entities')
			->result();

		// If found any, create their objects.
		if ($db_users)
		{
			foreach ($db_users as $db_user)
			{
				$users[] = new CG_User($db_user);
			}
		}

		// Return the final result.
		return $users;
    }

	// ------------------------------------------------------------------------

	/**
	 * Retrieve all users with optional limit and offset.
	 * @access 	public
	 * @param 	int 	$limit 	Limit to use for getting records.
	 * @param 	int 	$offset Database offset.
	 * @return 	array o objects if found, else null.
	 */
	public function get_all($limit = 0, $offset = 0)
    {
		return $this->get_many(null, null, $limit, $offset);
    }

	// ------------------------------------------------------------------------

	/**
	 * This method is used in order to search users.
	 *
	 * @access 	public
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	array of objects if found, else null.
	 */
	public function find($field, $match = null, $limit = 0, $offset = 0)
	{
		// We start with a empty $users.
		$users = false;

		// Attempt to find users.
		$db_users = $this->_parent
			->find($field, $match, $limit, $offset, 'users')
			->get('entities')
			->result();

		// If we find any, we create their objects.
		if ($db_users)
		{
			foreach ($db_users as $db_user)
			{
				$users[] = new CG_User($db_user);
			}
		}

		return $users;
	}

	// -----------------------------------------------------------------------------
	// Update
	// -----------------------------------------------------------------------------

	/**
	 * Update a single user.
	 * @access 	public
	 * @param 	int 	$user_id
	 * @param 	array 	$data
	 * @return 	bool
	 */
	public function update($id, array $data = array())
	{
		// Empty $data? Nothing to do.
		if (empty($data))
		{
			return false;
		}

		// Split data.
		list($entity, $user, $meta) = $this->_split_data($data);

		// Update entity.
		if ( ! empty($entity) && ! $this->_parent->entities->update($id, $entity))
		{
			return false;
		}

		// Are there any changes to do to "users" table?
		if ( ! empty($user))
		{
			/**
			 * Filters to apply on user's field before proceeding.
			 */
			foreach ($user as $key => &$val) {
				// Global fields.
				if ( has_filter("edit_{$key}")) {
					$var = apply_filters("edit_{$key}", $val, $key);
				}

				// "users" table related fields.
				if ( has_filter("edit_user_{$key}")) {
					$var = apply_filters("edit_user_{$key}", $val, $key);
				}
			}

			// Hash the password if present.
			if (isset($user['password']) && ! empty($user['password']))
			{
				$user['password'] = $this->ci->encryption->hash($user['password']);
			}

			// Make sure the the gender is valid.
			if (isset($user['gender'])
				&& ! in_array($user['gender'], array('unspecified', 'male', 'female')))
			{
				$user['gender'] = 'unspecified';
			}

			if ( ! $this->ci->db->update('users', $user, array('guid' => $id)))
			{
				return false;
			}
		}

		// Are there any metadata to update?
		if ( ! empty($meta))
		{
			$this->_parent->metadata->update_meta($id, $meta);
		}

		return true;
	}

	// ------------------------------------------------------------------------

	/**
	 * Update all or multiple users by arbitrary WHERE clause.
	 * @access 	public
	 * @return 	bool
	 */
	public function update_by()
	{
		// Collect arguments first and make sure there are any.
		$args = func_get_args();
		if (empty($args))
		{
			return false;
		}

		// Data to update is always the last element.
		$data = array_pop($args);
		if (empty($data))
		{
			return false;
		}

		// Get users
		if ( ! empty($args))
		{
			(is_array($args[0])) && $args = $args[0];
			$users = $this->get_many($args);
		}
		else
		{
			$users = $this->get_all();
		}

		// If there are any users, proceed to update.
		if ($users)
		{
			foreach ($users as $user)
			{
				$this->update($user->id, $data);
			}

			return true;
		}

		// Nothing happened, return false.
		return false;
	}

	// -----------------------------------------------------------------------------
	// Delete
	// -----------------------------------------------------------------------------

	/**
	 * Delete a single user by ID, username or email address.
	 * @access 	public
	 * @param 	mixed 	$id 	The primary key value.
	 * @return 	boolean
	 */
	public function delete($id)
    {
		// Deleting by ID?
		if (is_numeric($id))
		{
			return $this->delete_by('id', $id, 1, 0);
		}

		// Deleting by email address?
		if (false !== filter_var($id, FILTER_VALIDATE_EMAIL))
		{
			return $this->delete_by('email', $id, 1, 0);
		}

		// Deleting by username?
		if (is_string($id))
		{
			return $this->delete_by('username', $id, 1, 0);
		}

		// Otherwise, let the "delete_by" method handle the rest.
		return $this->delete_by($id, null, 1, 0);

    }

	// ------------------------------------------------------------------------

	/**
	 * Delete multiple users by arbitrary WHERE clause.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @return 	boolean
	 */
	public function delete_by($field = null, $match = null, $limit = 0, $offset = 0)
    {
		// Let's find users first.
		$users = $this->get_many($field, $match, $limit, $offset);

		// If no user found, nothing to do.
		if ( ! $users)
		{
			return false;
		}

		// Let's prepare users IDS.
		$ids = array();
		foreach ($users as $user)
		{
			$ids[] = $user->id;
		}

		// Double check that we have IDs.
		if (empty($ids))
		{
			return false;
		}

		return $this->_parent->entities->delete_by('id', $ids);

    }

	// -----------------------------------------------------------------------------
	// Remove
	// -----------------------------------------------------------------------------

	/**
	 * Completely remove a single user by ID, username or email address.
	 *
	 * @access 	public
	 * @param 	mixed 	$id 	User's ID, username, email address or array of WHERE clause
	 * @return 	bool
	 */
	public function remove($id)
	{
		// Removing by ID?
		if (is_numeric($id))
		{
			return $this->remove_by('id', $id, 1, 0);
		}

		// Removing by email address?
		if (false !== filter_var($id, FILTER_VALIDATE_EMAIL))
		{
			return $this->remove_by('email', $id, 1, 0);
		}

		// Removing by username?
		if (is_string($id))
		{
			return $this->remove_by('username', $id, 1, 0);
		}

		// Otherwise, let the "remove_by" method handle the rest.
		return $this->remove_by($id, null, 1, 0);
	}

	// ------------------------------------------------------------------------

	/**
	 * Completely remove multiple users by arbitrary WHERE clause.
	 *
	 * @access 	public
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	public function remove_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// See if users exist.
		$users = $this->get_many($field, $match, $limit, $offset);

		// If not users found, nothing to do.
		if ( ! $users)
		{
			return false;
		}

		// Collect users IDs.
		$ids = array();
		foreach ($users as $user)
		{
			$ids[] = $user->id;
		}

		// Double check users IDs.
		if (empty($ids))
		{
			return false;
		}

		return $this->_parent->entities->remove_by('id', $ids);
	}

	// -----------------------------------------------------------------------------
	// Restore
	// -----------------------------------------------------------------------------

	/**
	 * Restore a previously soft-deleted user.
	 *
	 * @access 	public
	 * @param 	mixed 	$id 	The user's ID, username, email address or WHERE clause.
	 * @return 	bool
	 */
	public function restore($id)
	{
		// Restoring by ID?
		if (is_numeric($id))
		{
			return $this->restore_by('id', $id, 1, 0);
		}

		// Restoring by email address?
		if (false !== filter_var($id, FILTER_VALIDATE_EMAIL))
		{
			return $this->restore_by('email', $id, 1, 0);
		}

		// Restoring by username?
		if (is_string($id))
		{
			return $this->restore_by('username', $id, 1, 0);
		}

		// Otherwise, let the "restore_by" method handle the rest.
		return $this->restore_by($id, null, 1, 0);
	}

	// ------------------------------------------------------------------------

	/**
	 * Restore multiple or all soft-deleted users.
	 *
	 * @access 	public
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	public function restore_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Collect users.
		$users = $this->get_many($field, $match, $limit, $offset);

		// If not users found, nothing to do.
		if (empty($users))
		{
			return false;
		}

		// Collect users IDs.
		$ids = array();
		foreach ($users as $user)
		{
			$ids[] = $user->id;
		}

		// Double check users IDs.
		if (empty($ids))
		{
			return false;
		}

		return $this->_parent->entities->restore_by('id', $ids);
	}

	// ------------------------------------------------------------------------
	// Front-end methods.
	// ------------------------------------------------------------------------

	/**
	 * register
	 *
	 * Method used for users registration to the front-end.
	 *
	 * @access 	public
	 * @param 	array 	$data 	Array of user's data.
	 * @return 	mixed 	The user's ID if created, else false.
	 */
	public function register($data = array())
	{
		// No data provided? Nothing to do...
		if (empty($data))
		{
			set_alert(__('All fields are required.'), 'error');
			return false;
		}

		// User successfully created?
		if (false === ($guid = $this->create($data)))
		{
			// Other wise, an error occurred.
			set_alert(__('Unable to create user account'), 'error');
			return false;
		}

		// Log the activity.
		log_activity($guid, 'Registered.');

		$email_activation = ( ! isset($data['enabled']) && false !== $this->_parent->options->item('email_activation'));
		$manual_activation = (false !== $this->_parent->options->item('manual_activation', false));

		// Requires a email activation?
		if (false === $email_activation)
		{
			// No activation required?
			if (false === $manual_activation) {
				$this->update($guid, ['enabled'=> 1]);
				set_alert(__('Account successfully created. You may now login.'), 'success');
			}
			else {
				set_alert(__('All accounts require approval by a site administrator before being active. You will receive an email once approved.'), 'info');
			}

			return $guid;
		}

		// Requires a manual activation?
		if (true === $manual_activation)
		{
			$send_email = $this->_parent->send_email(
				$guid,
				__('lang_email_manual_activation'),
				'view:emails/users/manual_activation'
			);

			$msg = __('All accounts require approval by a site administrator before being active. You will receive an email once approved.');
		}
		else
		{
			// We create the activation code then send it to user.
			$code = random_string('alnum', 40);
			$this->_parent->variables->create(array(
				'guid'   => $guid,
				'name'   => 'activation_code',
				'value'  => $code,
				'params' => $this->ci->input->ip_address(),
			));

			$send_email = $this->_parent->send_email(
				$guid,
				__('lang_email_register'),
				'view:emails/users/register',
				array('link' => site_url('activate-account?code='.$code),)
			);

			$msg = __('Account successfully created. The activation link was sent to you.');
		}

		if ($send_email) {
			set_alert($msg, 'info');
		}

		return $guid;
	}

	// ------------------------------------------------------------------------

	/**
	 * resend_link
	 *
	 * Method for resending account activation link to user;
	 *
	 * @access 	public
	 * @param 	mixed  	$identity 	User's ID, username or email address.
	 * @return 	bool 	true if successful, else false.
	 */
	public function resend_link($identity)
	{
		// Nothing passed? Nothing to do...
		if (empty($identity))
		{
			set_alert(__('All fields are required.'), 'error');
			return false;
		}

		// Get the user from database and make sure he/she exists.
		if (false === ($user = $this->get($identity)))
		{
			set_alert(__('This user does not exist.'), 'error');
			return false;
		}

		// User already enabled?
		if ($user->enabled == 1)
		{
			set_alert(__('This account is already active.'), 'error');
			return false;
		}

		// Process status.
		$status = false;

		// Check if a variable already exists.
		$var = $this->_parent->variables->get_by(array(
			'guid' => $user->id,
			'name' => 'activation_code',
		));
		// Exists an valid?
		if ($var && $var->created_at > (time() - (DAY_IN_SECONDS * 2)))
		{
			$code   = $var->value;
			$status = true;
		}
		else
		{
			$code = random_string('alnum', 40);

			// If the variable exists, update it.
			if ($var)
			{
				$status = $var->update(array(
					'value'      => $code,
					'params'     => $this->ci->input->ip_address(),
					'created_at' => time(),
				));
			}
			// Create it.
			else
			{
				$status = (bool) $this->_parent->variables->create(array(
					'guid'   => $user->id,
					'name'   => 'activation_code',
					'value'  => $code,
					'params' => $this->ci->input->ip_address(),
				));
			}
		}

		// The process was successful?
		if (true === $status)
		{
			// TODO: Log the activity.
			log_activity($user->id, 'Requested new activation link.');

			$send_email = $this->_parent->send_email(
				$user,
				__('lang_email_resend'),
				'view:emails/users/resend',
				array('link' => anchor('activate-account?code='.$code))
			);

			if ($send_email) {
				set_alert(__('Account activation link successfully resent. Check your inbox or spam.'), 'success');
			}

			// Delete old captcha.
			$this->_parent->auth->delete_captcha();
		}
		else
		{
			set_alert(__('Unable to resend account activation link'), 'error');
		}

		return $status;
	}

	// ------------------------------------------------------------------------

	/**
	 * restore_account
	 *
	 * Method for allowing users to restore their deleted accounts.
	 *
	 * @access 	public
	 * @param 	string 	$identity 	User's username or email address.
	 * @param 	string 	$password 	Account's password.
	 * @return 	bool
	 */
	public function restore_account($identity, $password)
	{
		if (empty($identity) OR empty($password))
		{
			set_alert(__('All fields are required.'), 'error');
			return false;
		}

		// Get user from database and make sure he/she exists.
		if (false === ($user = $this->get($identity)))
		{
			/**
			 * The reason we are using wrong credentials here is to
			 * hide the fact that the account does not exist.
			 */
			set_alert(__('Invalid username/email address and/or password'), 'error');
			return false;
		}

		// Check the password.
		if ( ! $this->ci->encryption->verify($password, $user->password))
		{
			set_alert(__('Invalid username/email address and/or password'), 'error');
			return false;
		}

		// The user is not really deleted?
		if ($user->deleted == 0)
		{
			set_alert(__('Only deleted accounts can be restored'), 'error');
			return false;
		}

		// Process status.
		$status = (false !== $user->update('deleted', 0) && $this->_parent->auth->quick_login($user));

		// Successfully restored and logged in?
		if (false !== $status)
		{
			// TODO: Log the activity.
			log_activity($user->id, 'Restored account.');

			$send_email = $this->_parent->send_email(
				$user,
				__('lang_email_restore'),
				'view:emails/users/restore'
			);

			if ($send_email) {
				set_alert(__('Account successfully restored'), 'success');
			}

			// Delete old captcha.
			$this->_parent->auth->delete_captcha();
		}
		else
		{
			set_alert(__('Unable to restore account'), 'error');
		}

		// Return the final process status.
		return $status;
	}

	// ------------------------------------------------------------------------

	/**
	 * prep_password_reset
	 *
	 * Method for prepare account for password reset.
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	bool
	 */
	public function prep_password_reset($identity)
	{
		// $identity is empty?
		if (empty($identity))
		{
			set_alert(__('All fields are required.'), 'error');
			return false;
		}

		// Get user from database and make sure (s)he exists.
		if (false === ($user = $this->get($identity)))
		{
			set_alert(__('This user does not exist.'), 'error');
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
			set_alert(__('Your account has been deleted but not yet removed from database. Contact us if you want it restored'), 'error');
			return false;
		}

		// Prepare process status.
		$status = false;

		// Check if there is an existing password code.
		$var = $this->_parent->variables->get_by(array(
			'guid' => $user->id,
			'name' => 'password_code',
		));

		// Found?
		if ($var && $var->created_at > time() - (DAY_IN_SECONDS * 2))
		{
			$code   = $var->value;
			$status = true;
		}
		else
		{
			$code = random_string('alnum', 40);

			// If the variable exists, update it.
			if ($var)
			{
				$status = $var->update(array(
					'value'      => $code,
					'params'     => $this->ci->input->ip_address(),
					'created_at' => time(),
				));
			}
			// Create it.
			else
			{
				$status = (bool) $this->_parent->variables->create(array(
					'guid'   => $user->id,
					'name'   => 'password_code',
					'value'  => $code,
					'params' => $this->ci->input->ip_address(),
				));
			}
		}

		// Successful process?
		if (true === $status)
		{
			// TODO: Log the activity.
			log_activity($user->id, 'Request password reset.');

			$send_email = $this->_parent->send_email(
				$user,
				__('lang_email_recover'),
				'view:emails/users/forgot',
				array('link' => anchor('reset-password?code='.$code))
			);

			if ($send_email) {
				set_alert(__('Password reset link successfully sent'), 'success');
			}
		}
		else
		{
			set_alert(__('Unable to send password reset link'), 'error');
		}

		return $status;
	}

	// ------------------------------------------------------------------------

	/**
	 * check_password_code
	 *
	 * Method for checking the provided password reset code.
	 *
	 * @access 	public
	 * @param 	string 	$code 	The password reset code.
	 * @return 	mixed	Return the user's ID if found, else false.
	 */
	public function check_password_code($code = null)
	{
		// First we check if set and check the length.
		if (empty($code) OR strlen($code) !== 40)
		{
			return false;
		}

		// Attempt to get the variable from database.
		$var = $this->_parent->variables->get_by(array(
			'name'          => 'password_code',
			'BINARY(value)' => $code,
			'created_at >'  => time() - (DAY_IN_SECONDS * 2),
		));

		return (false !== $var) ? $var->guid : false;
	}

	// ------------------------------------------------------------------------

	/**
	 * reset_password
	 *
	 * Method for reseting user's password.
	 *
	 * @access 	public
	 * @param 	int 	$guid 		The user's ID;
	 * @param 	string 	$password 	The new password.
	 * @return 	bool
	 */
	public function reset_password($guid, $password)
	{
		// Nothing provided? Nothing to do...
		if (empty($guid) OR empty($password))
		{
			set_alert(__('All fields are required.'), 'error');
			return false;
		}

		// Make sure the user exists.
		if (false === ($user = $this->get($guid)))
		{
			set_alert(__('Unable to reset password'), 'error');
			return false;
		}

		/**
		 * The process status depending on two things:
		 * 1. Whether the user is using the same old password.
		 * 2. Whether a different password is provided and user updated.
		 */

		// Same password? status is set to true.
		$status = (false !== $this->ci->encryption->verify($password, $user->password));

		// Different password? The status depends on the update.
		$status OR $status = $user->update('password', $password);

		// Successful?
		if (true === $status)
		{
			// TODO: Log the activity.
			log_activity($user->id, 'Reset password.');

			$send_email = $this->_parent->send_email(
				$guid,
				__('lang_email_password'),
				'view:emails/users/password',
				array('login_url' => site_url('login'))
			);

			if ($send_email) {
				set_alert(__('Password successfully reset'), 'success');
			}

			// Purge password codes.
			$this->_parent->auth->delete_password_codes($guid);
		}
		else
		{
			set_alert(__('Unable to reset password'), 'error');
		}

		// Return the process status.
		return $status;
	}

	// ------------------------------------------------------------------------

	/**
	 * activate_by_code
	 *
	 * Method for activating a user by the given activation code.
	 *
	 * @access 	public
	 * @param 	string 	$code 	The account activation code.
	 * @return 	bool 	true if activated, else false.
	 */
	public function activate_by_code($code = null)
	{
		// Check whether it's set and check its length.
		if (empty($code) OR strlen($code) !== 40)
		{
			set_alert(__('This account activation link is no longer valid'), 'error');
			return false;
		}

		// Get variable from database and make sure it exists.
		$var = $this->_parent->variables->get_by(array(
			'name'          => 'activation_code',
			'BINARY(value)' => $code,
			'created_at >'  => time() - (DAY_IN_SECONDS * 2),
		));
		if (false === $var)
		{
			set_alert(__('This account activation link is no longer valid'), 'error');
			return false;
		}

		/**
		 * If the user does not exists, we fake the message telling that
		 * the key is invalid instead of saying the user does not exist.
		 */
		if (false === ($user = $this->get($var->guid)))
		{
			// First purge activation codes.
			$this->_parent->auth->delete_activation_codes($var->guid);

			set_alert(__('This account activation link is no longer valid'), 'error');
			return false;
		}

		// Successfully activated?
		if (false !== $user->update('enabled', 1))
		{
			// TODO: Log the activity.
			log_activity($user->id, 'Activated account.');

			$send_email = $this->_parent->send_email(
				$user,
				__('lang_email_activated'),
				'view:emails/users/activated',
				array('login_url' => site_url('login'))
			);

			if ($send_email) {
				set_alert(__('Account successfully activated. You may now login'), 'success');
			}

			// Purge activation codes.
			$this->_parent->auth->delete_activation_codes($var->guid);
			return true;
		}

		// Otherwise, an error occurred somewhere.
		set_alert(__('Unable to activate user account'), 'error');
		return false;
	}

	// ------------------------------------------------------------------------
	// Utilities.
	// ------------------------------------------------------------------------

	/**
	 * Count all users.
	 *
	 * @access 	public
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	int
	 */
	public function count($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// We make sure to select only users and join their table.
		$this->ci->db
			->where('entities.type', 'user')
			->join('users', 'users.guid = entities.id');

		// We run the query now.
		$query = $this->_parent
			->where($field, $match, $limit, $offset)
			->get('entities');

		// We return the count.
		return $query->num_rows();
	}

	// ------------------------------------------------------------------------

	/**
	 * Deletes users that have no existing records in "entities".
	 *
	 * @access 	public
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	public function purge($limit = 0, $offset = 0)
	{
		// We get existing users IDs.
		$entities_ids = $this->_parent->entities->get_ids('type', 'user');

		// Let's see if there are users.
		$users = $this->ci->db
			->where_not_in('guid', $entities_ids)
			->get('users')
			->result();

		// No users found? Nothing to do.
		if ( ! $users)
		{
			return false;
		}

		// Collect users ids.
		$ids = array();
		foreach ($users as $user)
		{
			$ids[] = $user->id;
		}

		// Double check $ids array.
		if (empty($ids))
		{
			return false;
		}

		// We delete users.
		$this->ci->db
			->where_in('guid', $ids)
			->delete('users');

		// Hold the status for later use.
		$status = ($this->ci->db->affected_rows() > 0);

		// Deleted? Remove everything related to them.
		if ($status === true)
		{
			// Delete users activities.
			$this->_parent->activities->delete_by('user_id', $ids);

			// Remove any groups or objects owned by users.
			$this->_parent->entities->remove_by('parent_id', $ids);
			$this->_parent->entities->remove_by('owner_id', $ids);

			// Delete all users metadata and variables.
			$this->_parent->metadata->delete_by('guid', $ids);
			$this->_parent->variables->delete_by('guid', $ids);

			// Delete all users relations.
			$this->_parent->relations->delete_by('guid_from', $ids);
			$this->_parent->relations->delete_by('guid_to', $ids);
		}

		// Return the process status.
		return $status;
	}

	// ------------------------------------------------------------------------

	/**
	 * Pisahkan data saat dibuat atau diperbarui menjadi entitas dan pengguna.
	 * @param 	array 	$data
	 * @return 	array.
	 */
	private function _split_data(array $data = array())
	{
		if (empty($data))
		{
			return $data;
		}

		$_data = array();

		foreach ($data as $key => $val)
		{
			// Entities table.
			if (in_array($key, $this->_parent->entities->fields()))
			{
				$_data[0][$key] = $val;
			}
			// Users table.
			elseif (in_array($key, $this->fields()))
			{
				$_data[1][$key] = $val;
			}
			else
			{
				$_data[2][$key] = $val;
			}
		}

		if (empty($_data))
		{
			return $data;
		}

		// Make sure all three elements are set.
		(isset($_data[0])) OR $_data[0] = array();
		(isset($_data[1])) OR $_data[1] = array();
		(isset($_data[2])) OR $_data[2] = array();

		// Sort things up.
		ksort($_data);

		return $_data;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('add_user'))
{
	/**
	 * Create a single user.
	 * @param 	array 	$data 	array of data to insert.
	 * @return 	int 	The user's id if created, else false.
	 */
	function add_user(array $data = array())
	{
		return get_instance()->users->create($data);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_user'))
{
	/**
	 * Retrieve a single user by ID, username, email or arbitrary WHERE clause.
	 * @param 	mixed 	$id
	 * @return 	object if found, else null.
	 */
	function get_user($id)
	{
		return get_instance()->users->get($id);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_user_by'))
{
	/**
	 * Retrieve a single user by arbitrary WHERE clause.
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @return 	object if found, else null.
	 */
	function get_user_by($field, $match = null)
	{
		return get_instance()->users->get_by($field, $match);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_users'))
{
	/**
	 * Retrieve multiple users by arbitrary WHERE clause.
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	array of objects if found, else null.
	 */
	function get_users($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->users->get_many($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_all_users'))
{
	/**
	 * Retrieve all users with optional limit and offset.
	 * @access 	public
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	array of objects.
	 */
	function get_all_users($limit = 0, $offset = 0)
	{
		return get_instance()->users->get_all($limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('find_users'))
{
	/**
	 * This function is used in order to search users.
	 *
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	array of objects if found, else null.
	 */
	function find_users($field, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->users->find($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('update_user'))
{
	/**
	 * Update a single user by ID.
	 * @param 	int 	$id 	The user's ID.
	 * @param 	array 	$data 	Array of data to set.
	 * @return 	bool
	 */
	function update_user($id, array $data = array())
	{
		return get_instance()->users->update($id, $data);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('update_user_by'))
{
	/**
	 * Update a single, all or multiple users by arbitrary WHERE clause.
	 * @return 	bool
	 */
	function update_user_by()
	{
		return call_user_func_array(
			array(get_instance()->users, 'update_by'),
			func_get_args()
		);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('update_users'))
{
	/**
	 * Update a single, all or multiple users by arbitrary WHERE clause.
	 * @return 	bool
	 */
	function update_users()
	{
		return call_user_func_array(
			array(get_instance()->users, 'update_by'),
			func_get_args()
		);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('delete_user'))
{
	/**
	 * Delete a single user by ID, username or email address.
	 * @param 	mixed 	$id 	ID, username or email address.
	 * @return 	bool
	 */
	function delete_user($id)
	{
		return get_instance()->users->delete($id);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('delete_user_by'))
{
	/**
	 * Soft delete multiple users by arbitrary WHERE clause.
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @return 	bool
	 */
	function delete_user_by($field, $match = null)
	{
		return get_instance()->users->delete_by($field, $match);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('delete_users'))
{
	/**
	 * Soft delete multiple or all users by arbitrary WHERE clause.
	 *
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	function delete_users($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->users->delete_by($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('remove_user'))
{
	/**
	 * Completely remove a user from database.
	 * @param 	mixed 	$id 	The user's ID, username, email address or WhERE clause.
	 * @return 	bool
	 */
	function remove_user($id)
	{
		return get_instance()->users->remove($id);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('remove_user_by'))
{
	/**
	 * Completely remove multiple users from database.
	 *
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	function remove_user_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->users->remove_by($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('remove_users'))
{
	/**
	 * Completely remove multiple or all users from database.
	 *
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	function remove_users($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->users->remove_by($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('restore_user'))
{
	/**
	 * Restore a previously soft-deleted user.
	 * @access 	public
	 * @param 	mixed 	$id 	The user's ID, username, email or WHERE clause.
	 * @return 	bool
	 */
	function restore_user($id)
	{
		return get_instance()->users->restore($id);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('restore_user_by'))
{
	/**
	 * Restore multiple or all soft-deleted users.
	 *
	 * @access 	public
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	function restore_user_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->users->restore_by($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('restore_users'))
{
	/**
	 * Restore multiple or all soft-deleted users.
	 *
	 * @access 	public
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	function restore_users($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->users->restore_by($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('count_users'))
{
	/**
	 * Count all users on database with arbitrary WHERE clause.
	 *
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	int
	 */
	function count_users($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->users->count($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('purge_users'))
{
	/**
	 * Delete users that has no records in "entities" table.
	 *
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	function purge_users($limit = 0, $offset = 0)
	{
		return get_instance()->users->purge($limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('user_avatar')):
	/**
	 * Returns the avatar of the selected user.
	 *
	 * @param 	int 	$size 	size of the image to display.
	 * @param 	int 	$id 	the user's id.
	 * @param 	mixed 	$attrs 	html attributes (string or array)
	 */
	function user_avatar($size = 100, $id = null, $attrs = array())
	{
		$CI =& get_instance();

		// An email is passed?
		if (filter_var($id, FILTER_VALIDATE_EMAIL) !== false)
		{
			$avatar = md5($id);
		}

		// An md5 hashed string?
		elseif (is_string($id) && strlen($id) === 32)
		{
			$avatar = $id;
		}

		// An ID?
		else
		{
			// If the user is logged in and ID is the same
			if ($CI->auth->online() && $CI->auth->user()->id == $id)
			{
				$hash = $CI->auth->user()->avatar;
				$avatar = $hash ? $hash : md5($CI->auth->user()->email);
			}
			// If the user exists, generate the hash.
			elseif (false !== $user = $CI->users->get_by('id', $id))
			{
				$hash = $user->avatar;
				$avatar = $hash ? $hash : md5($user->email);
			}
			// Otherwise, nothing to return.
			else
			{
				return null;
			}
		}

		// The path where the avatar should be.
		$avatar_path = $CI->themes->upload_path("avatars/{$avatar}");

		if (strpos($hash, '://') !== false)
		{
			$avatar_url = $hash;
		}
		elseif ( ! file_exists($avatar_path))
		{
			$avatar_url = $CI->themes->theme_url("assets/img/user.png");
			$uri = "http://www.gravatar.com/avatar/".$hash."?d=404";
			$headers = @get_headers($uri);
			if ( is_array($headers)
				&& ! preg_match("|200|", $headers[0])
				&& $CI->config->item('use_gravatar')){
				$avatar_url = "https://www.gravatar.com/avatar/{$hash}?r=g&amp;d=mm";
				($size >= 1) && $avatar_url .= "&amp;s={$size}";
			}
		}
		else
		{
			$avatar_url = $CI->themes->upload_url("avatars/{$avatar}");
		}

		if (is_array($attrs)) {
			$attrs['width'] = $attrs['height'] = $size;
			$attrs['data-src'] = $avatar_url;
		}
		else {
			$attrs .= $size >= 1 ? ' width="'.$size.'" height="'.$size.'"' : '';
			$attrs .= ' data-src="'.$avatar_url.'"';
		}

		// Another security layer is to load theme library if the function
		return img('data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==', false, $attrs);
	}
endif; // End of: user_avatar.

// ------------------------------------------------------------------------

if ( ! function_exists('user_anchor')) :
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
			$title = __($line);
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
endif; // End of: user_anchor.

// ------------------------------------------------------------------------

class CG_User
{
	/**
	 * User data container.
	 * @var 	object
	 */
	public $data;

	/**
	 * The user's ID.
	 * @var 	integer
	 */
	public $id = 0;

	/**
	 * Array of data awaiting to be updated.
	 * @var 	array
	 */
	protected $queue = array();

	/**
	 * Constructor.
	 *
	 * Retrieves the user data and passes it to CG_User::init().
	 *
	 * @access 	public
	 * @param 	mixed	 $id 	User's ID, username, object or WHERE clause.
	 * @return 	void
	 */
	public function __construct($id = 0) {
		// In case we passed an instance of this object.
		if ($id instanceof CG_User) {
			$this->init($id->data);
			return;
		}

		// In case we passed the entity's object.
		elseif (is_object($id)) {
			$this->init($id);
			return;
		}

		if ($id) {
			$user = get_user($id);
			if ($user) {
				$this->init($user->data);
			} else {
				$this->data = new stdClass();
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Sets up object properties.
	 * user_avatar@access 	public
	 * @param 	object
	 */
	public function init($user)
	{
		// Format user's ID.
		$this->id = (int) $user->id;

		// Add user's full name.
		$user->full_name = null;
		if ( ! isset($user->full_name) && isset($user->first_name)) {
			$user->full_name = $user->first_name;
			isset($user->last_name) && $user->full_name .= ' '.$user->last_name;

			/**
			 * Global filter on users' full names.
			 */
			if (has_filter('users_fullname')) {
				$user->full_name = apply_filters('users_fullname', $user->full_name);
			}

			/**
			 * Individual filter on user's full name.
			 */
			if (has_filter("user_fullname_{$this->id}")) {
				$user->full_name = apply_filters("user_fullname_{$this->id}", $user->full_name);
			}
		}

		/**
		 * Global filter applied to all retrieved uses.
		 */
		if (has_filter('get_user')) {
			$user = apply_filters('get_user', $user);
		}

		/**
		 * A filter applied to the user by his/her ID.
		 */
		if (has_filter("get_user_{$this->id}")) {
			$user = apply_filters("get_user_{$this->id}", $user);
		}

		// Now we cache the user.
		$this->data = $user;

		/**
		 * Allow package or themes define the role used as
		 * the admin role.
		 */
		$admin_role = apply_filters('users_admin_role', 'administrator');

		// Whether the user is an admin or not.
		$this->data->admin = ($admin_role === $user->subtype);
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic method for checking the existence of a property.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 * @return 	bool 	true if the property exists, else false.
	 */
	public function __isset($key) {
		// Just make it possible to use ID.
		if ('ID' == $key) {
			$key = 'id';
		}

		// Found in $data container?
		if (isset($this->data->{$key})) {
			return true;
		}

		// Found as object property?
		if (isset($this->{$key})) {
			return true;
		}

		// Check for metadata.
		return metadata_exists($this->id, $key);
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic method for getting a property value.
	 * @access 	public
	 * @param 	string 	$key 	The property key to retrieve.
	 * @return 	mixed 	Depends on the property value.
	 */
	public function __get($key) {
		// We start with an empty value.
		$value = false;

		// Is if found in $data object?
		if (isset($this->data->{$key})) {
			$value = $this->data->{$key};
		}
		// Otherwise, let's attempt to get the meta.
		else {
			$meta = get_meta($this->id, $key);
			if ($meta) {
				$value = $meta->value;
			}
		}

		// Then we return the final result.
		return $value;
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic method for setting a property value.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 * @param 	mixed 	$value 	The property value.
	 */
	public function __set($key, $value) {
		// Just make it possible to use ID.
		if ('ID' == $key) {
			$key = 'id';
		}

		// If found, we make sure to set it.
		$this->data->{$key} = $value;

		// We enqueue it for later use.
		$this->queue[$key]  = $value;
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic method for unsetting a property.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 */
	public function __unset($key) {
		// Remove it from $data object.
		if (isset($this->data->{$key})) {
			unset($this->data->{$key});
		}

		// We remove it if queued.
		if (isset($this->queue[$key])) {
			unset($this->queue[$key]);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for checking the existence of an user in database.
	 * @access 	public
	 * @param 	none
	 * @return 	bool 	true if the user exists, else false.
	 */
	public function exists() {
		return ( ! empty($this->id));
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for checking the existence of a property.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 * @return 	bool 	true if the property exists, else false.
	 */
	public function has($key) {
		return $this->__isset($key);
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns an array representation of this object data.
	 *
	 * @access 	public
	 * @return 	array
	 */
	public function to_array() {
		return get_object_vars($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for setting a property value.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 * @param 	string 	$value 	The property value.
	 * @return 	object 	we return the object to make it chainable.
	 */
	public function set($key, $value) {
		$this->__set($key, $value);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for getting a property value.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 * @return 	mixed 	Depends on the property's value.
	 */
	public function get($key) {
		return $this->__get($key);
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for updating the user in database.
	 *
	 * @access 	public
	 * @param 	string 	$key 	The field name.
	 * @param 	mixed 	$value 	The field value.
	 * @return 	bool 	true if updated, else false.
	 */
	public function update($key, $value = null) {
		// We make sure things are an array.
		$data = (is_array($key)) ? $key : array($key => $value);

		// Keep the status in order to dequeue the key.
		$status = update_user($this->id, $data);

		if ($status === true) {
			foreach ($data as $k => $v) {
				if (isset($this->queue[$k])) {
					unset($this->queue[$k]);
				}
			}
		}

		return $status;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for saving anything changes.
	 * @access 	public
	 * @param 	void
	 * @return 	bool 	true if updated, else false.
	 */
	public function save() {
		// We start if FALSE status.
		$status = false;

		// If there are enqueued changes, apply them.
		if ( ! empty($this->queue)) {
			$status = update_user($this->id, $this->queue);

			// If the update was successful, we reset $queue array.
			if ($status === true) {
				$this->queue = array();
			}
		}

		// We return the final status.
		return $status;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for retrieving the array of data waiting to be saved.
	 * @access 	public
	 * @return 	array
	 */
	public function dirty() {
		return $this->queue;
	}

}