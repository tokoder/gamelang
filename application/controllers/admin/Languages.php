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
 * Language - Admin Controller
 *
 * Allow administrators to manage site's languages.
 *
 * @subpackage 	Admin
 * @author		Tokoder Team
 */
class Languages extends CG_Controller_Admin
{
	/**
	 * __construct
	 *
	 * We simply call parent's constructor, load language file
	 * and we make sure to load our languages JS file.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		// Default page title and icon.
		$this->data['page_icon']  = 'globe';
		$this->data['page_title'] = __('lang_languages');

		// Add our head string.
		add_filter('admin_head', array($this, '_admin_head'));
	}

	// ------------------------------------------------------------------------

	/**
	 * index
	 *
	 * Method for displaying the list of available site languages.
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		// Get site's current language.
		$this->data['language'] = $this->options->item('language');

		// Get site's available languages.
		$this->data['available_languages'] = $this->config->item('languages') ?: array();

		// Get all languages details.
		$this->data['languages'] = $this->lang->lang_lists();
		ksort($this->data['languages']);

		/**
		 * Languages actions.
		 */
		$action = $this->input->get('action', true);
		$lang   = $this->input->get('lang', true);

		if (($action && in_array($action, array('enable', 'disable', 'make_default')))
			&& check_nonce_url("language-{$action}_{$lang}")
			&& method_exists($this, '_'.$action))
		{
			return call_user_func_array(array($this, '_'.$action), array($lang));
		}

		/**
		 * We check if the language folder is available or not and set
		 * it to available if found. This way we avoid installing languages
		 * that are not really available.
		 */
		foreach ($this->data['languages'] as $folder => &$l)
		{
			// Language availability.
			$l['available'] =  (is_dir(APPPATH.'language/'.$folder));

			// Language action.
			$l['action'] = null; // Ignore english.
			if ('indonesia' !== $folder)
			{
				$l['action'] = (in_array($folder, $this->data['available_languages'])) ? 'disable' : 'enable';
			}

			// Action buttons.
			$l['actions'] = array();

			/**
			 * No actions is available on "English" language except making
			 * it the language by default if it is not.
			 */
			if ('indonesia' === $folder)
			{

				// Not by default? Display the "Make Default" button.
				if ('indonesia' !== $this->data['language'])
				{
					$l['actions'][] = html_tag('button', array(
						'type' => 'button',
						'data-endpoint' => esc_url(nonce_admin_url(
							'languages?action=make_default&amp;lang=indonesia',
							'language-make_default_indonesia'
						)),
						'class' => 'btn btn-primary btn-sm btn-icon language-default',
					), fa_icon('lock').__('lang_make_default'));
				}

				// Ignore the rest.
				continue;
			}

			// Make default action.
			if ($folder !== $this->data['language'])
			{
				if (true === $l['available'])
				{
					$l['actions'][] = html_tag('button', array(
						'type' => 'button',
						'data-endpoint' => esc_url(nonce_admin_url(
							"languages?action=make_default&amp;lang={$folder}",
							"language-make_default_{$folder}"
						)),
						'class' => 'btn btn-primary btn-sm btn-icon language-default',
					), fa_icon('lock').__('lang_make_default'));
				}
				else
				{
					$l['actions'][] = html_tag('button', array(
						'type'     => 'button',
						'class'    => 'btn btn-primary btn-sm btn-icon op-2',
						'disabled' => 'disabled',
					), fa_icon('lock').__('lang_make_default'));
				}
			}

			// Disable language action.
			if (in_array($folder, $this->data['available_languages']))
			{
				if (true === $l['available'])
				{
					$l['actions'][] = html_tag('button', array(
						'type' => 'button',
						'data-endpoint' => esc_url(nonce_admin_url(
							"languages?action=disable&amp;lang={$folder}",
							"language-disable_{$folder}"
						)),
						'class' => 'btn btn-warning btn-sm btn-icon language-disable',
					), fa_icon('times').__('lang_disable'));
				}
				else
				{
					$l['actions'][] = html_tag('button', array(
						'type'     => 'button',
						'class'    => 'btn btn-warning btn-sm btn-icon op-2',
						'disabled' => 'disabled',
					), fa_icon('times').__('lang_disable'));
				}
			}

			// Enable language action.
			else
			{
				if (true === $l['available'])
				{
					$l['actions'][] = html_tag('button', array(
						'type' => 'button',
						'data-endpoint' => esc_url(nonce_admin_url(
							"languages?action=enable&amp;lang={$folder}",
							"language-enable_{$folder}"
						)),
						'class' => 'btn btn-success btn-sm btn-icon language-enable',
					), fa_icon('check').__('lang_enable'));
				}
				else
				{
					$l['actions'][] = html_tag('button', array(
						'type'     => 'button',
						'class'    => 'btn btn-success btn-sm btn-icon op-2',
						'disabled' => 'disabled',
					), fa_icon('check').__('lang_enable'));
				}
			}
		}

