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
 * CG_Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @subpackage 	Core
 * @author		Tokoder Team
 */
class CG_Controller extends CI_Controller
{
	/**
	 * Holds the current user's object.
	 * @var object
	 */
	protected $c_user;

	/**
	 * Holds the redirection URL.
	 * @var string
	 */
	protected $redirect = '';

	/**
	 * Array of data to pass to views.
	 * @var array
	 */
	protected $data = array();

	/**
	 * Class constructor
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		/**
		 * We make sure to always store $_POST data upon submission. This
		 * is useful if we want to re-fill form inputs.
		 */
		if ($this->input->is_post_request())
		{
			$this->session->set_flashdata('old_post_data', $this->input->post());
		}

		// Get current package's details.
		$package = $this->router->fetch_package();
		empty($package) OR $package = $this->router->package_details($package);

		// In case the package is disabled.
		if (null !== $package
			&& ( ! isset($package['enabled']) OR true !== $package['enabled']))
		{
			if (false === stripos($this->uri->uri_string(), $this->router->default_controller))
			{
				set_alert(__('This component is disabled. Enable it on the dashboard in order to use it'), 'error');
				redirect(($this->router->is_admin() ? config_item('site_admin') : ''));
				exit;
			}

			show_error(__('This component is disabled. Enable it on the dashboard in order to use it'));
		}
		/**
		 * If the "manifest.json" file is missing or badly formatted,
		 * $package will be set to "FALSE". So we have two options:
		 * 1. Redirect the user to homepage.
		 * 2. Show error, this is in case the package is used as the
		 * default controller.
		 */
		elseif (false === $package)
		{
			if (false === stripos($this->uri->uri_string(), $this->router->default_controller))
			{
				set_alert(__('This component\'s "manifest.json" file is either missing or badly formatted'), 'error');
				redirect('');
				exit;
			}

			show_error(__('This component\'s "manifest.json" file is either missing or badly formatted'));
		}
		$this->themes->set('package', $package, true);

		// Add all necessary meta tags.
		$this->themes->set_meta();

		// Load authentication library.
		$this->c_user = $this->auth->user();
		$this->themes->set('c_user', $this->c_user, true);

		// Always hold the redirection URL for eventual use.
		if ($this->input->get_post('next'))
		{
			$this->session->set_flashdata('redirect',
				rawurldecode($this->input->get_post('next'))
			);
		}
		$this->redirect = $this->session->flashdata('redirect');

		log_message('info', 'CG_Controller Class Initialized');
	}

	// ------------------------------------------------------------------------

	/**
	 * We are remapping things just so we can handle methods that are
	 * http accessed.
	 *
	 * @access 	public
	 * @param 	string 	$method 	The method's name.
	 * @param 	array 	$params 	Arguments to pass to the method.
	 * @return 	mixed 	Depends on the called method.
	 */
	public function _remap($method, $params = array())
	{
		// The method is not found? Nothing to do.
		if ( ! method_exists($this, $method))
		{
			show_404();
		}

		// Add a class to body class if the user is logged in.
		if (true === $this->auth->online())
		{
			$this->themes->set_body_class(['logged-in']);
		}

		// Call the method.
		return call_user_func_array(array($this, $method), $params);
	}

	// ------------------------------------------------------------------------

	/**
	 * prep_form
	 *
	 * Method for preparing form validation library with optional rules to
	 * apply and whether to use jQuery.
	 *
	 * @access 	protected
	 * @param 	array
	 * @param 	string 	$form 		jQuery selector.
	 * @param 	string 	$filter 	String appended to filtered parameters.
	 * @return 	void
	 */
	public function prep_form($rules = array(), $form = null, $filter = null)
	{
		// Load form validation library if not loaded.
		if ( ! class_exists('CI_Form_validation', false))
		{
			$this->load->library('form_validation');
		}

		// Load inputs config file.
		$this->load->config('inputs', true);

		// Are there any rules to apply?
		if (is_array($rules) && ! empty($rules))
		{
			// Set CI validation rules first.
			$this->form_validation->set_rules($rules);
			// Use jQuery validation?
			if (null !== $form)
			{
				// Make sure to use _query_validate() method on admin.
				add_action( 'after_theme_setup', function () {
					add_script('assets/vendor/jquery-validation/jquery.validate.js');
					add_script('assets/vendor/jquery-validation/additional-methods.js');
				} );

				// Different language?
				$code = $this->lang->lang_detail('code');
				if ('en' !== $code)
				{
					add_action( 'after_theme_setup', function () use ($code) {
						add_script('assets/vendor/jquery-validation/localization/messages_'.$code.'.js');
					} );
				}

				$this->validation->set_rules($rules);

				// we build the final jQuery validation output.
				$this->themes->add_inline('js', $this->validation->run($form, $filter));
			}
		}
	}
}

