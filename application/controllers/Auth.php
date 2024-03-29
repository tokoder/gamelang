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
 * Auth Controller Class
 *
 * It handles users registration, activation,
 * authentication and password management.
 *
 * @subpackage 	Controllers
 * @author		Tokoder Team
 */
class Auth extends CG_Controller
{
    /**
	 * __construct
	 *
	 * Simply call parent's constructor and allow access to logout method
	 * only for already logged-in users.
     */
    public function __construct()
    {
		parent::__construct();

		// pastikan user belum login
		if ($this->auth->online()
			&& 'logout' !== $this->router->fetch_method())
		{
			set_alert(__('You are already logged in'), 'error');
			redirect('');
			exit;
		}
    }

	// ------------------------------------------------------------------------

	/**
	 * We are remapping things just so we can handle methods that are
	 * http accessed.
	 */
	public function _remap($method, $params = array())
	{
		// set layouts.
		$this->themes->set_theme()->set_layout('auth');

		/**
		 * If the view does not exists within the theme's folder,
		 * we make sure to use our default one.
		 */
		if (false == $this->themes->view_exists())
		{
			remove_all_actions('extra_head');

			// Enqueue our assets.
			add_action( 'extra_head', function ($output) {
				$config = array(
					'site_url'     => site_url(),
					'token_name'   => config_item('csrf_token_name'),
					'token_cookie' => config_item('csrf_cookie_name'),
				);
				$output .= '<script type="text/javascript">';
				$output .= 'var cg = window.cg = window.cg || {};';
				$output .= 'cg.config = '.json_encode($config).';';
				$output .= '</script>';
				return $output;
			});

			remove_all_actions('after_theme_setup');

			// Enqueue our assets.
			add_action( 'after_theme_setup', function () {
				add_styles('assets/vendor/bootstrap/css/bootstrap.css');
				add_styles('assets/vendor/fontawesome-free/css/all.css');
				add_styles(get_theme_path('style.css'));

				add_script('assets/vendor/jquery/jquery.js');
				add_script('assets/vendor/bootstrap/js/bootstrap.bundle.js');
			});

			remove_all_actions('enqueue_partials');

			// Fixed views and layouts if theme doesn't have theme.
			add_filter('theme_layouts_path', function($path) {
				return VIEWPATH.'auth/layouts';
			});

			add_filter( 'theme_partials_path', function () {
				return VIEWPATH.'auth/partials';
			});

			add_filter('theme_views_path', function($path) {
				return VIEWPATH;
			});
		}

		// Call the method.
		return call_user_func_array(array($this, $method), $params);
	}

	// ------------------------------------------------------------------------

