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
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CG_Router Class
 *
 * Parses URIs and determines routing
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
require_once(APPPATH.'libraries/CG_Route.php');

class CG_Router extends CI_Router
{
	/**
	 * The current package's name.
	 * @var string
	 */
	public $package = null;

	/**
	 * Array of all available packages.
	 * @var array
	 */
	protected $_packages;

	/**
	 * Array of all packages and their details.
	 * @var 	array
	 */
	protected $_package_details = array();

	/**
	 * Holds the path where packages are located.
	 * @var string
	 */
	protected $_packages_dir;

	/**
	 * Holds the array of active packages.
	 * @var array
	 */
	protected $_package_active;

	/**
	 * To avoid creating the variable each time we get
	 * a single package details, we create it here.
	 * @var 	array
	 */
	protected $_headers = array(
		'name'         => null,
		'package_uri'   => null,
		'description'  => null,
		'version'      => null,
		'license'      => null,
		'license_uri'  => null,
		'author'       => null,
		'author_uri'   => null,
		'author_email' => null,
		'tags'         => null,
		'enabled'      => false,
		'app_menu'     => false,
		'routes'       => array(),
		'admin_menu'   => null,
		'admin_order'  => 0,
		'textdomain'   => null,
		'translations' => array(),
	);

	/**
	 * Class constructor.
	 * @return 	void
	 */
	function __construct()
	{
		parent::__construct();
	}

	// --------------------------------------------------------------------

	/**
	 * Set route mapping
	 *
     * The only reason we are add this method is to allow users to create
     * a "routes.php" file inside the config folder.
     * They can either use the "$route" array of our static Routing using
     * Route class.
	 */
	protected function _set_routing()
	{
		// Load the routes.php file. It would be great if we could
		// skip this for enable_query_strings = TRUE, but then
		// default_controller would be empty ...
		if (file_exists(APPPATH.'config/routes.php'))
		{
			include(APPPATH.'config/routes.php');
		}

		if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/routes.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
		}

    	// Package routes.
		if ( ! empty($packages = $this->list_packages(false)))
		{
			foreach ($packages as $folder => $path)
			{
				// Returns TRUE if the selected plugin is enabled.
				if ( ! $this->is_enabled($folder))
				{
					continue;
				}

				$routes_file = $path.'config/routes.php';
				if (is_file($routes_file) && pathinfo($routes_file, PATHINFO_EXTENSION) == 'php')
				{
					include_once($path.'config/routes.php');
				}

				$details = $this->package_details($folder, $path);
				if (isset($details['routes']) && ! empty($details['routes']))
				{
					$this->_set_request_package($details['routes']);
				}

				continue;
			}
		}

		// Validate & get reserved routes
		if (isset($route) && is_array($route))
		{
			isset($route['default_controller']) && $this->default_controller = $route['default_controller'];
			isset($route['translate_uri_dashes']) && $this->translate_uri_dashes = $route['translate_uri_dashes'];
			unset($route['default_controller'], $route['translate_uri_dashes']);
			$this->routes = Route::map($route);
		}

		// Are query strings enabled in the config file? Normally CI doesn't utilize query strings
		// since URI segments are more search-engine friendly, but they can optionally be used.
		// If this feature is enabled, we will gather the directory/class/method a little differently
		if ($this->enable_query_strings)
		{
			// If the directory is set at this time, it means an override exists, so skip the checks
			if ( ! isset($this->directory))
			{
				$_d = $this->config->item('directory_trigger');
				$_d = isset($_GET[$_d]) ? trim($_GET[$_d], " \t\n\r\0\x0B/") : '';

				if ($_d !== '')
				{
					$this->uri->filter_uri($_d);
					$this->set_directory($_d);
				}
			}

			$_c = trim($this->config->item('controller_trigger'));
			if ( ! empty($_GET[$_c]))
			{
				$this->uri->filter_uri($_GET[$_c]);
				$this->set_class($_GET[$_c]);

				$_f = trim($this->config->item('function_trigger'));
				if ( ! empty($_GET[$_f]))
				{
					$this->uri->filter_uri($_GET[$_f]);
					$this->set_method($_GET[$_f]);
				}

				$this->uri->rsegments = array(
					1 => $this->class,
					2 => $this->method
				);
			}
			else
			{
				$this->_set_default_controller();
			}

			// Routing rules don't apply to query strings and we don't need to detect
			// directories, so we're done here
			return;
		}

