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
 * Users - Admin Controller
 *
 * This controller allow administrators to manage users accounts.
 *
 * @subpackage 	Admin
 * @author		Tokoder Team
 */
class Users extends CG_Controller_Admin {

	/**
	 * __construct
	 *
	 * Load needed files.
	 */
	public function __construct()
	{
		parent::__construct();

		// Default page title and icon.
		$this->data['page_icon']  = 'users';
		$this->data['page_title'] = __('lang_manage_users');

		// Add our head string.
		add_filter('admin_head', array($this, '_admin_head'));
	}

    // -----------------------------------------------------------------------------

	/**
	 * List reports.
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		// Create pagination.
		$this->load->library('pagination');

		// Users filter.
		$where = array();

		// Filter by role (subtype).
		if (null !== ($role = $this->input->get('role', true)))
		{
			$where['subtype'] = $role;
		}

		if ( ! $this->auth->is_admin() && user_permission('users'))
		{
			$where['owner_id'] = $this->auth->user_id();
		}

		// Account status.
		if (null !== ($status = $this->input->get('status', true)))
		{
			switch ($status) {
				case 'deleted':
					$where['deleted'] = 1;
					break;
				case 'active':
					$where['enabled'] = 1;
					break;
				case 'inactive':
					$where['enabled'] = 0;
					break;
				case 'banned':
					$where['enabled'] = -1;
					break;
			}
		}

		// Pagination configuration.
		$config['base_url']   = $config['first_link'] = admin_url('users');
		$config['total_rows'] = $this->users->count($where);
		$config['per_page']   = config_item('per_page');

		// Initialize pagination.
		$this->pagination->initialize($config);

		// Create pagination links.
		$this->data['pagination'] = $this->pagination->create_links();

		// Display limit.
		$limit = $config['per_page'];

		// Prepare offset.
		$offset = 0;
		if ($this->input->get('page'))
		{
			$offset = $config['per_page'] * ($this->input->get('page') - 1);
		}

		// Get all users.
		$this->data['users'] = $this->users->get_many($where, null, $limit, $offset);

		/**
		 * Cache users actions.
		 */
		$actions = array('activate', 'deactivate', 'delete', 'restore', 'remove');
		$action  = $this->input->get('action');
		$user    = (int) $this->input->get('user', true);

		if (($action && in_array($action, $actions))
			&& ($user && $user > 0)
			&& check_nonce_url('user-'.$action.'_'.$user)
			&& method_exists($this, '_'.$action))
		{
			return call_user_func_array(array($this, '_'.$action), array($user));
		}

