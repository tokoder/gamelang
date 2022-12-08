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
 * Load Controller Class
 *
 * It handles render content and language
 *
 * @subpackage 	Controllers
 * @author		Tokoder Team
 */
class Resource extends CG_Controller
{
	/**
	 * __construct
	 *
	 * Simply call parent's constructor and make sure the request is only
	 * a GET request.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		// We make sure the request is a GET.
		if (true !== $this->input->is_get_request())
		{
			redirect('');
			exit;
		}
	}

	// -----------------------------------------------------------------------------

	public function _remap($method, $params = array())
	{
		if ( ! method_exists($this, $method)) {
			// Call the method.
			return call_user_func_array(array($this, 'index'), $params);
		}

		// Call the method.
		return call_user_func_array(array($this, $method), $params);
	}

	// -----------------------------------------------------------------------------

	public function index()
	{
		$file = array_slice($this->uri->segment_array(), 1);
		$file = implode('/', $file);

		if ($file == null ) {
			show_404();
		}

		$ext = pathinfo($file, PATHINFO_EXTENSION);

		// Temporary output.
		$out = '';

		// Hold the path to the file.
		$file_path = normalize_path(APPPATH.$file);

		// The file does not exist?
		if ( ! file_exists($file_path))
		{
			return $out;
		}

		$out .= file_get_contents($file_path);

		// Still no output?
		if ('' === $out)
		{
			exit();
		}

		// We render our final output.
		$this->output
			->set_content_type($ext)
			->set_output($out);
	}

	// ------------------------------------------------------------------------

	/**
	 * set_language
	 *
	 * Method for changing current site language.
	 *
	 * @access 	public
	 * @param 	string 	$folder 	The language's folder name;
	 * @return 	void
	 */
	public function language($folder = null)
	{
		// Prepare redirection.
		$redirect = ($this->input->get_post('next'))
			? $this->input->get_post('next')
			: $this->input->referrer('/', true);

		// No language set? Nothing to do.
		if (empty($folder))
		{
			redirect($redirect);
			exit;
		}

		// Language not available? Nothing to do.
		if (in_array($folder, $this->config->item('languages')))
		{
			// We setup the session.
			$this->session->set_userdata('language', $folder);

			// We update user's language if he/she is logged in.
			if (true === $this->auth->online())
			{
				$user = $this->auth->user();
				$user->update('language', $folder);
			}
		}

		redirect($redirect);
		exit;
	}

    // -----------------------------------------------------------------------------

	/**
	 * refresh_captcha.
	 */
	public function captcha()
	{
		/**
		 * Disable parsing of the {elapsed_time} and {memory_usage}
		 * pseudo-variables because we don't need them.
		 */
		$this->output->parse_exec_vars = false;

		$this->load->helper('captcha');
		$generate_captcha = generate_captcha();
		$response['results'] = base_url('captcha/'.$generate_captcha['filename']);

		// Return the final output.
		return $this->output
			->set_content_type('json')
			->set_status_header(200)
			->set_output(json_encode($response));
    }
}