		// Set page title and render view.
		$this->themes
			->set_title(__('lang_languages'))
			->render($this->data);
    }

	// ------------------------------------------------------------------------
	// Quick-access methods.
	// ------------------------------------------------------------------------

	/**
	 * Method for enabling the given language.
	 *
	 * @access 	protected
	 * @param 	string 	$folder
	 * @return 	void
	 */
	protected function _enable($folder)
	{
		// Make sure to lower the name.
		ctype_lower($folder) OR $folder = strtolower($folder);

		// We cannot touch "English" language.
		if ('indonesia' === $folder)
		{
			set_alert(__('lang_ERROR_ENGLISH_REQUIRED'), 'error');
			redirect(admin_url('languages'));
			exit;
		}

		// Get database languages for later use.
		$languages = $this->config->item('languages');
		$languages OR $languages = array();

		// Already enabled? Nothing to do..
		if (in_array($folder, $languages))
		{
			set_alert(__('lang_ALREADY_ENABLE'), 'error');
			redirect(admin_url('languages'));
			exit;
		}

		// Add language to languages array.
		$languages[] = $folder;
		asort($languages);
		$languages = array_values($languages);

		// Successfully updated?
		if (false !== $this->options->set_item('languages', $languages))
		{
			set_alert(__('lang_SUCCESS_ENABLE'), 'success');
			redirect(admin_url('languages'));
			exit;
		}

		set_alert(__('lang_ERROR_ENABLE'), 'error');
		redirect(admin_url('languages'));
		exit;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for disabling the given language.
	 *
	 * @access 	protected
	 * @param 	string 	$folder
	 * @return 	void
	 */
	protected function _disable($folder)
	{
		// Make sure to lower the name.
		ctype_lower($folder) OR $folder = strtolower($folder);

		// We cannot touch "English" language.
		if ('indonesia' === $folder)
		{
			set_alert(__('lang_ERROR_ENGLISH_REQUIRED'), 'error');
			redirect(admin_url('languages'));
			exit;
		}

		// Get database languages for later use.
		$languages = $this->config->item('languages');
		$languages OR $languages = array();

		// Already disabled? Nothing to do..
		if ( ! in_array($folder, $languages))
		{
			set_alert(__('lang_ALREADY_DISABLE'), 'error');
			redirect(admin_url('languages'));
			exit;
		}

		// Remove language from languages array.
		$languages[] = $folder;
		foreach ($languages as $i => $lang)
		{
			if ($lang === $folder)
			{
				unset($languages[$i]);
			}
		}
		asort($languages);
		$languages = array_values($languages);

		// Successfully updated?
		if (false !== $this->options->set_item('languages', $languages))
		{
			/**
			 * If the language is the site's default language, we make
			 * sure to set English as the default one.
			 */
			if ($folder === $this->options->item('language'))
			{
				$this->options->set_item('language', 'indonesia');
			}

			set_alert(__('lang_SUCCESS_DISABLE'), 'success');
			redirect(admin_url('languages'));
			exit;
		}

		set_alert(__('lang_ERROR_DISABLE'), 'error');
		redirect(admin_url('languages'));
		exit;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for making a language site's default.
	 *
	 * @access 	protected
	 * @param 	string 	$folder
	 * @return 	void
	 */
	protected function _make_default($folder)
	{
		// Make sure to lower the name.
		ctype_lower($folder) OR $folder = strtolower($folder);

		// Get database languages for later use.
		$languages = $this->config->item('languages');
		$languages OR $languages = array();

		// If the language is not enabled, we make sure to enable it first.
		if ( ! in_array($folder, $languages))
		{
			$languages[] = $folder;
			asort($languages);

			if (false === $this->options->set_item('languages', $languages))
			{
				set_alert(__('lang_ERROR_DEFAULT'), 'error');
				redirect(admin_url('languages'));
				exit;
			}
		}

		// Successfully changed?
		if (false !== $this->options->set_item('language', $folder))
		{
			// We setup the session.
			$this->session->set_userdata('language', $folder);

			// We update user's language if he/she is logged in.
			$user = $this->auth->user();
			$user->update('language', $folder);

			set_alert(__('lang_success_default'), 'success');
			redirect(admin_url('languages'));
			exit;
		}

		set_alert(__('lang_ERROR_DEFAULT'), 'error');
		redirect(admin_url('languages'));
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
	public function _subhead()
	{
		add_action('admin_subhead', function () {
			echo html_tag('span', array(), fa_icon('info-circle text-primary me-1').__('lang_tip'));
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
		$lines = array(
			'enable'       => __('confirm_enable'),
			'disable'      => __('confirm_disable'),
			'make_default' => __('confirm_default'),
		);

		$output .= '<script type="text/javascript">';
		$output .= 'gamelang.i18n = gamelang.i18n || {};';
		$output .= ' gamelang.i18n.languages = '.json_encode($lines).';';
		$output .= '</script>';
		return $output;
	}
}