// -----------------------------------------------------------------------------

/**
 * CG_Controller_Admin Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @subpackage 	Core
 * @author		Tokoder Team
 */
class CG_Controller_Admin extends CG_Controller
{
	// Constructor
	public function __construct()
	{
		parent::__construct();

		$this->auth->check_permission('admin_panel');
	}

	// ------------------------------------------------------------------------

	/**
	 * We remap methods so we can do extra actions when we are not on methods
	 * that required AJAX requests.
	 *
	 * @access 	public
	 * @param 	string 	$method 	The method's name.
	 * @param 	array 	$params 	Arguments to pass to the method.
	 * @return 	mixed 	Depends on the called method.
	 */
	public function _remap($method, $params = array())
	{
		// The method is not found? Nothing to do.
		if ( ! method_exists($this, $method))
		{
			show_404();
		}

		if ($this->input->is_ajax_request())
		{
			return parent::_remap($method, $params);
		}

		/**
		 * Admin menu is called only of method that load views.
		 */
		$this->_admin_menu();

		// If we have a heading method, use it.
		method_exists($this, '_subhead') && $this->_subhead();

		/**
		 * We set global variables so they can be found by dashboard partials views.
		 */
		if ( ! empty($this->data))
		{
			// Then we make all variables global.
			foreach ($this->data as $key => $val)
			{
				$this->load->vars($key, $val);
			}
		}

		// We call the method.
		return call_user_func_array(array($this, $method), $params);
	}

	// ------------------------------------------------------------------------
	// Private Methods.
	// ------------------------------------------------------------------------

	/**
	 * Prepare dashboard sidebar menu.
	 * @access 	public
	 * @param 	none
	 * @return 	array
	 */
	protected function _admin_menu()
	{
		$ignored_contexts = array('admin', 'users', 'settings');
		$packages = $this->router->list_packages(true);

		if ( ! $packages)
		{
			return;
		}

		foreach ($packages as $folder => $package)
		{
			// we make sure the package is enabled!
			if ( ! $package['enabled'])
			{
				continue;
			}

			foreach ($package['contexts'] as $context => $status)
			{
				// No context? Ignore it.
				if (false === $status
					OR in_array($folder, $ignored_contexts)
					OR ! user_permission($package['folder']))
				{
					continue;
				}

				// Add other context.
				add_filter("_admin_menu", function($admin) use ($package, $context)
				{
					$uri = $package['folder'];
					('admin' !== $context) && $uri = $context.'/'.$uri;

					$title_line = isset($package[$context.'_menu'])
						? $context.'_menu'
						: 'admin_menu';

					// Translation present?
					$title = (isset($package[$title_line])
						&& 1 === sscanf($package[$title_line], 'lang:%s', $line))
						? __($line)
						: ucwords(str_replace('-', ' ', $package[$title_line]));

					$admin[] = array(
						'parent'     => "_{$context}_menu",
						'order'      => $package['admin_order'],
						'id'         => "_{$context}_".url_title(strtolower($title)),
						'permission' => $package['folder'],
						'slug'       => admin_url($uri),
						'name'       => $title,
					);

					return $admin;
				});
			}
		}
	}