	/**
	 * index
	 *
	 * Method kept as a backup only, it does absolutely nothing.]
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function index() {}

	// ------------------------------------------------------------------------
	// Account management methods.
	// ------------------------------------------------------------------------

	/**
	 * Method for users registration on the site.
	 *
	 * @return void
	 */
	public function register()
	{
		// Are registrations allowed?
		if (true !== get_option('allow_registration', false))
		{
			redirect('');
			exit;
		}

		// Prepare form validation and form helper.
		$rules = array(
			array(	'field' => 'identity',
					'label' => 'lang:lang_identity',
					'rules' => 'trim|required|max_length[32]'),
			array(	'field' => 'email',
					'label' => 'lang:lang_email',
					'rules' => 'trim|required|valid_email|unique_email'),
			array(	'field' => 'password',
					'label' => 'lang:lang_password',
					'rules' => 'trim|required|min_length[8]|max_length[20]'),
			array(	'field' => 'cpassword',
					'label' => 'lang:confirm_password',
					'rules' => 'trim|required|min_length[8]|max_length[20]|matches[password]'),
		);

		if (get_option('use_captcha', false) === true)
		{
			$this->load->helper('captcha');
			$rules[] = array(
				'field' => 'captcha',
				'label' => 'lang:lang_captcha',
				'rules' => 'trim|required|check_captcha'
			);
		}

		$this->prep_form(apply_filters('register-validation-data', $rules), '#register');

		// After the form is processed.
		if ($this->form_validation->run() !== false)
		{
			if (true !== check_nonce('user-register'))
			{
				set_alert(__('This form did not pass our security controls'), 'error');
				redirect('register', 'refresh');
				exit;
			}

			// Attempt to register the user.
			$this->users->register($this->input->post(array(
                'identity',
                'email',
                'password',
            ), true));

            // Redirect back to registration page.
			redirect('login', 'refresh');
			exit;
		}

		// Prepare form fields.
		$_defaults = array(
			'identity',
			'email',
			'password',
			'cpassword',
		);
		foreach ($_defaults as $field)
		{
			$name = $field;
			$inputs[$name] = array_merge(
				$this->config->item($name, 'inputs'),
				array( 'value' => set_value($name, $this->input->post($name, false)) )
			);
		}

		// Let's now add our generated inputs to view.
		$this->data['inputs'] = $inputs;

		// Set page title and render view.
		$this->themes
			->set_title(__('lang_register'))
			->set_alert($this->form_validation->validation_errors_list(), 'error')
			->render($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * activate
	 *
	 * Method for activating a user by the given activation code.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function activate()
	{
		$code = $this->input->get('code', true);

		// No code provided? Safely redirect to homepage.
		if (empty($code))
		{
			redirect('');
			exit;
		}

		// Successfully enabled?
		if (false !== $this->users->activate_by_code($code))
		{
			redirect('login');
			exit;
		}

		// Otherwise, simply redirect to homepage.
		redirect('');
		exit;
	}

	// ------------------------------------------------------------------------

	/**
	 * resend
	 *
	 * Method for resend account activation links.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function resend()
	{
		// Are registrations allowed?
		if (true !== get_option('allow_registration', false))
		{
			redirect('');
			exit;
		}

		// Prepare form validation and rules.
		$rules[] = array(
			'field' => 'identity',
			'label' => 'lang:lang_identity',
			'rules' => 'trim|required|min_length[5]'
		);

		if (get_option('use_captcha', false) === true)
		{
			$this->load->helper('captcha');
			$rules[] = array(
				'field' => 'captcha',
				'label' => 'lang:lang_captcha',
				'rules' => 'trim|required|check_captcha'
			);
		}

		$this->prep_form(apply_filters('resend-validation-data', $rules), '#resend');

		// After form processing.
		if ($this->form_validation->run() !== false)
		{
			if (true !== check_nonce('user-resend-link'))
			{
				set_alert(__('This form did not pass our security controls'), 'error');
				redirect('resend-link', 'refresh');
				exit;
			}

			// Attempt to resend activation link.
			$this->users->resend_link($this->input->post('identity', true));

			// Redirect back to the same page.
			redirect('resend-link', 'refresh');
			exit;
		}

		// Prepare form fields.
		$this->data['identity'] = array_merge(
			$this->config->item('identity', 'inputs'),
			array('value' => set_value('identity'))
		);

		// Set page title and render view.
		$this->themes
			->set_title(__('lang_RESEND_LINK'))
			->render($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * restore
	 *
	 * Method for restoring a previously soft-deleted account.
	 */
	public function restore()
	{
		// Prepare form validation.
		$rules[] = array(
			'field' => 'identity',
			'label' => 'lang:lang_identity',
			'rules' => 'trim|required|min_length[5]|max_length[32]'
		);
		$rules[] = array(
			'field' => 'password',
			'label' => 'lang:lang_password',
			'rules' => 'trim|required|min_length[8]|max_length[20]'
		);

		if (get_option('use_captcha', false) === true)
		{
			$this->load->helper('captcha');
			$rules[] = array(
				'field' => 'captcha',
				'label' => 'lang:lang_captcha',
				'rules' => 'trim|required|check_captcha'
			);
		}

		$this->prep_form(apply_filters('restore-validation-data', $rules), '#restore');

		// After form processing.
		if ($this->form_validation->run() !== false)
		{
			// Check CSRF token.
			if ( ! check_nonce('user-restore'))
			{
				set_alert(__('This form did not pass our security controls'), 'error');
				redirect('restore-account', 'refresh');
				exit;
			}

			// Attempt to restore the account.
			$status = $this->users->restore_account(
				$this->input->post('identity', true),
				$this->input->post('password', true)
			);

			// The redirection depends on the restore status.
			redirect((false !== $status ? '' : 'restore-account'), 'refresh');
			exit;
		}

		// Prepare form fields.
		$this->data['password'] = $this->config->item('password', 'inputs');
		$this->data['identity'] = array_merge(
			$this->config->item('identity', 'inputs'),
			array('value' => set_value('identity'))
		);

		// Set page title and render view.
		$this->themes
			->set_title(__('lang_RESTORE_ACCOUNT'))
			->render($this->data);
	}

	// ------------------------------------------------------------------------
	// Authentication methods.
	// ------------------------------------------------------------------------

	/**
	 * login
	 *
	 * Method for site's members login.
	 */
	public function login()
	{
		// Prepare empty validation rules.
		$rules = array();

		// What type of login to use?
		$login_type = get_option('login_type', 'both');
		switch ($login_type)
		{
			case 'username':
				$login_type = 'username';
				$rules[]    = array(
					'field' => 'username',
					'label' => 'lang:lang_username',
					'rules' => 'trim|required|min_length[5]|max_length[32]|user_exists'
				);
				break;

			case 'email':
				$login_type = 'email';
				$rules[]    = array(
					'field' => 'email',
					'label' => 'lang:lang_email_address',
					'rules' => 'trim|required|valid_email|user_exists'
				);
				break;

			case 'both':
			default:
				$login_type = 'identity';
				$rules[]    = array(
					'field' => 'identity',
					'label' => 'lang:lang_identity',
					'rules' => 'trim|required|min_length[5]|user_exists'
				);
				break;
		}

		// Add password.
		$rules[] = array(
			'field' => 'password',
			'label' => 'lang:lang_password',
			'rules' => "trim|required|min_length[8]|max_length[20]"
		);

		if (get_option('use_captcha', false) === true)
		{
			$this->load->helper('captcha');
			$rules[] = array(
				'field' => 'captcha',
				'label' => 'lang:lang_captcha',
				'rules' => 'trim|required|check_captcha'
			);
		}

		// Prepare form validation and pass rules.
		$this->prep_form(apply_filters('login-validation-data', $rules), '#login');

		// After form processing!
		if ($this->form_validation->run() !== false)
		{
			if (true !== check_nonce('user-login'))
			{
				set_alert(__('This form did not pass our security controls'), 'error');
				redirect('login');
				exit;
			}

			// Attempt to login the current user.
			$status = $this->auth->login(
				$this->input->post($login_type, true),
				$this->input->post('password', true),
				$this->input->post('remember') == '1'
			);

			// Success? Redirect to homepage, else, back to login page.
			redirect(($status === true ? $this->redirect : 'login'), 'refresh');
			exit;
		}

		// Prepare form fields.
		$this->data['password'] = $this->config->item('password', 'inputs');
		$this->data['login_type'] = $login_type;
		$this->data['login'] = array_merge(
			$this->config->item($login_type, 'inputs'),
			array('value' => set_value($login_type))
		);

		// Set page title and render view.
		$this->themes
			->set_title(__('lang_login'))
			->render($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * logout
	 *
	 * Method for logging out already logged in users.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function logout()
	{
		// Logout the current user.
		$this->auth->logout();

		// Redirect the user to homepage.
		redirect('');
		exit;
	}

	// ------------------------------------------------------------------------
	// Password management methods.
	// ------------------------------------------------------------------------

	/**
	 * forgot
	 *
	 * Method for requesting a password reset link.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function forgot()
	{
		// Prepare form validation and rules.
		$rules[] = array(
			'field' => 'email',
			'label' => 'lang:lang_email_address',
			'rules' => 'trim|required|min_length[5]|user_exists'
		);

		if (get_option('use_captcha', false) === true)
		{
			$this->load->helper('captcha');
			$rules[] = array(
				'field' => 'captcha',
				'label' => 'lang:lang_captcha',
				'rules' => 'trim|required|check_captcha'
			);
		}

		$this->prep_form(apply_filters('forgot-validation-data', $rules), '#forgot');

		// After the form is processed.
		if ($this->form_validation->run() !== false)
		{
			// Check nonce.
			if (true !== check_nonce('user-lost-password'))
			{
				set_alert(__('This form did not pass our security controls'), 'error');
				redirect('lost-password', 'refresh');
				exit;
			}

			// Attempt to prepare password reset.
			$this->users->prep_password_reset($this->input->post('email', true));

			// Redirect back to the same page.
			redirect('lost-password', 'refresh');
			exit;
		}

		// Prepare form fields.
		$this->data['email'] = array_merge(
			$this->config->item('email', 'inputs'),
			array('value' => set_value('email'))
		);

		// Set page title and render view.
		$this->themes
			->set_title(__('lost_password'))
			->render($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * reset
	 *
	 * Method for resetting account's password.
	 *
	 * @access 	public
	 * @param 	void
	 * @return 	void
	 */
	public function reset()
	{
		$code = $this->input->get('code', true);

		// No code provided? Safely redirect to homepage.
		if (empty($code))
		{
			redirect('');
			exit;
		}

		// The code is invalid?
		if (false === ($guid = $this->users->check_password_code($code)))
		{
			set_alert(__('This password reset link is no longer valid'), 'error');
			redirect('');
			exit;
		}

		// Prepare form validation and rules.
		$this->prep_form(array(
			array(	'field' => 'npassword',
					'label' => 'lang:lang_new_password',
					'rules' => 'trim|required|min_length[8]|max_length[20]'),
			array(	'field' => 'cpassword',
					'label' => 'lang:confirm_password',
					'rules' => 'trim|required|min_length[8]|max_length[20]|matches[npassword]'),
		), '#reset');

		// After the form is processed.
		if ($this->form_validation->run() !== false)
		{
			// Check nonce.
			if (true !== check_nonce('user-reset-password_'.$code, false))
			{
				set_alert(__('This form did not pass our security controls'), 'error');
				redirect('reset-password?code='.$code);
				exit;
			}

			// Attempt to reset password.
			$status = $this->users->reset_password(
				$guid,
				$this->input->post('npassword', true)
			);

			// The redirection depends on the process status.
			redirect(($status === true ? 'login' : 'reset-password?code='.$code), 'refresh');
			exit;
		}

		// Prepare form fields.
		$this->data['code'] = $code;
		$this->data['npassword'] = $this->config->item('npassword', 'inputs');
		$this->data['cpassword'] = $this->config->item('cpassword', 'inputs');

		// Set page title and render view.
		$this->themes
			->set_title(__('reset_password'))
			->render($this->data);
	}
}