		// Set page title and render view.
		$this->themes
			->set_title(__('lang_manage_users'))
			->render($this->data);
    }

	// ------------------------------------------------------------------------

	/**
	 * add
	 *
	 * Method for adding a new user's account.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function add()
	{
		// Prepare form validation and rules.
		$this->prep_form(array(
			array(	'field' => 'first_name',
					'label' => 'lang:lang_first_name',
					'rules' => 'trim|required|max_length[32]'),
			array(	'field' => 'last_name',
					'label' => 'lang:lang_last_name',
					'rules' => 'trim|required|max_length[32]'),
			array(	'field' => 'email',
					'label' => 'lang:lang_email_address',
					'rules' => 'trim|required|valid_email|unique_email'),
			array(	'field' => 'password',
					'label' => 'lang:lang_password',
					'rules' => 'trim|required|min_length[8]|max_length[20]'),
			array(	'field' => 'cpassword',
					'label' => 'lang:confirm_password',
					'rules' => 'trim|required|matches[password]'),
		), '#add-user');

		// Default user fields.
		$_defaults = array('first_name', 'last_name', 'email', 'password', 'cpassword');

		// Allow users to add extra fields.
		$defaults = apply_filters('users_fields', $_defaults);

		// Let's now generate our form fields.
		foreach ($defaults as $field)
		{
			$name = $field;
			$inputs[$name] = array_merge(
				$this->config->item($name, 'cg_inputs'),
				array(
					'value' => set_value($name, $this->input->post($name, false))
				)
			);
		}

		// Let's now add our generated inputs to view.
		$this->data['inputs'] = $inputs;

		// Before form processing
		if ($this->form_validation->run() == false)
		{
			// Page icon and title.
			$this->data['page_icon'] = 'user-plus';
			$this->data['page_title'] = __('lang_add_user');

			$this->themes
				->set_title($this->data['page_title'])
				->render($this->data);
		}
		// Process form.
		else
		{
			if (true !== check_nonce('add-user'))
			{
				set_alert(__('error_csrf'), 'error');
				redirect(admin_url('users/add'), 'refresh');
				exit;
			}

			$data = $this->input->post(array(
				'first_name',
				'last_name',
				'email',
				'password'
			), true);

			$data['enabled'] = ($this->input->post('enabled') == '1') ? 1 : 0;
			($this->input->post('admin') == '1') && $data['subtype'] = 'administrator';

			// Successful
			if (false !== ($guid = $this->users->create($data)))
			{
				set_alert(__('lang_SUCCESS_ADD'), 'success');
				redirect(admin_url('users'), 'refresh');
			}
			// Something went wrong?
			else {
				set_alert(__('lang_ERROR_ADD'), 'error');
				redirect(admin_url('users/add'), 'refresh');
			}
			exit;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * edit
	 *
	 * Edit an existing user's account.
	 *
	 * @access 	public
	 * @param 	int 	$id 	The user's ID.
	 * @return 	void
	 */
	public function edit($id = 0)
	{
		// Get the user from database.
		$this->data['user'] = $this->users->get($id);
		if ( ! $this->data['user'])
		{
			set_alert(__('lang_ERROR_ACCOUNT_MISSING'), 'error');
			redirect($this->agent->referrer());
			exit;
		}

		$this->data['user']->admin = ($this->data['user']->subtype === 'administrator');

		// Prepare form validation.
		$rules = array(
			array(	'field' => 'first_name',
					'label' => 'lang:lang_first_name',
					'rules' => 'trim|required|min_length[1]|max_length[32]'),
			array(	'field' => 'last_name',
					'label' => 'lang:lang_last_name',
					'rules' => 'trim|required|min_length[1]|max_length[32]'),
		);

		// Using a new email address?
		$email_rules = 'trim|required|valid_email';
		if ($this->input->post('email'))
		{
			if ($this->input->post('email') !== $this->data['user']->email)
			{
				$email_rules .= '|unique_email';
			}

			$rules[] = array(
				'field' => 'email',
				'label' => 'lang:lang_email_address',
				'rules' => $email_rules,
			);
		}

		// Using a different username?
		$username_rules = 'trim|required|min_length[5]|max_length[32]';
		if ($this->input->post('username'))
		{
			if ($this->input->post('username') !== $this->data['user']->username)
			{
				$username_rules .= '|unique_username';
			}

			$rules[] = array(
				'field' => 'username',
				'label' => 'lang:lang_username',
				'rules' => $username_rules,
			);
		}

		// Changing password?
		if ($this->input->post('password'))
		{
			$rules[] = array(
				'field' => 'password',
				'label' => 'lang:lang_password',
				'rules' => 'trim|required|min_length[8]|max_length[20]'
			);
			$rules[] = array(
				'field' => 'cpassword',
				'label' => 'lang:confirm_password',
				'rules' => 'trim|required|min_length[8]|max_length[20]|matches[password]'
			);
		}

		// Prepare form validation and rules.
		$this->prep_form($rules, '#edit-user');

		// Default user fields.
		$_defaults = array('first_name', 'last_name', 'email', 'username');

		// Allow users to add extra fields.
		$defaults = apply_filters('users_fields', array());
		$defaults = array_merge($_defaults, $defaults);

		// Let's now generate our form fields.
		foreach ($defaults as $field)
		{
			/**
			 * Pertama-tama kita mulai dengan mendapatkan nama input.
			 * NOTE: Jika Anda melewatkan array sebagai bidang baru, pastikan untuk SELALU menambahkan nama input
			 */
			$name = (is_array($field)) ? $field['name'] : $field;

			/**
			 * Sekarang kita menyimpan nilai default dari field.
			 * Jika bidangnya adalah array $_defaults, itu berarti itu datang
			 * dari tabel "user". Jika tidak, itu adalah metadata.
			 */
			$value = (in_array($name, $_defaults))
				? $this->data['user']->{$name}
				: $this->metadata->get_meta($this->data['user']->id, $name, true);

			// In case of an array, use it as-is.
			if (is_array($field))
			{
				$inputs[$name] = array_merge($field, array('value' => set_value($name, $value)));
			}
			/**
			 * Jika string dilewatkan, kami memastikannya ada terlebih dahulu,
			 * if it does, we add it. Otherwise, we set error.
			 */
			elseif ($item = $this->config->item($name, 'cg_inputs'))
			{
				$inputs[$name] = array_merge($item, array('value' => set_value($name, $value)));
			}
		}

		/**
		 * Bidang di bawah ini adalah bidang default juga, jadi kami tidak memberikan
		 * plugin atau tema hak untuk mengubahnya.
		 */
		$inputs['password']  = $this->config->item('password', 'cg_inputs');
		$inputs['cpassword'] = $this->config->item('cpassword', 'cg_inputs');
		$inputs['gender']    = array_merge(
			$this->config->item('gender', 'cg_inputs'),
			array('selected' => $this->data['user']->gender)
		);

		// Let's now add our generated inputs to view.
		$this->data['inputs'] = $inputs;

		// Before form processing
		if ($this->form_validation->run() == false)
		{
			$this->data['page_icon'] = 'user';
			$this->data['page_title'] = sprintf(__('lang_edit_user_%s'), $this->data['user']->username);

			// Set page title and render view.
			$this->themes
				->set_title($this->data['page_title'])
				->render($this->data);
		}
		// Process form.
		else
		{
			if (true !== check_nonce('edit-user_'.$id))
			{
				set_alert(__('error_csrf'), 'error');
				redirect(admin_url('users/edit/'.$id), 'refresh');
				exit;
			}

			/**
			 * Here we make sure to remove the confirm password field.
			 * Otherwise it will be used as a metadata
			 */
			unset($inputs['cpassword']);

			// Collect all user details.
			$user_data = $this->input->post(array_keys($inputs), true);

			// Format "enabled" and user's "subtype".
			$user_data['enabled'] = ($this->input->post('enabled') == '1') ? 1 : 0;
			$user_data['subtype'] = ($this->input->post('admin') == '1') ? 'administrator' : 'regular';

			/**
			 * After form submit. We make sure to remove fields that have
			 * not been changed: Username, Email address, first name, last name
			 * and user's subtype.
			 */
			$_fields = array(
				'username',
				'email',
				'subtype',
				'first_name',
				'last_name',
				'gender',
				'enabled',
			);
			foreach ($_fields as $_field)
			{
				if ($user_data[$_field] == $this->data['user']->{$_field})
				{
					unset($user_data[$_field]);
				}
			}

			/**
			 * For the password, we make sure to remove it if it's empty
			 * of if it's the same as the old one.
			 */
			if (empty($user_data['password'])
				OR password_verify($user_data['password'], $this->data['user']->password))
			{
				unset($user_data['password']);
			}

			// Successful or nothing to update?
			if (empty($user_data) OR true === $this->users->update($id, $user_data))
			{
				// Log the activity.
				set_alert(__('lang_SUCCESS_EDIT'), 'success');
				redirect(admin_url('users'), 'refresh');
			}
			// Something went wrong?
			else
			{
				set_alert(__('lang_ERROR_EDIT'), 'error');
				redirect(admin_url('users/edit/'.$this->data['user']->id), 'refresh');
			}
			exit;
		}
	}

	// ------------------------------------------------------------------------
	// Quick-access methods.
	// ------------------------------------------------------------------------

	/**
	 * Method for activating the given user.
	 *
	 * @access 	protected
	 * @param 	int 	$id 	The user's ID.
	 * @return 	void
	 */
	protected function _activate($id)
	{
		// No action done on own account.
		if ($id == $this->c_user->id)
		{
			set_alert(__('lang_error_activate_OWN'), 'error');
		}

		// Make sure the user exists.
		elseif (false === ($user = $this->users->get($id)))
		{
			set_alert(__('lang_ERROR_ACCOUNT_MISSING'), 'error');
		}

		// Successfully activated?
		elseif (0 == $user->enabled && false !== $user->update('enabled', 1))
		{
			set_alert(sprintf(__('lang_success_activate_%s'), $user->username), 'success');
		}

		// An error occurred somewhere?
		else
		{
			set_alert(sprintf(__('lang_error_activate_%s'), $user->username), 'error');
		}

		redirect($this->redirect);
		exit;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for deactivating the given user.
	 *
	 * @access 	protected
	 * @param 	int 	$id 	The user's ID.
	 * @return 	void
	 */
	protected function _deactivate($id)
	{
		// No action done on own account.
		if ($id == $this->c_user->id)
		{
			set_alert(__('lang_error_deactivate_OWN'), 'error');
		}

		// Make sure the user exists.
		elseif (false === ($user = $this->users->get($id)))
		{
			set_alert(__('lang_ERROR_ACCOUNT_MISSING'), 'error');
		}

		// Successfully activated?
		elseif (1 == $user->enabled && false !== $user->update('enabled', 0))
		{
			set_alert(sprintf(__('lang_success_deactivate_%s'), $user->username), 'success');
		}

		// An error occurred somewhere?
		else
		{
			set_alert(sprintf(__('lang_error_deactivate_%s'), $user->username), 'error');
		}

		redirect($this->redirect);
		exit;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for deleting the given user.
	 *
	 * @access 	protected
	 * @param 	int 	$id 	The user's ID.
	 * @return 	void
	 */
	protected function _delete($id)
	{
		// No action done on own account.
		if ($id == $this->c_user->id)
		{
			set_alert(__('lang_error_delete_OWN'), 'error');
		}

		// Make sure the user exists.
		elseif (false === ($user = $this->users->get($id)))
		{
			set_alert(__('lang_ERROR_ACCOUNT_MISSING'), 'error');
		}

		// Successfully activated?
		elseif (0 == $user->deleted && false !== $this->users->delete($id))
		{
			set_alert(sprintf(__('lang_success_delete_%s'), $user->username), 'success');
		}

		// An error occurred somewhere?
		else
		{
			set_alert(sprintf(__('lang_error_delete_%s'), $user->username), 'error');
		}

		redirect($this->redirect);
		exit;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for restoring the given user.
	 *
	 * @access 	protected
	 * @param 	int 	$id 	The user's ID.
	 * @return 	void
	 */
	protected function _restore($id)
	{
		// No action done on own account.
		if ($id == $this->c_user->id)
		{
			set_alert(__('lang_error_restore_own'), 'error');
		}

		// Make sure the user exists.
		elseif (false === ($user = $this->users->get($id)))
		{
			set_alert(__('lang_error_account_missing'), 'error');
		}

		// Successfully activated?
		elseif (1 == $user->deleted && false !== $this->users->restore($id))
		{
			set_alert(sprintf(__('lang_success_restore_%s'), $user->username), 'success');
		}

		// An error occurred somewhere?
		else
		{
			set_alert(sprintf(__('lang_error_restore_%s'), $user->username), 'error');
		}

		redirect($this->redirect);
		exit;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for permanently delete the given user.
	 *
	 * @access 	protected
	 * @param 	int 	$id 	The user's ID.
	 * @return 	void
	 */
	protected function _remove($id)
	{
		// No action done on own account.
		if ($id == $this->c_user->id)
		{
			set_alert(__('lang_error_remove_own'), 'error');
		}

		// Make sure the user exists.
		elseif (false === ($user = $this->users->get($id)))
		{
			set_alert(__('lang_error_account_missing'), 'error');
		}

		// Successfully activated?
		elseif (false !== $this->users->remove($id))
		{
			set_alert(sprintf(__('lang_success_remove_%s'), $user->username), 'success');
		}

		// An error occurred somewhere?
		else
		{
			set_alert(sprintf(__('lang_error_remove_%s'), $user->username), 'error');
		}

		redirect($this->redirect);
		exit;
	}

	// ------------------------------------------------------------------------
	// Private methods.
	// ------------------------------------------------------------------------

	/**
	 * Admin subheading.
	 * @access 	protected
	 * @param 	none
	 * @return	 void
	 */
	protected function _subhead()
	{
		if ('index' !== $this->router->fetch_method())
		{
			add_action('admin_subhead', function () {
				$this->_btn_back();
			});
			return;
		}

		add_action('admin_subhead', function () {

			// Add user button.
			echo html_tag('a', array(
				'href' => admin_url('users/add'),
				'class' => 'btn btn-success btn-sm btn-icon mr-2'
			), fa_icon('plus-circle').__('lang_add_user'));

		});
	}

	// ------------------------------------------------------------------------

	/**
	 * _reports_admin_head
	 *
	 * Add some JS lines.
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	string
	 */
	public function _admin_head($output)
	{
		// Confirmation messages.
		$lines = deep_htmlentities(array(
			'activate'   => __('confirm_activate'),
			'deactivate' => __('confirm_deactivate'),
			'delete'     => __('confirm_delete'),
			'restore'    => __('confirm_RESTORE'),
			'remove'     => __('confirm_REMOVE'),
		), ENT_QUOTES, 'UTF-8');

		$output .= '<script type="text/javascript">';
		$output .= 'cg.i18n.users = '.json_encode($lines).';';
		$output .= '</script>';

		return $output;
	}
}