	/**
	 * _btn_back
	 *
	 * Method for creating a back to packages main page.
	 *
	 * @access 	public
	 * @param 	int 	$limit 	The limit to create back link for.
	 * @param 	bool 	$return 		Whether to echo the anchor.
	 * @return 	string
	 */
	protected function _btn_back($package = null, $label = false, $return = false)
	{
		if (null === $package)
		{
			$package = empty($this->uri->segment(3))
				? $this->uri->segment(2)
				: $this->uri->segment(3);
		}

		// Direction of the icon depends on the language.
		$icon = 'arrow-'.('rtl' === $this->lang->lang_detail('direction') ? 'right' : 'left').'-long';

		$anchor = html_tag('a', array(
			'href' => admin_url($package),
			'class' => 'btn btn-outline-dark btn-sm btn-icon me-2',
		), fa_icon($icon).($label ? $label : __('lang_back')));

		if (false === $return)
		{
			echo $anchor;
		}

		return $anchor;
	}
}

// -----------------------------------------------------------------------------

/**
 * CG_Controller_User Class
 *
 * Controllers extending this class require a logged in user.
 *
 * @subpackage 	Core
 * @author		Tokoder Team
 */
class CG_Controller_User extends CG_Controller
{
	/**
	 * Class constructor
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->check_permission();
	}
}

// -----------------------------------------------------------------------------

/**
 * CG_Controller_Ajax Class
 *
 * @subpackage 	Core
 * @author		Tokoder Team
 */
class CG_Controller_Ajax extends CG_Controller
{
	/**
	 * Array of methods that require a logged-in user and
	 * a safe URL with check.
	 * @var array
	 */
	protected $safe_methods = array();

	/**
	 * Array of methods that require a logged-in user of rank admin.
	 * @var array
	 */
	protected $admin_methods = array();

	/**
	 * Array of methods that require a logged-in user of rank admin and
	 * a safe URL check.
	 * @var array
	 */
	protected $safe_admin_methods = array();

	/**
	 * Used by AJAX methods to hold response details.
	 * @var object
	 */
	protected $response;

	/**
	 * List of HTTP status codes.
	 */
	const HTTP_OK                    = 200;
	const HTTP_CREATED               = 201;
	const HTTP_NO_CONTENT            = 204;
	const HTTP_NOT_MODIFIED          = 304;
	const HTTP_BAD_REQUEST           = 400;
	const HTTP_UNAUTHORIZED          = 401;
	const HTTP_FORBIDDEN             = 403;
	const HTTP_NOT_FOUND             = 404;
	const HTTP_METHOD_NOT_ALLOWED    = 405;
	const HTTP_NOT_ACCEPTABLE        = 406;
	const HTTP_CONFLICT              = 409;
	const HTTP_INTERNAL_SERVER_ERROR = 500;
	const HTTP_NOT_IMPLEMENTED       = 501;

	/**
	 * Array of most used HTTP status codes and their message.
	 * @var array
	 */
    protected $http_status_codes = array(
		self::HTTP_OK                    => 'OK',
		self::HTTP_CREATED               => 'CREATED',
		self::HTTP_NO_CONTENT            => 'NO CONTENT',
		self::HTTP_NOT_MODIFIED          => 'NOT MODIFIED',
		self::HTTP_BAD_REQUEST           => 'BAD REQUEST',
		self::HTTP_UNAUTHORIZED          => 'UNAUTHORIZED',
		self::HTTP_FORBIDDEN             => 'FORBIDDEN',
		self::HTTP_NOT_FOUND             => 'NOT FOUND',
		self::HTTP_METHOD_NOT_ALLOWED    => 'METHOD NOT ALLOWED',
		self::HTTP_NOT_ACCEPTABLE        => 'NOT ACCEPTABLE',
		self::HTTP_CONFLICT              => 'CONFLICT',
		self::HTTP_INTERNAL_SERVER_ERROR => 'INTERNAL SERVER ERROR',
		self::HTTP_NOT_IMPLEMENTED       => 'NOT IMPLEMENTED'
    );