		// Is there anything to parse?
		if ($this->uri->uri_string !== '')
		{
			$this->_parse_routes();
		}
		else
		{
			$this->_set_default_controller();
		}
	}

    // ------------------------------------------------------------------------

    /**
     * Set package Routes
	 *
     * Sets packages routes automatically.
     */
    protected function _set_request_package($routes = array())
    {
		if (empty($routes))
		{
			return;
		}

		foreach ($routes as $route => $original)
		{
			if (1 === sscanf($route, 'resources:%s', $new_route))
			{
				Route::resources($new_route, $original);
			}
			elseif (1 === sscanf($route, 'context:%s', $new_route))
			{
				Route::context($new_route, $original);
			}
			elseif (empty($original))
			{
				Route::block($route);
			}
			elseif (1 === sscanf($route, 'any:%s', $new_route))
			{
				Route::any($new_route, $original);
			}
			elseif (1 === sscanf($route, 'get:%s', $new_route))
			{
				Route::get($new_route, $original);
			}
			elseif (1 === sscanf($route, 'post:%s', $new_route))
			{
				Route::post($new_route, $original);
			}
			elseif (1 === sscanf($route, 'put:%s', $new_route))
			{
				Route::put($new_route, $original);
			}
			elseif (1 === sscanf($route, 'delete:%s', $new_route))
			{
				Route::delete($new_route, $original);
			}
			elseif (1 === sscanf($route, 'head:%s', $new_route))
			{
				Route::head($new_route, $original);
			}
			elseif (1 === sscanf($route, 'patch:%s', $new_route))
			{
				Route::patch($new_route, $original);
			}
			elseif (1 === sscanf($route, 'options:%s', $new_route))
			{
				Route::options($new_route, $original);
			}
			else
			{
				Route::any($route, $original);
			}
		}
    }

	// --------------------------------------------------------------------

	/**
	 * Set default controller
	 *
	 * @return	void
	 */
	protected function _set_default_controller()
	{
		// Returns TRUE if the selected plugin is enabled.
		if ($this->is_enabled($this->default_controller)
			&& $this->uri->uri_string === '')
		{
			$this->_locate_package([$this->default_controller]);
		}

        // Let the parent handle the rest!
        parent::_set_default_controller();
	}

	// --------------------------------------------------------------------

	/**
	 * Validates the supplied segments.
	 *
	 * Attempts to determine the path to the controller.
	 * Edited to allow modular development
	 */
	protected function _validate_request($segments)
	{
    	// If we have no segments, return as-is.
        if (count($segments) == 0)
        {
            return $segments;
        }

    	// Let's detect package's parts first.
		if ($located = $this->_locate_package($segments))
		{
        	// If found, return the result.
			return $located;
		};

        // Let the parent handle the rest!
        return parent::_validate_request($segments);
	}

	// --------------------------------------------------------------------

	/**
	 * Parse Routes
	 *
	 * Matches any routes that may exist in the config/routes.php file
	 * against the URI to determine if the class/method need to be remapped.
	 *
	 * @return	void
	 */
	protected function _parse_routes()
	{
		// Turn the segment array into a URI string
		$uri = implode('/', $this->uri->segments);

		if (Route::named($uri) != NULL)
		{
			$uri = Route::named($uri);
		}

		// Get HTTP verb
		$http_verb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

		// Loop through the route array looking for wildcards
		foreach ($this->routes as $key => $val)
		{
			// Check if route format is using HTTP verbs
			if (is_array($val))
			{
				$val = array_change_key_case($val, CASE_LOWER);
				if (isset($val[$http_verb]))
				{
					$val = $val[$http_verb];
				}
				else
				{
					continue;
				}
			}

			// Convert wildcards to RegEx
			$key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);

			// Does the RegEx match?
			if (preg_match('#^'.$key.'$#', $uri, $matches))
			{
				// Are we using callbacks to process back-references?
				if ( ! is_string($val) && is_callable($val))
				{
					// Remove the original string from the matches array.
					array_shift($matches);

					// Execute the callback using the values in matches as its parameters.
					$val = call_user_func_array($val, $matches);
				}
				// Are we using the default routing method for back-references?
				elseif (strpos($val, '$') !== FALSE && strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}

				$this->_set_request(explode('/', $val));
				return;
			}
		}

		// If we got this far it means we didn't encounter a
		// matching route so we'll set the site default route
		$this->_set_request(array_values($this->uri->segments));
	}

	// -----------------------------------------------------------------------------
	// PACKAGES
	// -----------------------------------------------------------------------------

	protected function _locate_package($segments)
	{
    	// Let's detect package's parts first.
        list($package, $directory, $controller) = array_pad($segments, 3, NULL);

        // Flag to see if we are in a package.
        $is_package = false;
		$package_path = PACKAGEPATH . $package;
		$directory_path = empty($directory) ?: PACKAGEPATH . $directory;

		// we check entities user
		if (is_dir($package_path))
        {
			$is_package = true;
			$location  = $package_path;
        }
        // Because of revered routes ;)
        elseif (is_dir($directory_path))
        {
			$is_package = true;
			$location  = $directory_path;
			$_package   = $package;
			$package    = $directory;
			$directory = $_package;
        }

        if ( ! $is_package)
        {
			return $is_package;
		}

		if (true !== is_dir($source = $location.'/controllers/'))
		{
			return false;
		}

		$relative = rtrim(str_replace(APPPATH, '', $location), '/');
		$this->directory = "../{$relative}/controllers/";
		$this->set_package($package);

		// Found the controller?
		if ($directory && is_file($source.ucfirst($directory).'.php'))
		{
			$this->set_class($directory);
			$segments[0] = $package;
			$segments[1] = $directory;

			return array_slice($segments, 1);
		}

		// Controller in a sub-directory?
		if ($directory && is_dir($source.$directory.'/'))
		{
			$source = $source.$directory.'/';
			$this->directory .= $directory.'/';

			// Different controller's name?
			if ($controller && is_file($source.ucfirst($controller).'.php'))
			{
				return array_slice($segments, 2);
			}

			// package sub-directory with default controller?
			if (is_file($source.ucfirst($this->default_controller).'.php'))
			{
				$segments[1] = $this->default_controller;
				return array_slice($segments, 1);
			}
		}

		// package with default controller?
		if (is_file($source.ucfirst($this->default_controller).'.php'))
		{
			$segments[0] = $this->default_controller;
			return $segments;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns a list of available packages.
	 */
	public function list_packages($details = true)
	{
		// Not cached? Cache them first.
		if ( ! isset($this->_packages))
		{
			$this->_packages = array();

			$packages_dir = $this->packages_dir();

			if (false !== ($handle = opendir($packages_dir)))
			{
				// Files and directories to ignore.
				$_to_eliminate = array(
					'.',
					'..',
					'.gitkeep',
					'index.html',
					'.htaccess',
					'__MACOSX',
				);

				while (false !== ($file = readdir($handle)))
				{
					if ( ! in_array($file, $_to_eliminate) // Ignore some files.
						&& is_dir($packages_dir.$file) // Valid directory.
						&& is_file($packages_dir.$file.'/manifest.json')) // manifest.json file.
					{
						$this->_packages[$file] = rtrim(str_replace('\\', '/', $packages_dir.$file), '/').'/';
					}
				}
			}
		}

		// Alphabetically order packages.
		ksort($this->_packages);

		$return = $this->_packages;

		if (true === $details)
		{
			$_package_details = array();

			foreach ($this->_packages as $folder => $path)
			{
				if (isset($this->_package_details[$folder]))
				{
					$_package_details[$folder] = $this->_package_details[$folder];
				}
				elseif (false !== ($details = $this->package_details($folder, $path)))
				{
					$_package_details[$folder] = $details;
				}
			}

			empty($_package_details) OR $return = $_package_details;
		}

		return $return;
	}

	// ------------------------------------------------------------------------

	/**
	 * Reads details about the plugin from the manifest.json file.
	 */
	public function package_details($folder, $path = null)
	{
		if (empty($folder))
		{
			$name = $this->fetch_package();

			if (empty($name))
			{
				return false;
			}
		}

		// Prepare path to plugin first.
		if (null === $path && isset($this->_packages[$folder]))
		{
			$path = $this->_packages[$folder];
		}
		elseif (null === $path && is_dir($dir = $this->packages_dir($folder.'/')))
		{
			$path = $dir;
			unset($dir);
		}

		$full_path = $path;
		$manifest_file = $full_path.'manifest.json';

		// Second check.
		if (null === $full_path OR ! is_file($manifest_file))
		{
			return false;
		}

		$content = file_get_contents($manifest_file);
		$headers = json_decode($content, true);

		// Make sure a good array is provided.
		if ( ! is_array($headers))
		{
			return false;
		}

		/**
		 * Allow users to filter default packages headers.
		 */
		$default_headers = apply_filters('packages_headers', $this->_headers);
		empty($default_headers) && $default_headers = $this->_headers;

		$headers = array_replace_recursive($default_headers, $headers);

		// Remove not listed headers.
		foreach ($headers as $key => $val)
		{
			if ( ! array_key_exists($key, $default_headers))
			{
				unset($headers[$key]);
			}
		}

		// Added things:
		empty($headers['admin_menu']) && $headers['admin_menu'] = $folder;

		// Add all internal details.
		$headers['contexts'] = $this->package_contexts($folder, $full_path);
		if ( ! empty($headers['contexts']))
		{
			foreach ($headers['contexts'] as $key => $val)
			{
				$headers['has_'.$key] = (false !== $val);
			}
		}

		/**
		 * If the package comes without a "help" controller, we see if
		 * the developer provided a package URI so we can use it as
		 * a URL later.
		 */
		if (false === $headers['has_help'] && ! empty($headers['package_uri']))
		{
			$headers['contexts']['help'] = $headers['package_uri'];
			$headers['has_help'] = true;
		}

		// Format license.
		if (false !== stripos($headers['license'], 'mit') && empty($heades['license_uri']))
		{
			$headers['license_uri'] = 'http://opensource.org/licenses/MIT';
		}

		// Add some useful stuff.
		$headers['enabled']      = $this->is_enabled($folder);
		$headers['folder']       = $folder;
		$headers['full_path']    = $full_path;

		// Cache everything before returning.
		$this->_package_details[$folder] = $headers;
		return $headers;
	}

	// ------------------------------------------------------------------------

	/**
	 * packages_dir
	 *
	 * Method for returning the full path to packages directory.
	 */
	public function packages_dir($uri = '')
	{
		if ( ! isset($this->_packages_dir))
		{
			$this->_packages_dir = rtrim(str_replace('\\', '/', PACKAGEPATH), '/').'/';
		}

		// Provided a $uri? Format it first.
		empty($uri) OR $uri = ltrim(str_replace('\\', '/', $uri), '/');

		return $this->_packages_dir.$uri;
	}

	// -----------------------------------------------------------------------------

	/**
	 * Method for list all package's available contexts.
	 */
	public function package_contexts($package, $path = null)
	{
		// Nothing provided? Nothing to do...
		if (empty($package))
		{
			return false;
		}

		// We start with an empty array.
		$package_contexts = array();

		// Make sure the package directory path if found.
		(null === $path) && $path = $this->package_realpath($package);
		if (false === $path)
		{
			return $package_contexts;
		}

		// Let's first see if the package has an admin controller.
		$package_contexts['admin']  = is_file($path.'/controllers/admin/Welcome.php');

		// Loop through contexts and see if we find a controller.
		global $back_contexts;
		foreach ($back_contexts as $context)
		{
			$package_contexts[$context] = is_file($path.'/controllers/admin/'.ucfirst($context).'.php');
		}

		// Return the final result.
		return $package_contexts;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the real path to packages directory.
	 */
	public function package_realpath($uri = '')
	{
		return realpath($this->_packages_dir.'/'.$uri);
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns TRUE if the selected plugin is enabled.
	 */
	public function is_enabled($name)
	{
		return (in_array($name, $this->get_package_active()));
	}

	// ------------------------------------------------------------------------

	/**
	 * Get the list of active packages.
	 */
	public function get_package_active($details = false)
	{
		// Not cached? Cache them first.
		if ( ! isset($this->_package_active))
		{
			/**
			 * Because we are automatically assigning options from database
			 * to $assign_to_config array, we see if we have the item
			 */
			global $assign_to_config;
			if (isset($assign_to_config['active_packages']))
			{
				$packages = $assign_to_config['active_packages'];
			}
			// Otherwise, see on database.
			else
			{
				$packages = CG_app::DB()
					->where('name', 'active_packages')
					->get('options')
					->row();

				// Not found? We make sure to create the option.
				if (null === $packages)
				{
					$packages = array();
					CG_app::DB()->insert('options', array(
						'name'  => 'active_packages',
						'value' => to_bool_or_serialize($packages),
						'tab'   => 'packages',
					));
				}
			}

			// We make sure it's an array before finally caching it.
			is_array($packages) OR $packages = array();
			$this->_package_active = $packages;
		}

		$return = $this->_package_active;

		if (true === $details)
		{
			$_package_details = array();

			foreach ($this->_package_active as $key => $folder)
			{
				if (isset($this->_package_details[$folder]))
				{
					$_package_details[$folder] = $this->_package_details[$folder];
				}
				elseif (false !== ($details = $this->package_details($folder)))
				{
					$_package_details[$folder] = $details;
				}
			}

			empty($_package_details) OR $return = $_package_details;
		}

		return $return;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set package name.
	 */
	public function set_package($package)
	{
		$this->package = $package;
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch the current package name.
	 */
	public function fetch_package()
	{
		return $this->package;
	}
}

// ------------------------------------------------------------------------
// Helpers.
// ------------------------------------------------------------------------

if ( ! function_exists('is_package'))
{
	/**
	 * Checks if the page belongs to a given package. If no argument is passed,
	 * it checks if we areusing a package.
	 * You may pass a single string, multiple comma- separated packages or an array.
	 */
	function is_package($packages = null)
	{
		$package = get_instance()->router->fetch_package();

		if (null === $packages)
		{
			return ($package !== null);
		}

		if ( ! is_array($packages))
		{
			$packages = explode(',', $packages);
		}

		$packages = array_clean($packages);

		return in_array($package, $packages);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('is_controller'))
{
	/**
	 * Checks if the page belongs to a given controller.
	 */
	function is_controller($controllers = null)
	{
		$controller = get_instance()->router->fetch_class();

		if (null === $controllers)
		{
			return ($controller !== null);
		}

		if ( ! is_array($controllers))
		{
			$controllers = explode(',', $controllers);
		}

		$controllers = array_clean($controllers);

		return in_array($controller, $controllers);
	}
}