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
 * Gamelang_themes Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class Gamelang_themes extends CI_Driver
{
	/**
	 * CodeIgniter copyright.
	 * @var string
	 */
	protected $CG_copyright =<<<EOT
	\n<!--copyright--><!--
	Website proudly powered by CodeIgniter Gamelang (https://github.com/tokoder).
	Project developed and maintained by Tokoder Team (https://tokoder.com).
	--><!--/copyright-->
	EOT;

	/**
	 * Header template
	 * @var string
	 */
	protected $template_header = <<<EOT
	{doctype}
	{CG_copyright}
	<html{html_class}{language_attributes}>
	<head>
		<meta charset="{charset}">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{title}</title>
		{meta_tags}
		{stylesheets}
		{extra_head}
	</head>
	<body{body_class}{body_attributes}>\n
	EOT;

	/**
	 * Footer template.
	 * @var string
	 */
	protected $template_footer = <<<EOT
		{javascripts}
		{analytics}
	</body>
	</html>
	EOT;

	/**
	 * Google analytics template.
	 * @var string
	 */
	protected $template_google_analytics = <<<EOT
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id={site_id}"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', '{site_id}',{
			page_path: window.location.pathname,
		});
	</script>
	EOT;

	/**
	 * Default alert message template to use
	 * as a fallback if none is provided.
	 */
	protected $template_alert = <<<EOT
	<div class="{class} alert-dismissible fade show" role="alert">
		{message}
		<button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	EOT;

	/**
	 * JavaSript alert template.
	 */
	protected $template_alert_js = <<<EOT
	'<div class="{class} alert-dismissible fade show" role="alert">'
	+ '{message}'
	+ '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
	+ '<span aria-hidden="true">&times;</span>'
	+ '</button>'
	+ '</div>'
	EOT;

	/**
	 * Array of default alerts classes.
	 * @var  array
	 */
	protected $alert_classes = array(
		'info'    => 'alert alert-info',
		'error'   => 'alert alert-danger',
		'warning' => 'alert alert-warning',
		'success' => 'alert alert-success',
	);

	/**
	 * Holds and instance of CI object.
	 * @var object
	 */
	protected $CI;
	/**
	 * Holds the current active theme.
	 * @var string
	 */
	protected $current_theme;

	/**
	 * Holds the active front-end theme.
	 * @var string
	 */
	protected $public_theme;

	/**
	 * Holds the active back-end theme.
	 * @var string
	 */
	protected $admin_theme;

	/**
	 * Holds the currently used package's name (folder).
	 * @var string
	 */
	protected $package = null;

	/**
	 * Holds the currently accessed controller.
	 * @var string
	 */
	protected $controller = null;

	/**
	 * Holds the currently accessed method.
	 * @var string
	 */
	protected $method = null;

	/**
	 * Holds the currently used layout.
	 * @var string
	 */
	protected $layout = 'default';

	/**
	 * Holds the currently loaded view.
	 * @var string
	 */
	protected $view;

	/**
	 * Holds an array of loaded partial views.
	 * @var array
	 */
	protected $partials = array();

	/**
	 * Holds the array of <html> tag classes.
	 * @var array
	 */
	protected $html_classes = array();

	/**
	 * Holds the array of <body> tag classes.
	 * @var array
	 */
	protected $body_classes = array();

	/**
	 * Holds the current page's title.
	 * @var string
	 */
	protected $title;

	/**
	 * Holds the page's title parts separator.
	 * @var string
	 */
	protected $title_separator = ' &#8212; ';

	/**
	 * Holds an array of all meta tags.
	 * @var array
	 */
	protected $meta_tags = array();

	/**
	 * Holds the array of styles to be put first.
	 * @var array
	 */
	protected $prepended_styles = array();

	/**
	 * Holds the array of enqueued styles.
	 * @var array
	 */
	protected $styles = array();

	/**
	 * Holds the array of inline styles.
	 * @var array
	 */
	protected $inline_styles = array();

	/**
	 * Holds the array of scripts to be put first.
	 * @var array
	 */
	protected $prepended_scripts = array();

	/**
	 * Holds the array of enqueued scripts.
	 * @var array
	 */
	protected $scripts = array();

	/**
	 * Holds the array of inline scripts.
	 * @var array
	 */
	protected $inline_scripts = array();


	/**
	 * Holds the current view content.
	 * @var string
	 */
	protected $content;

	/**
	 * Holds the time for which content is cached. Default: 0
	 * @var integer
	 */
	protected $cache_lifetime = 0;

	/**
	 * Whether to compress the final output or not.
	 * @var boolean
	 */
	protected $compress = false;

	/**
	 * Holds the array of enqueued alert messages.
	 * @var array
	 */
	protected $messages = array();

	/**
	 * Holds the current URI segments.
	 * @var array
	 */
	protected $uri = array();

	/**
	 * Holds an array of details about user's browser.
	 * @var array
	 */
	public $user_agent = array();

	// Default theme details.
	private $_headers = array(
		'name'         => null,
		'theme_uri'    => null,
		'description'  => null,
		'version'      => null,
		'license'      => null,
		'license_uri'  => null,
		'author'       => null,
		'author_uri'   => null,
		'author_email' => null,
		'tags'         => null,
		'screenshot'   => null,
		'textdomain'   => null,
		'domainpath'   => null,
		'admin'        => false,
	);

	/**
	 * Initializes class preferences.
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function initialize()
	{
		$this->uri = $this->ci->uri->segment_array();

		// Start our class initialization benchmark.
		$this->ci->benchmark->mark('theme_initialize_start');

		// load dependency
		$this->ci->load->helper('url');
		$this->ci->load->helper('html');
		$this->ci->load->helper('file');

		// We detect user's browser details.
		$this->user_agent = $this->_set_user_agent();

		// Store information about package, controller and method.
		$this->package = $this->ci->router->fetch_package();
		$this->controller = $this->ci->router->fetch_class();
		$this->method = $this->ci->router->fetch_method();

		// Overridden output compression.
		$this->compress = $this->ci->config->item('theme_compress');

        if ( ! isset($this->ci->assets))
        {
            $this->ci->load->driver('assets');
        }

		// A default variable that can be used to set active URI.
		$this->ci->load->vars('uri_string', uri_string(true));

		// ENd of our class initialization benchmark.
		$this->ci->benchmark->mark('theme_initialize_end');

		log_message('info', 'Gamelang_themes Class Initialized');
	}

	// ------------------------------------------------------------------------
	// Rendering methods.
	// ------------------------------------------------------------------------

	/**
	 * Instead of chaining this class methods or calling them one by one,
	 * this method is a shortcut to do anything you want in a single call.
	 * @access 	public
	 * @param 	array 	$data 		array of data to pass to view
	 * @param 	bool 	$return 	whether to output or simply build
	 */
	public function render($data = array(), $return = false)
	{
		$this->ci->benchmark->mark('theme_render_start');

		if ($this->_is_admin() && ! $this->theme_path())
		{
			die();
		}

		$this->load_functions();

		$this->load_translation();

		$output = $this->_load($this->get_view(), $data);

		$this->ci->benchmark->mark('theme_render_end');

		if ($this->ci->output->parse_exec_vars)
		{
			$output = str_replace(
				'{theme_time}',
				$this->ci->benchmark->elapsed_time('theme_render_start', 'theme_render_end'),
				(string)$output
			);
		}

		if ($return)
		{
			return $output;
		}

		$this->ci->output->set_output($output);
	}

	// --------------------------------------------------------------------

	/**
	 * Loads view file
	 * @access 	protected
	 * @param 	string 	$view 		view to load
	 * @param 	array 	$data 		array of data to pass to view
	 * @param 	bool 	$return 	whether to output view or not
	 * @param 	string 	$master 	in case you use a distinct master view
	 * @return  void
	 */
	protected function _load($view, $data = array())
	{
		do_action('after_theme_setup');

		has_action('theme_menus') && do_action('theme_menus');

		if (has_action('theme_images'))
		{
			do_action('theme_images');
			do_action('_set_images_sizes');
		}

		$layout = array();

		do_action($this->_is_admin() ? 'enqueue_admin_partials' : 'enqueue_partials');

		if ( ! empty($this->partials))
		{
			foreach ($this->partials  as $name => $content)
			{
				$layout[$name] = $content;
			}
		}

		$this->content = $this->_load_file($view, $data, 'view');

		$this->content = apply_filters(
			$this->_is_admin() ? 'admin_content' : 'the_content',
			$this->content
		);

		$layout['content'] = $this->content;

		! isset($this->layout) ?: $this->layout = $this->get_layout();

		$this->ci->output->set_header('HTTP/1.0 200 OK');
		$this->ci->output->set_header('HTTP/1.1 200 OK');
		$this->ci->output->set_header('Expires: Sat, 01 Jan 2000 00:00:01 GMT');
		$this->ci->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->ci->output->set_header('Cache-Control: post-check=0, pre-check=0, max-age=0');
		$this->ci->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		$this->ci->output->set_header('Pragma: no-cache');

		if (is_int($this->cache_lifetime) && $this->cache_lifetime > 0)
		{
			$this->ci->output->cache($this->cache_lifetime);
		}

		$output = $this->_load_file($this->layout, $layout, 'layout');

		$output = apply_filters($this->_is_admin() ? 'admin_output' : 'theme_output', $output);

		$output = $this->get_header().$output.$this->get_footer();

		$this->compress && $output = $this->compress_output($output);

		return $output;
	}

	// ------------------------------------------------------------------------

	/**
	 * Loads a file.
	 * @access 	protected
	 * @param 	string 	$file
	 * @param 	array 	$data
	 * @param 	string 	$type
	 * @return 	string if found, else false.
	 */
	protected function _load_file($file, $data = array(), $type = 'view')
	{
		$file = preg_replace('/.php$/', '', $file).'.php';
		$file_path = VIEWPATH;
		$this->_is_admin() && $file_path .= 'admin/';
		$file_path = normalize_path($file_path);

		$output = '';

		switch ($type) {
			case 'partial':
			case 'partials':
				$folder     = 'partials/';
				$filter     = $this->_is_admin() ? 'admin_partials_path' : 'theme_partials_path';
				$alt_filter = $this->_is_admin() ? 'admin_partial_fallback' : 'theme_partial_fallback';
				break;
			case 'layout':
			case 'layouts':
				$folder     = 'layouts/';
				$filter     = $this->_is_admin() ? 'admin_layouts_path' : 'theme_layouts_path';
				$alt_filter = $this->_is_admin() ? 'admin_layout_fallback' : 'theme_layout_fallback';
				break;

			case 'view':
			case 'views':
			default:
				$folder     = 'views/';
				$filter     = $this->_is_admin() ? 'admin_views_path' : 'theme_views_path';
				$alt_filter = $this->_is_admin() ? 'admin_view_fallback' : 'theme_view_fallback';
				break;
		}

		$this->package = $this->ci->router->fetch_package();
		isset($packpath) OR $packpath = $this->ci->router->package_path($this->package);

		if ( ! $this->_is_admin() OR 'view' !== $type)
		{
			$file_path .= $folder;
		}
		$file_path = apply_filters($filter, $file_path);

		// Alternative file.
		$alt_file = apply_filters($alt_filter, null);

		// Fall-back file.
		$this->ci->load->helper('inflector');
		$fallback = singular($type);

		// Full path to file.
		if ( ! $this->package)
		{
			$alt_file_path = $file_path;
		}
		elseif (false !== $packpath)
		{
			$alt_file_path = $packpath.$folder;
			if ('view' === $type && $this->_is_admin())
			{
				global $back_contexts;
				if (isset($this->uri[2]) && in_array($this->uri[2], $back_contexts))
				{
					$file = ltrim(str_replace(array($this->uri[1], $this->uri[2]), $this->uri[2], $file), '/');
				}
			}
		}

		$file_path = normalize_path($file_path.'/'.$file);
		$alt_file_path = normalize_path($alt_file_path.'/'.$file);

		if ( ! is_file((string)$alt_file) && $this->package)
		{
			// Attempt to guess the folder from package's contexts.
			if ('view' === $type && $this->_is_admin())
			{
				$folder .= 'admin/';
			}
			else
			{
				$folder = 'views/'.$folder;
			}

			$alt_file .= $packpath.$folder.$file;
		}

		if (is_file($file_path))
		{
			empty($data) OR $this->ci->load->vars($data);

			$output = $this->ci->load->file($file_path, true);
		}
		elseif ($alt_file && is_file($alt_file))
		{
			$file_path = $alt_file;

			empty($data) OR $this->ci->load->vars($data);

			$output = $this->ci->load->file($file_path, true);
		}
		elseif ($alt_file_path && is_file($alt_file_path))
		{
			empty($data) OR $this->ci->load->vars($data);

			$output = $this->ci->load->file($alt_file_path, true);
		}
		elseif ($fallback && isset($this->{'template_'.$fallback}))
		{
			$search = array_map(function(&$val) {
				return "{{$val}}";
			}, array_keys($data));

			$replace = array_values($data);

			$output = str_replace($search, $replace, $this->{'template_'.$fallback});
		}
		else
		{
			show_error(sprintf(__('lang_missing_file %s'), $file_path));
		}

		return $output;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set details about the user's browser.
	 * @access 	protected
	 * @param 	none
	 * @return 	array
	 */
	protected function _set_user_agent()
	{
		(class_exists('CI_User_agent', false)) OR $this->ci->load->library('user_agent');

		// We store details in session.
		if ( ! $this->ci->session->user_agent)
		{
			// Get the browser's nae.
			$user_agent['browser'] = $this->ci->agent->mobile()
				? $this->ci->agent->mobile()
				: $this->ci->agent->browser();

			// Browser's version.
			$user_agent['version'] = $this->ci->agent->version();

			// Browser's accepted languages.
			$user_agent['languages'] = array_values(array_filter(
				$this->ci->agent->languages(),
				function($lang) {
					return strlen($lang) <= 3;
				}
			));

			// Client's used platform (Window, iOS, Unix...).
			$user_agent['platform'] = $this->ci->agent->platform();

			// Set the session now.
			$this->ci->session->set_userdata('user_agent', $user_agent);
		}

		return $this->ci->session->user_agent;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the current view file content.
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	public function print_content()
	{
		return $this->content;
	}

	// ------------------------------------------------------------------------
	// Partials methods.
	// ------------------------------------------------------------------------

	/**
	 * Adds partial view
	 * @access 	public
	 * @param 	string 	$view 	view file to load
	 * @param 	array 	$data 	array of data to pass
	 * @param 	string 	$name 	name of the variable to use
	 */
	public function add_partial($view, $data = array(), $name = null)
	{
		empty($name) && $name = basename($view);

		if ( ! isset($this->partials[$name]))
		{
			$this->partials[$name] = $this->_load_file($view, $data, 'partial');
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Displays a partial view alone.
	 * @access 	public
	 * @param 	string 	$view 	the partial view name
	 * @param 	array 	$data 	array of data to pass
	 * @param 	bool 	$load 	load it if not cached?
	 * @return 	mixed
	 */
	public function get_partial($view, $data = array(), $load = true)
	{
		$name = basename($view);

		do_action('get_partial_'.$name);

		if (isset($this->partials[$name]))
		{
			return $this->partials[$name];
		}

		return $load ? null : $this->_load_file($view, $data, 'partial');
	}

	// ------------------------------------------------------------------------
	// Header and Footer methods.
	// ------------------------------------------------------------------------

	/**
	 * Returns or outputs the header file or provided template.
	 * @access 	public
	 * @param 	string 	$name 	The name of the file to use (Optional).
	 * @return 	string
	 */
	public function get_header($name = null)
	{
		static $cached = array();

		if (isset($cached[$name]))
		{
			return $cached[$name];
		}

		do_action('get_header', $name);

		$file = 'header.php';
		$name && $file = 'header-'.$name;
		$file = preg_replace('/.php$/', '', $file).'.php';

		$replace['doctype']             = apply_filters('the_doctype', '<!DOCTYPE html>');
		$replace['CG_copyright']     	= apply_filters('CG_copyright', $this->CG_copyright);
		$replace['base_url']            = base_url();
		$replace['html_class']          = $this->html_class();
		$replace['language_attributes'] = $this->language_attributes();
		$replace['charset']             = $this->ci->config->item('charset');
		$replace['title']               = $this->get_title();
		$replace['meta_tags']           = $this->print_meta_tags();
		$replace['stylesheets']         = $this->print_styles();
		$replace['extra_head']          = $this->print_extra_head();
		$replace['body_class']          = $this->body_class();
		$replace['body_attributes']     = $this->body_attributes();

		$output = $this->template_header;

		foreach ($replace as $key => $val)
		{
			$output = str_replace('{'.$key.'}', $val, $output);
		}

		$cached[$name] = $output;
		return $cached[$name];
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns or outputs the footer file or provided template.
	 * @access 	public
	 * @param 	string 	$name 	The name of the file to use (Optional).
	 * @return 	string
	 */
	public function get_footer($name = null)
	{
		static $cached = array();

		if (isset($cached[$name]))
		{
			return $cached[$name];
		}

		do_action('get_footer', $name);

		$file = $backup_file = 'footer.php';
		$name && $file = 'footer-'.$name;
		$file = preg_replace('/.php$/', '', $file).'.php';

		$output = str_replace(
			array('{javascripts}', '{analytics}'),
			array($this->print_scripts(), $this->print_analytics()),
			$this->template_footer
		);

		$cached[$name] = $output;
		return $cached[$name];
	}

	// ------------------------------------------------------------------------
	// HTML and Body classes methods.
	// ------------------------------------------------------------------------

	/**
	 * Return the string to use for html_class()
	 * @access 	public
	 * @param 	string 	$class to add.
	 * @return 	string
	 */
	public function html_class($class = null)
	{
		$output = '';

		is_array($this->html_classes) OR $this->html_classes = (array) $this->html_classes;

		$class && array_unshift($this->html_classes, $class);

		$this->html_classes = array_clean((array) $this->html_classes);

		$this->html_classes = apply_filters(
			$this->_is_admin() ? 'admin_html_class' : 'html_class',
			$this->html_classes
		);

		empty($this->html_classes) OR $output .= ' class="'.implode(' ', (array)$this->html_classes).'"';

		return $output;
	}

	// ------------------------------------------------------------------------

	/**
	 * Return the string to use for get_body_class()
	 * @access 	public
	 * @param 	string 	$class 	class to add.
	 * @return 	string
	 */
	public function body_class($class = null)
	{
		$output = '';

		is_array($this->body_classes) OR $this->body_classes = (array) $this->body_classes;

		$class && array_unshift($this->body_classes, $class);

		if ($this->_is_admin())
		{
			$this->body_classes[] = 'admin';
			$this->body_classes[] = 'ver-'.str_replace('.', '-', CG_VERSION);
			$this->body_classes[] = 'locale-'.strtolower($this->language('locale'));
		}

		$this->package && $this->body_classes[] = $this->package;
		$this->method = $this->ci->router->fetch_method();
		$this->body_classes[] = $this->controller;
		('index' !== $this->method) && $this->body_classes[] = $this->method;

		if ('login' !== $this->controller && 'rtl' === $this->language('direction'))
		{
			$this->body_classes[] = 'rtl';
		}

		$this->body_classes = array_clean((array)$this->body_classes);

		$this->body_classes = apply_filters(
			$this->_is_admin() ? 'admin_body_class' : 'body_class',
			$this->body_classes
		);

		empty($this->body_classes) OR $output .= ' class="'.implode(' ', (array)$this->body_classes).'"';

		return $output;
	}

	// ------------------------------------------------------------------------

	/**
	 * Return the string to use for get_body_class()
	 * @access 	public
	 * @param 	string 	$class 	class to add.
	 * @return 	string
	 */
	public function body_attributes(array $attributes = null)
	{
		$output = '';

		$attrs = apply_filters(
			$this->_is_admin() ? 'admin_body_attributes' : 'body_attributes',
			null
		);

		empty($attributes) OR $attrs = array_merge($attributes, $attrs);

		$attrs = _stringify_attributes($attrs);

		empty($attrs) OR $output .= $attrs;

		return $output;
	}

	// ------------------------------------------------------------------------

	/**
	 * Quick add classes to <body> tag.
	 * @access 	public
	 * @param 	mixed
	 * @return 	Theme
	 */
	public function set_body_class($args)
	{
		if ( ! empty($args))
		{
			is_array($args[0]) && $args = $args[0];

			$this->body_classes = array_clean(array_merge($this->body_classes, $args));
		}

		return $this;
	}

	// ------------------------------------------------------------------------
	// Paths methods.
	// ------------------------------------------------------------------------

	/**
	 * Returns the full path to the currently active theme, whether it's the
	 * front-end theme or dashboard theme.
	 * @access 	public
	 * @param 	string 	$uri
	 * @return 	mixed 	String if valid, else false.
	 */
	public function theme_path($uri = '', $theme = null)
	{
		static $path, $cached_paths = array();

		$theme OR $theme = $this->current_theme();

		if (is_null($path))
		{
			$path = $this->_is_admin() ? VIEWPATH : APPPATH.'themes';
			$path = path_join($path, $theme);
		}

		$return = $path;

		if ( ! empty($uri))
		{
			if ( ! isset($cached_paths[$uri]))
			{
				$return = file_exists($path.'/'.$uri) ? normalize_path($path.'/'.$uri) : false;
				$cached_paths[$uri] = $return;
			}

			$return = $cached_paths[$uri];
		}
		else
		{
			$return = file_exists($path) ? normalize_path($path) : false;
		}

		return $return;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the full path to uploads folder.
	 * @access 	public
	 * @param 	string 	$uri
	 * @return 	mixed 	String if valid path, else false.
	 */
	public function upload_path($uri = '')
	{
		static $path;

		is_null($path) && $path = APPPATH.config_item('upload_path');

		$return = normalize_path($path.'/'.$uri);

		return $return;
	}

	// ------------------------------------------------------------------------
	// URLs methods.
	// ------------------------------------------------------------------------

	/**
	 * Returns the URL to the currently active theme, whether it's the front-end
	 * theme or the dashboard theme.
	 * @access 	public
	 * @param 	string 	$uri
	 * @param 	string 	$protocol
	 * @return 	string
	 */
	public function theme_url($uri = '', $protocol = null)
	{
		static $base_url, $_protocol, $cached_uris;

		if (empty($base_url) OR $_protocol !== $protocol)
		{
			$_protocol = $protocol;
			$path = $this->_is_admin() ? 'views' : 'themes';
			$base_url = path_join(base_url('gamelang/'.$path, $_protocol), $this->current_theme());
		}

		$return = $base_url;

		if ( ! empty($uri))
		{
			if ( ! isset($cached_uris[$uri]))
			{
				$cached_uris[$uri] = $return.'/'.$uri;
			}

			$return = $cached_uris[$uri];
		}

		return $return;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the URL to the uploads folder.
	 * @access 	public
	 * @param 	string 	$uri
	 * @param 	string 	$protocol
	 * @return 	string
	 */
	public function upload_url($uri = '', $protocol = null)
	{
		static $base_url, $_protocol, $cached_uris;

		if (empty($base_url) OR $_protocol !== $protocol)
		{
			$_protocol = $protocol;

			$base_url = base_url('gamelang/'.config_item('upload_path'), $_protocol);
		}

		$return = $base_url;

		if ( ! empty($uri)
			&& file_exists($this->upload_path($uri))
		) {
			if ( ! isset($cached_uris[$uri]))
			{
				$cached_uris[$uri] = $base_url.'/'.$uri;
			}

			$return = $cached_uris[$uri];
		}
		else {
			$return = base_url('gamelang/views/assets/img/blank.png');
		}

		return $return;
	}

	// ------------------------------------------------------------------------

	/**
	 * Allows user to add in-line elements (CSS or JS)
	 * @access 	public
	 * @param 	string 	$type 		the file's type to add.
	 * @param 	string 	$content 	the in-line content.
	 * @param 	string 	$handle 	before which handle the content should be output.
	 * @return 	object
	 */
	public function add_inline($type = 'css', $content = '', $handle = null)
	{
		if ( ! in_array($type, array('css', 'js')) OR empty(trim($content)))
		{
			return $this;
		}

		$handle = preg_replace("/-{$type}$/", '',$handle)."-{$type}";

		if ($type === 'css')
		{
			$this->inline_styles[$handle] = $content;
		}
		else
		{
			$this->inline_scripts[$handle] = $content;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Outputs all site StyleSheets and in-line styles string.
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	public function print_styles()
	{
		$after_filter  = ($this->_is_admin())
			? 'after_admin_styles'
			: 'after_styles';

		$styles = '';

		$styles .= $this->ci->assets->get_assets('style', [], 'css');

		if ( ! empty($this->inline_styles))
		{
			$styles .= implode("\n\t", $this->inline_styles);
		}

		$styles = apply_filters($after_filter, $styles)."\t";

		return $styles;
	}

	// ------------------------------------------------------------------------

	/**
	 * Outputs all script tags and in-line scripts.
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	public function print_scripts()
	{
		$after_filter  = ($this->_is_admin())
			? 'after_admin_scripts'
			: 'after_scripts';

		$scripts = '';

		$scripts .= $this->ci->assets->get_assets('script', [], 'js');

		if ( ! empty($this->inline_scripts))
		{
			$scripts .= implode("\n\t", $this->inline_scripts);
		}

		$scripts = apply_filters($after_filter, $scripts)."\t";

		return $scripts;
	}

	// ------------------------------------------------------------------------

	/**
	 * Outputs all additional head string.
	 * @access 	public
	 * @param 	string 	$content
	 * @return 	string
	 */
	public function print_extra_head($content = "\n")
	{
		return apply_filters($this->_is_admin() ? 'admin_head' : 'extra_head', $content);
	}

	// ------------------------------------------------------------------------

	/**
	 * Outputs the default google analytics code.
	 * @access 	public
	 * @param 	string 	$site_id 	Google Analytics ID
	 * @return 	string
	 */
	public function print_analytics($site_id = null)
	{
		$site_id OR $site_id = $this->ci->config->item('google_analytics_id');

		$output = '';

		if ($site_id && 'UA-XXXXX-Y' !== $site_id)
		{
			$temp_analytics = apply_filters(
				$this->_is_admin() ? 'admin_google_analytics' : 'google_analytics',
				$this->template_google_analytics
			);

			$output .= str_replace('{site_id}', $site_id, $temp_analytics)."\n";
		}

		return $output;
	}

	// ------------------------------------------------------------------------
	// Meta tags methods.
	// ------------------------------------------------------------------------

	/**
	 * Quick action to add meta tags to given page
	 * @param mixed $object
	 * @return void
	 */
	public function set_meta($object = null)
	{
		// Site name and default title.
		if ($this->ci->config->item('site_name'))
		{
			$this->set('site_name', $this->ci->config->item('site_name'));
			$this->add_meta('application-name', $this->ci->config->item('site_name'));
			$this->add_meta('title', $this->ci->config->item('site_name'));
			$this->add_meta('og:title', $this->ci->config->item('site_name'));
		}

		// Site description.
		if ($this->ci->config->item('site_description'))
		{
			$this->add_meta('description', $this->ci->config->item('site_description'));
			$this->add_meta('og:description', $this->ci->config->item('site_description'));
		}

		// Add google site verification IF found.
		if ($this->ci->config->item('google_site_verification'))
		{
			$this->add_meta('google-site-verification', $this->ci->config->item('google_site_verification'));
		}

		// Site keywords.
		if ($this->ci->config->item('site_keywords'))
		{
			$this->add_meta('keywords', $this->ci->config->item('site_keywords'));
		}

		// Add site's author if found.
		if ($this->ci->config->item('site_author'))
		{
			$this->add_meta('author', $this->ci->config->item('site_author'));
		}

		if ( ! $this->_is_admin())
		{
			$generator = apply_filters('CG_generator', 'Tokoder '.CI_VERSION);
			empty($generator) OR $this->add_meta('generator', $generator);
		}

		// Add canonical tag.
		$this->add_meta('canonical', current_url(), 'rel');
	}

	// -----------------------------------------------------------------------------

	/**
	 * Appends meta tags
	 * @access 	public
	 * @param 	mixed 	$name 	meta tag's name
	 * @param 	mixed 	$content
	 * @return 	object
	 */
	public function add_meta($name, $content = null, $type = 'meta', $attrs = array())
	{
		if (is_array($name))
		{
			foreach ($name as $key => $val)
			{
				$this->add_meta($key, $val, $type, $attrs);
			}

			return $this;
		}

		$this->meta_tags[$type.'::'.$name] = array('content' => $content);
		empty($attrs) OR $this->meta_tags[$type.'::'.$name]['attrs'] = $attrs;
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Takes all site meta tags and prepare the output string.
	 * @access 	public
	 * @return 	string
	 */
	public function print_meta_tags()
	{
		$before_filter = 'before_meta';
		$after_filter  = 'after_meta';

		if ($this->_is_admin())
		{
			$before_filter = 'before_admin_meta';
			$after_filter  = 'after_admin_meta';
		}

		$meta_tags = apply_filters($before_filter, '');
		$meta_tags .= $this->_render_meta_tags();
		$meta_tags = apply_filters($after_filter, $meta_tags);

		return $meta_tags;
	}

	// ------------------------------------------------------------------------

	/**
	 * Collects all additional meta_tags and prepare them for output
	 * @access 	protected
	 * @param 	none
	 * @return 	string
	 */
	protected function _render_meta_tags()
	{
		$action = 'enqueue_admin_meta';
		$filter = 'render_admin_meta_tags';

		if ( ! $this->_is_admin())
		{
			$action = 'enqueue_meta';
			$filter = 'render_meta_tags';
		}

		do_action($action);

		$output = '';

		$i = 1;
		$j = count($this->meta_tags);

		foreach ($this->meta_tags as $key => $val)
		{
			list($type, $name) = explode('::', $key);

			$content = isset($val['content']) ? deep_htmlentities($val['content']) : null;
			$attrs   = isset($val['attrs']) ? $val['attrs'] : null;
			$output .= meta_tag($name, $content, $type, $attrs).($i === $j ? '' : "\n\t");

			$i++;
		}

		return  apply_filters($filter, $output);
	}

	// ------------------------------------------------------------------------
	// Title methods.
	// ------------------------------------------------------------------------

	/**
	 * Sets the page title.
	 * @access 	public
	 * @param 	string 	$title
	 * @return 	object
	 */
	public function set_title()
	{
		$args = func_get_args();

		if ( ! empty($args))
		{
			is_array($args[0]) && $args = $args[0];
			$this->title = implode($this->title_separator, $args);
		}

		add_filter( 'admin_page_title', function() {
			return $this->title;
		});

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the current page's title.
	 * @access 	public
	 * @param 	string 	$before 	string to be prepended.
	 * @param 	string 	$after 		string to be appended.
	 * @return 	string
	 */
	public function get_title($before = null, $after = null)
	{
		isset($this->title) OR $this->title = $this->_guess_title();
		is_array($this->title) OR $this->title = (array) $this->title;

		$this->title = apply_filters(
			$this->_is_admin() ? 'admin_title' : 'the_title',
			$this->title
		);

		empty($before) OR array_unshift($this->title, $before);
		empty($before) OR array_push($this->title, $before);

		$this->title = implode($this->title_separator, array_clean((array)$this->title));

		if ($this->_is_admin())
		{
			$CG_title = apply_filters('CG_title', ' &lsaquo; '.__('lang_app'));
			empty($CG_title) OR $this->title .= $CG_title;

			if (null !== ($site_name = $this->ci->config->item('site_name')))
			{
				$this->title .= $this->title_separator.$site_name;
			}
		}

		return $this->title;
	}

	// ------------------------------------------------------------------------

	/**
	 * Attempt to guess the title if it's not set.
	 * @access 	protected
	 * @param 	none
	 * @return 	array
	 */
	protected function _guess_title()
	{
		$title = array();

		$this->_is_admin() && $title[] = config_item('site_admin');

		$title[] = $this->package;

		$this->controller = $this->ci->router->fetch_class();
		$title[] = $this->controller;

		$this->method = $this->ci->router->fetch_method();
		($this->method !== 'index') && $title[] = $this->method;

		$title = array_clean(array_map('ucwords', $title));

		$title = apply_filters('guess_title', $title);

		return $title;
	}

	// ------------------------------------------------------------------------
	// Language method.
	// ------------------------------------------------------------------------

	/**
	 * Returns details about the currently used language.
	 * @access 	public
	 * @param 	string 	$key 	The key to return.
	 * @return 	mixed 	Array of no key provided, else string
	 */
	public function language($key = null)
	{
		/**
		 * Make sure the method remembers the current language to reduce
		 * calling config class each time we use it.
		 * @var array
		 */
		static $language;
		is_null($language) && $language = $this->ci->config->item('current_language');
		return ($key && isset($language[$key])) ? $language[$key] : $language;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set <html> language attributes.
	 * @access 	public
	 * @param 	array 	$attributes
	 * @return 	string
	 */
	public function language_attributes(array $attributes = null)
	{
		$output = '';

		$attrs = array($this->language('code'));

		$attrs = apply_filters(
			$this->_is_admin() ? 'admin_language_attributes' : 'language_attributes',
			$attrs
		);

		empty($attributes) OR $attrs = array_merge($attributes, $attrs);

		$attrs = array_clean($attrs);

		empty($attrs) OR $output .= ' lang="'.implode(' ', $attrs).'"';

		if ('login' !== $this->controller && 'rtl' === $this->language('direction'))
		{
			$output .= ' dir="rtl"';
		}

		return $output;
	}

	// ------------------------------------------------------------------------
	// Alerts methods.
	// ------------------------------------------------------------------------

	/**
	 * Sets alert message by storing them in $messages property and session.
	 * @access 	public
	 * @param 	mixed 	$message 	Message string or associative array.
	 * @return 	object
	 */
	public function set_alert($message, $type = 'info')
	{
		if ( ! empty($message))
		{
			is_array($message) OR $message = array($type => $message);

			foreach ($message as $key => $val)
			{
				$this->messages[] = array($key => $val);
			}

			$this->ci->session->set_flashdata('__ci_alert', $this->messages);
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns all registered alerts.
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	public function get_alert()
	{
		$output = '';

		empty($this->messages) && $this->messages = $this->ci->session->flashdata('__ci_alert');

		if ( ! empty($this->messages))
		{
			if ( ! $this->_is_admin())
			{
				$this->template_alert = apply_filters('alert_template', $this->template_alert);
				$this->alert_classes  = apply_filters('alert_classes', $this->alert_classes);
			}

			foreach ($this->messages as $index => $message)
			{
				$key = key($message);

				$output .= str_replace(
					array('{class}', '{message}'),
					array($this->alert_classes[$key], $message[$key]),
					$this->template_alert
				);
			}
		}

		return $output;
	}

	// ------------------------------------------------------------------------

	/**
	 * Displays an alert.
	 * @access 	public
	 * @param 	string 	$message 	The message to display.
	 * @param 	string 	$type 		The type of the alert.
	 * @param 	bool 	$js 		Whether to use the JS template.
	 * @return 	string
	 */
	public function print_alert($message, $type = 'info', $js = false)
	{
		if (empty($message))
		{
			return '';
		}

		$template = (true === $js) ? $this->template_alert_js : $this->template_alert;

		if ( ! $this->_is_admin())
		{
			$template = (true === $js)
				?  apply_filters('alert_template_js', $this->template_alert_js)
				:  apply_filters('alert_template', $this->template_alert);

			$this->alert_classes = apply_filters('alert_classes', $this->alert_classes);
		}

		$output = str_replace(
			array('{class}', '{message}'),
			array($this->alert_classes[$type], $message),
			$template
		);

		return $output;
	}

	// ------------------------------------------------------------------------
	// Layout methods.
	// ------------------------------------------------------------------------

	/**
	 * Changes the currently used layout.
	 * @access 	public
	 * @param 	string 	$layout 	the layout's name.
	 * @return 	object
	 */
	public function set_layout($layout = 'default')
	{
		empty($layout) && $layout = 'default';
		$this->layout = $layout;
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the current layout's name.
	 * @access 	public
	 * @param 	none
	 * @return 	string.
	 */
	public function get_layout()
	{
		$this->layout = apply_filters(
			$this->_is_admin() ? 'admin_layout' : 'theme_layout',
			$this->layout
		);

		return $this->layout;
	}

	// ------------------------------------------------------------------------

	/**
	 * layout_exists
	 *
	 * Method for checking the existence of the layout.
	 *
	 * @access 	public
	 * @param 	string 	$layout 	The layout to check (Optional).
	 * @return 	bool 	true if the layout exists, else false.
	 */
	public function layout_exists($layout = null)
	{
		empty($layout) && $layout = $this->get_layout();

		$layout = preg_replace('/.php$/', '', (string)$layout).'.php';

		$theme_path = $this->apply_filters(
			$this->_is_admin() ? 'admin_layouts_path' : 'theme_layouts_path',
			$this->theme_path()
		);

		return is_file($theme_path.'/'.$layout);
	}

	// -----------------------------------------------------------------------------

	public function load_functions()
	{
		// Load the current theme's functions.php file.
		if (false === $this->theme_path('functions.php'))
		{
			log_message('error', 'Unable to locate the theme\'s "functions.php" file: '.$this->current_theme());
			show_error(sprintf(__('theme_missing_functions %s'), $this->current_theme()));
		}

		// Load the current theme's functions.php file.
		require_once($this->theme_path('functions.php'));
	}

	// ------------------------------------------------------------------------
	// Theme translation methods.
	// ------------------------------------------------------------------------

	/**
	 * Allows themes to be translatable by loading their language files.
	 * @access 	public
	 * @param 	string 	$path 	The path to the theme's folder.
	 * @param 	string 	$index 	Unique identifier to retrieve language lines.
	 * @return 	void
	 */
	public function load_translation($path = null, $index = null)
	{
		/**
		 * Checks whether translations were already loaded or not.
		 * @var boolean
		 */
		static $loaded;

		if (true === $loaded)
		{
			return;
		}

		if (empty($path))
		{
			$path = apply_filters('theme_translation', $this->theme_path('language'));
		}

		if (true !== is_dir($path))
		{
			return;
		}

		// Prepare our array of language lines.
		$full_lang = array();

		// We make sure the check the english version.
		$english_file = $path.'/english.php';

		if (file_exists($english_file))
		{
			require_once($english_file);

			if (isset($lang))
			{
				$full_lang = array_replace_recursive($full_lang, $lang);
				unset($lang);
			}
		}

		if ('english' !== ($language = $this->language('folder'))
			&& file_exists($language_file = $path.'/'.$language.'.php'))
		{
			require_once($language_file);
			isset($lang) && $full_lang = array_replace_recursive($full_lang, $lang);
		}

		$full_lang = array_clean($full_lang);

		if ( ! empty($full_lang))
		{
			$textdomain = apply_filters('theme_translation_index', $index);
			empty($textdomain) && $textdomain = $this->current_theme();

			$this->ci->lang->language[$textdomain] = $full_lang;
		}

		$loaded = true;
	}

	// ------------------------------------------------------------------------
	// Views methods.
	// ------------------------------------------------------------------------

	/**
	 * Changes the currently used view.
	 * @access 	public
	 * @param 	string 	$view 	the view's name.
	 * @return 	object
	 */
	public function set_view($view = null)
	{
		$this->view = empty($view) ? $this->_guess_view() : $view;
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the current view's name.
	 * @access 	public
	 * @param 	none
	 * @return 	string.
	 */
	public function get_view()
	{
		isset($this->view) OR $this->view = $this->_guess_view();

		// Front-end view.
		if ( ! $this->_is_admin())
		{
			$this->view = apply_filters('theme_view', $this->view);
			return $this->view;
		}

		if ($this->package)
		{
			$this->view = preg_replace("/{$this->package}\//", '', $this->view);
			return $this->view;
		}

		// Backend-end view.
		if ( has_filter('admin_view'))
		{
			$this->view = apply_filters('admin_view', $this->view);
			return $this->view;
		}

		if ($this->_is_admin())
		{
			$this->method = $this->ci->router->fetch_method();
			$view = str_replace(config_item('site_admin').'/', '', isset($this->view) ? $this->view : $this->method);
			$packpath = $this->ci->router->package_path($this->package);
			$this->view = ($this->package && false !== $packpath) ? config_item('site_admin').'/'.$view : $view;
		}

		return $this->view;
	}

	// ------------------------------------------------------------------------

	/**
	 * Attempts to guess the view load.
	 * @access 	protected
	 * @param 	none
	 * @return 	string
	 */
	protected function _guess_view()
	{
		$view = array();

		if ($this->_is_admin() OR config_item('site_admin') === $this->ci->uri->segment(1) && $this->package == '')
		{
			$view[] = config_item('site_admin');
		}

		$this->package = $this->ci->router->fetch_package();
		$this->controller = $this->ci->router->fetch_class();
		if ($this->package !== $this->controller)
		{
			$view[] = $this->package;
			$view[] = $this->controller;
		}

		$this->method = $this->ci->router->fetch_method();
		$view[] = $this->method;

		return implode('/', array_clean($view));
	}

	// ------------------------------------------------------------------------

	/**
	 * Checks whether the view file exists or not.
	 * @access 	public
	 * @param 	string 	$view 	The view file to check.
	 * @return 	bool 	true if the view is found, else false.
	 */
	public function view_exists($view = null)
	{
		empty($view) && $view = $this->get_view();

		$view = preg_replace('/.php$/', '', (string)$view).'.php';

		$theme_path = apply_filters(
			$this->_is_admin() ? 'admin_views_path' : 'theme_views_path',
			$this->theme_path()
		);

		return is_file($theme_path.$view);
	}

	// ------------------------------------------------------------------------
	// Data setter
	// ------------------------------------------------------------------------

	/**
	 * Add variables to views.
	 * @access 	public
	 * @param 	string 	$name 	Variable's name.
	 * @param 	mixed 	$value 	Variable's value.
	 * @param 	bool 	$global Whether to make it global or not.
	 * @return 	object
	 */
	public function set($name, $value = null, $global = true)
	{
		if (is_array($name))
		{
			$global = (bool) $value;

			foreach ($name as $key => $val)
			{
				$this->set($key, $val, $global);
			}

			return $this;
		}

		if (true === $global)
		{
			$this->ci->load->vars($name, $value);
		}

		return $this;
	}

	// ------------------------------------------------------------------------
	// Current theme methods.
	// ------------------------------------------------------------------------

	/**
	 * Dynamically sets the current theme.
	 * @access 	public
	 * @param 	string 	$theme 	The theme's folder name.
	 * @return 	Theme
	 */
	public function set_theme($theme = null)
	{
		if (null !== $theme)
		{
			if ( ! isset($this->current_theme))
			{
				$this->current_theme = $this->current_theme();
			}

			if ($theme === $this->current_theme)
			{
				return $this;
			}

			if ($this->_is_admin())
			{
				$details = $this->get_theme_details($theme);

				if ( ! isset($details['admin']) OR true !== $details['admin'])
				{
					return $this;
				}
			}

			$this->current_theme = $theme;
		}

		$this->load_functions();

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the currently active theme (For backward compatibility).
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	public function get_theme()
	{
		return $this->current_theme();
	}

	// -----------------------------------------------------------------------------

	/**
	 * Returns the currently active theme depending on the site area.
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	public function current_theme()
	{
		if ( ! isset($this->current_theme))
		{
			$this->current_theme = $this->_is_admin()
				? $this->admin_theme()
				: $this->public_theme();
		}

		return $this->current_theme;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the currently active front-end theme.
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	public function public_theme()
	{
		if ( ! isset($this->public_theme))
		{
			$this->public_theme = apply_filters('public_theme', $this->ci->config->item('theme'));
			$this->public_theme OR $this->public_theme = 'default';
		}

		return $this->public_theme;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the currently active dashboard theme.
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	public function admin_theme()
	{
		if ( ! isset($this->admin_theme))
		{
			$this->admin_theme = $this->ci->config->item('admin_theme');
			$this->admin_theme OR $this->admin_theme = 'admin';
		}

		return $this->admin_theme;
	}

	// ------------------------------------------------------------------------

	/**
	 * @access 	public
	 * @param 	none
	 * @return 	bool 	true if in dashboard area, else false.
	 */
	protected function _is_admin()
	{
		/**
		 * We make sure the method remembers the status to reduce each time we use it.
		 * @var boolean
		 */
		static $is_admin;
		is_null($is_admin) && $is_admin = $this->ci->router->is_admin();

		return $is_admin;
	}

	// ------------------------------------------------------------------------
	// Output compression.
	// ------------------------------------------------------------------------

	/**
	 * Compresses the HTML output
	 *
	 * @access 	protected
	 * @param 	string 	$output 	the html output to compress
	 * @return 	string 	the minified version of $output
	 */
	public function compress_output($output)
	{
		// Make sure $output is always a string
		is_string($output) OR $output = (string) $output;

		// Nothing? Don't process.
		if ('' === trim($output))
		{
			return '';
		}

		// Conserve <pre> tags.
		$pre_tags = array();

		if (false !== strpos($output, '<pre'))
		{
			// We explode the output and always keep the last part.
			$parts     = explode('</pre>', $output);
			$last_part = array_pop($parts);

			// Reset output.
			$output = '';

			// Marker used to identify <pre> tags.
			$i = 0;

			foreach ($parts as $part)
			{
				$start = strpos($part, '<pre');

				// Malformed? Add it as it is.
				if (false === $start)
				{
					$output .= $part;
					continue;
				}

				// Identify the pre tag and keep it.
				$name = "<pre pre-tag-{$i}></pre>";
				$pre_tags[$name] = substr($part, $start).'</pre>';
				$output .= substr($part, 0, $start).$name;
				$i++;
			}

			// Always add the last part.
			$output .= $last_part;
		}

		// Conserve <!--copyright--> tags.
		$copyright = array();

		if (false !== strpos($output, '<!--copyright'))
		{
			// We explode the output and always keep the last part.
			$parts     = explode('<!--/copyright-->', $output);
			$last_part = array_pop($parts);

			// Reset output.
			$output = '';

			// Marker used to identify <!--copyright-->> tags.
			$i = 0;

			foreach ($parts as $part)
			{
				$start = strpos($part, '<!--copyright');

				// Malformed? Add it as it is.
				if (false === $start)
				{
					$output .= $part;
					continue;
				}

				// Identify the copyright tag and keep it.
				$name = "<copyright copyright-tag-{$i}></copyright>";
				$copyright[$name] = substr($part, $start).'<!--/copyright-->';
				$output .= substr($part, 0, $start).$name;
				$i++;
			}

			// Always add the last part.
			$output .= $last_part;
		}

		// Compress the final output.
		$output = $this->_compress_output($output);

		// If we have <pre> tags, add them.
		if ( ! empty($pre_tags))
		{
			$output = str_replace(array_keys($pre_tags), array_values($pre_tags), $output);
		}

		// If we have <!--copyright--> tags, add them.
		if ( ! empty($copyright))
		{
			$output = str_replace(array_keys($copyright), array_values($copyright), $output);
		}

		// Return the final output.
		return $output;
	}

	// ------------------------------------------------------------------------

	/**
	 * _compress_output
	 *
	 * The real method behind final output compression.
	 *
	 * @access 	protected
	 * @param 	string 	$output 	The final output.
	 * @return 	string 	The final output after compression.
	 */
	protected function _compress_output($output)
	{
		// In orders, we are searching for
		// 1. White-spaces after tags, except space.
		// 2. White-spaces before tags, except space.
		// 3. Multiple white-spaces sequences.
		// 4. HTML comments
		// 5. CDATA
		$output = preg_replace(array(
			'/\>[^\S ]+/s',
			'/[^\S ]+\</s',
			'/(\s)+/s',
			'/<!--(?!<!)[^\[>].*?-->/s',
			'#(?://)?<!\[CDATA\[(.*?)(?://)?\]\]>#s'
		), array(
			'>',
			'<',
			'\\1',
			'',
			"//&lt;![CDATA[\n".'\1'."\n//]]>"
		), $output);

		// We return the minified $output
		return $output;
	}

	// -----------------------------------------------------------------------------
	// THEME MANAGEMENT
	// -----------------------------------------------------------------------------

	function list_themes($details = false)
	{
		static $themes;

		if (is_null($themes))
		{
			$themes = array();
			$themes_path = $this->themes_path();

			if (false !== ($handle = opendir($themes_path)))
			{
				$ignored = array('.', '..', 'index.html', 'index.php', '.htaccess', '__MACOSX');

				while(false !== ($file = readdir($handle)))
				{
					if (is_dir($themes_path.'/'.$file) && ! in_array($file, $ignored))
					{
						$themes[] = $file;
					}
				}
			}
		}

		$return = $themes;

		if ($details && ! empty($themes))
		{
			foreach ($return as $i => $folder)
			{
				if (false !== ($details = $this->get_theme_details($folder)))
				{
					$return[$folder] = $details;
				}

				unset($return[$i]);
			}

			$return = apply_filters('get_themes', $return);
		}

		return $return;
	}

	// -----------------------------------------------------------------------------

	/**
	 * Returns path to where themes are located.
	 * @access 	public
	 * @param 	string 	$uri 	The URI to append to the path.
	 * @return 	string 	The path after being normalized if found, else false.
	 */
	public function themes_path($uri = '')
	{
		static $path, $cached_paths = array();
		empty($path) && $path = APPPATH.'themes';

		$return = $path;

		if ( ! empty($uri))
		{
			if ( ! isset($cached_paths[$uri]))
			{
				$return = file_exists($path.'/'.$uri) ? normalize_path($path.'/'.$uri) : false;
				$cached_paths[$uri] = $return;
			}

			$return = $cached_paths[$uri];
		}
		else
		{
			$return = file_exists($path) ? normalize_path($path) : false;
		}

		return $return;
	}

	// -----------------------------------------------------------------------------

	/**
	 * Returns details about the given theme.
	 * @access 	public
	 * @param 	string 	$folder 	The theme's folder name.
	 * @return 	mixed 	Array of details if valid, else false.
	 */
	public function get_theme_details($folder = null)
	{
		static $cached = array();
		$folder OR $folder = $this->current_theme();

		if ( isset($cached[$folder]))
		{
			return $cached[$folder];
		}

		if ( ! $folder)
		{
			return false;
		}

		$manifest_file = 'manifest.json';
		$manifest_dist = 'manifest.json.dist';

		$found = false;
		if (false !== is_file($manifest = $this->themes_path($folder.'/'.$manifest_file)))
		{
			$manifest = json_read_file($manifest);
			$found = true;

			// Create the distribution file just in case the file was edited.
			if (false === $this->themes_path($folder.'/'.$manifest_dist))
			{
				$theme_path = $this->themes_path($folder);
				@copy($theme_path.'/'.$manifest_file, $theme_path.'/'.$manifest_dist);
			}
		}

		if (true !== $found
			&& false !== is_file($manifest = $this->themes_path($folder.'/'.$manifest_dist)))
		{
			$manifest = json_read_file($manifest);
			$found = true;

			// Copy the distribution file.
			if (false === $this->themes_path($folder.'/'.$manifest_file))
			{
				$theme_path = $this->themes_path($folder);
				@copy($theme_path.'/'.$manifest_dist, $theme_path.'/'.$manifest_file);
			}
		}

		if (true !== $found OR false === $manifest)
		{
			return false;
		}

		/**
		 * Allow users to filter default themes headers.
		 */
		$headers = apply_filters('themes_headers', $this->_headers);

		// We fall-back to default headers if empty.
		empty($headers) && $headers = $this->_headers;

		foreach ($headers as $key => $val)
		{
			if (isset($manifest[$key]))
			{
				$headers[$key] = $manifest[$key];
			}
		}

		// Format license.
		if (false !== stripos($headers['license'], 'mit') && empty($headers['license_uri']))
		{
			$headers['license_uri'] = 'http://opensource.org/licenses/MIT';
		}

		if (empty($headers['screenshot']))
		{
			$headers['screenshot'] = base_url('gamelang/views/assets/img/blank.png');
			foreach (array('.png', '.jpg', '.jpeg', '.gif') as $ext)
			{
				if (false !== $this->themes_path($folder.'/screenshot'.$ext))
				{
					$headers['screenshot'] = base_url('gamelang/themes/'.$folder.'/screenshot'.$ext);
					break;
				}
			}
		}

		// Add extra stuff.
		$headers['folder']       = $folder;
		$headers['full_path']    = $this->themes_path($folder);

		// Is the theme enabled?
		$headers['enabled'] = ($folder === get_option('theme', 'default'));

		// Send default language folder and index.
		empty($headers['textdomain']) && $headers['textdomain'] = $folder;
		empty($headers['domainpath']) && $headers['domainpath'] = 'language';

		// Cache it first.
		return $cached[$folder] = $headers;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('add_ie9_support'))
{
	/**
	 * This function is used alongside the "extra_head" filter in order
	 * to add support for old browsers (Internet Explorer)
	 * @param 	string 	$output 	The extra head content.
	 * @param 	bool 	$remote 	Whether to load from CDN or use local files.
	 * @return 	void
	 */
	function add_ie9_support(&$output, $remote = true)
	{
		$html5shiv = '//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js';
		$respond   = '//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js';

		if ($remote === false)
		{
			$html5shiv = base_url('assets/js/html5shiv-3.7.3.min.js', '');
			$respond   = base_url('assets/js/respond-1.4.2.min.js', '');
		}
		$output .= <<<EOT
			<!--[if lt IE 9]>
			<script type="text/JavaScript" src="{$html5shiv}"></script>
			<script type="text/JavaScript" src="{$respond}"></script>
			<![endif]-->
		EOT;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('get_theme_path'))
{
	/**
	 * Returns the URL to the theme folder.
	 * @param   string  $uri    string to be appended.
	 * @return  string.
	 */
	function get_theme_path($uri = '')
	{
		return get_instance()->themes->theme_path($uri);
	}
}

// -----------------------------------------------------------------------------

if ( ! function_exists('get_theme_url'))
{
	/**
	 * Returns the URL to the theme folder.
	 * @param   string  $uri    string to be appended.
	 * @param   string  $protocol
	 * @return  string.
	 */
	function get_theme_url($uri = '', $protocol = null)
	{
		return get_instance()->themes->theme_url($uri, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('get_upload_path'))
{
	/**
	 * Unlike the function above, this one echoes the URL.
	 * @param   string  $uri    string to be appended.
	 * @param   string  $protocol
	 */
	function get_upload_path($uri = '')
	{
		return get_instance()->themes->upload_path($uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('get_upload_url'))
{
	/**
	 * Unlike the function above, this one echoes the URL.
	 * @param   string  $uri    string to be appended.
	 * @param   string  $protocol
	 */
	function get_upload_url($uri = '', $protocol = null)
	{
		return get_instance()->themes->upload_url($uri, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('add_inline'))
{
	/**
	 * Adds JavaScript files to view.
	 */
	function add_inline($type = 'css', $content = '', $handle = null)
	{
		return get_instance()->themes->add_inline($type, $content, $handle);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('the_content'))
{
	/**
	 * This function output/echoes the loaded view file content.
	 * @param 	bool 	$echo 	whether to output or return the content.
	 * @return 	string
	 */
	function the_content($echo = true)
	{
		if ($echo === false)
		{
			return get_instance()->themes->print_content();
		}

		echo get_instance()->themes->print_content();
	}
}

// ---------------------------------------------------------------------------------
// METADATA FUNCTIONS
// ---------------------------------------------------------------------------------

if ( ! function_exists('add_meta_tag'))
{
	/**
	 * Allow the user to add <meta> tags.
	 * @param   mixed   $name   meta tag's name
	 * @param   mixed   $content
	 * @return  object
	 */
	function add_meta_tag($name, $content = null, $type = 'meta', $attrs = array())
	{
		return get_instance()->themes->add_meta($name, $content, $type, $attrs);
	}
}

// ---------------------------------------------------------------------------------
// HEADER,FOOTER AND PARTIALS GETTERS
// ---------------------------------------------------------------------------------

if ( ! function_exists('get_header'))
{
	/**
	 * Load the theme header file.
	 */
	function get_header($file = null, $echo = true)
	{
		if ($echo === false)
		{
			return get_instance()->themes->get_header($file);
		}

		echo get_instance()->themes->get_header($file);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('get_footer'))
{
	/**
	 * Load the theme footer file.
	 */
	function get_footer($file = null)
	{
		echo get_instance()->themes->get_footer($file);
	}
}

// -----------------------------------------------------------------------------

if ( ! function_exists('add_partial'))
{
	/**
	 * This function allow you to enqueue any additional
	 * partial views and you can even override existing
	 * ones by providing the same name as the target.
	 * @param   string  $file   the view file to load.
	 * @param   array   $data   array of data to pass to view.
	 * @param   string  $name   the name of the partial view.
	 */
	function add_partial($file, $data = array(), $name = null)
	{
		return get_instance()->themes->add_partial($file, $data, $name);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('get_partial'))
{
	/**
	 * This function load a partial view located under
	 * theme folder/views/_partials/..
	 * @param   string  $file   the file to load.
	 * @param   array   $data   array of data to pass to view.
	 * @return  string.
	 */
	function get_partial($file, $data = array(), $load = true)
	{
		return get_instance()->themes->get_partial($file, $data, $load);
	}
}

// ---------------------------------------------------------------------------------
// FLASH ALERTS FUNCTIONS
// ---------------------------------------------------------------------------------

if ( ! function_exists('set_alert'))
{
	/**
	 * Sets a flash alert.
	 * @param   mixed   $message    message or array of $type => $message
	 * @param   string  $type       type to use for a single message.
	 * @return  void.
	 */
	function set_alert($message, $type = 'info')
	{
		return get_instance()->themes->set_alert($message, $type);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('the_alert'))
{
	/**
	 * Echoes any set flash messages.
	 * @return  string
	 */
	function the_alert()
	{
		echo get_instance()->themes->get_alert();
	}
}

// -----------------------------------------------------------------------------

if ( ! function_exists('print_alert'))
{
	/**
	 * Displays a flash alert.
	 * @param  string 	$message the message to display.
	 * @param  string 	$type    the message type.
	 * @param  bool 	$js 	 html or js
	 * @return string
	 */
	function print_alert($message = null, $type = 'info', $js = false, $echo = true)
	{
		$alert = get_instance()->themes->print_alert($message, $type, $js);
		if ($echo === false)
		{
			return $alert;
		}

		echo $alert;
	}
}