	// ------------------------------------------------------------------------

	/**
	 * Class constructor.
	 * @access 	public
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		// We make sure the request is AJAX.
		if ( ! $this->input->is_ajax_request())
		{
			show_404();
		}

		// Prepare $response property object.
		$this->response          = new stdClass();
		$this->response->header  = self::HTTP_BAD_REQUEST;
		$this->response->message = '';
		$this->response->scripts = array();
		$this->response->results = array();
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for catching called methods to check safety and integrity.
	 * @access 	public
	 * @param 	string 	$method 	The requested method.
	 * @param 	array 	$params 	Arguments to pass to the method.
	 * @return 	AJAX_Controller::response().
	 */
	public function _remap($method, $params = array())
	{
		// The method does not exist?
		if ( ! method_exists($this, $method))
		{
			$this->response->header  = self::HTTP_NOT_FOUND;
			return $this->response();
		}

		/**
		 * If the method is present in both $safe_methods and
		 * $admin_methods array we make sure to automatically
		 * add it to $safe_admin_methods array.
		 */
		if (in_array($method, $this->safe_methods)
			&& in_array($method, $this->admin_methods))
		{
			$this->safe_admin_methods[] = $method;
		}

		/**
		 * The reason behind this is that sometime we don't need to create
		 * the referrer field. So we see if one is provided. If it is not,
		 * we simply check the nonce without referrer.
		 */
		$referrer = $this->input->request('_http_referrer');
		$nonce_status = (null !== $referrer)
			? check_nonce()
			: check_nonce(null, false);

		// Does the requested methods require a safety check?
		if (in_array($method, $this->safe_methods)
			&& (true !== $nonce_status OR true !== $this->auth->online()))
		{
			$this->response->header  = self::HTTP_UNAUTHORIZED;
			$this->response->message = __('This action did not pass our security controls');
		}
		// Does the method require an admin user?
		elseif (in_array($method, $this->admin_methods)
			&& true !== $this->auth->is_admin())
		{
			$this->response->header  = self::HTTP_UNAUTHORIZED;
			$this->response->message = __('This action did not pass our security controls');
		}
		// Does the method require an admin user AND a safety check?
		elseif (in_array($method, $this->safe_admin_methods)
			&& (true !== $nonce_status OR true !== $this->auth->is_admin()))
		{
			$this->response->header  = self::HTTP_UNAUTHORIZED;
			$this->response->message = __('This action did not pass our security controls');
		}
		// Otherwise, call the method.
		else
		{
			call_user_func_array(array($this, $method), $params);
		}

		// Always return the final response.
		return $this->response();
	}

	// ------------------------------------------------------------------------

	/**
	 * This method handles the rest of AJAX requests.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	protected function response()
	{
		/**
		 * Disable parsing of the {elapsed_time} and {memory_usage}
		 * pseudo-variables because we don't need them.
		 */
		$this->output->parse_exec_vars = false;

		$response['code'] = $this->response->header;

		$response['status'] = (isset($this->response->status))
			? $this->response->status
			: $this->http_status_codes[$this->response->header];

		if (isset($this->response->message))
		{
			$response['message'] = $this->response->message;
		}

		if ( ! empty($this->response->scripts))
		{
			$response['scripts'] = $this->response->scripts;
		}

		if ( ! empty($this->response->results))
		{
			$response['results'] = $this->response->results;
		}

		// Return the final output.
		return $this->output
			->set_content_type('json')
			->set_status_header($this->response->header)
			->set_output(json_encode($response));
	}
}