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
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH.'libraries/CG_Route.php');

/**
 * CG_Router Class
 *
 * Parses URIs and determines routing
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
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
	protected $_packages_details = array();

	/**
	 * Holds the array of active packages.
	 * @var array
	 */
	protected $_active_packages;

	/**
	 * Holds an array of package locations.
	 * @var array.
	 */
	protected $_locations;

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
		$this->config =& load_class('Config', 'core');
		$this->_prep_locations();
		$this->config->set_item('package_locations', $this->_locations);

		log_message('info', 'CG_Router Class Initialize');

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
		$file = 'routes';
		foreach (array($file, ENVIRONMENT.DIRECTORY_SEPARATOR.$file, 'gamelang'.DIRECTORY_SEPARATOR.$file) as $location)
		{
			$file_path = APPPATH.'config/'.$location.'.php';

			if ( ! file_exists($file_path))
			{
				continue;
			}

			include($file_path);
		}

    	// Package routes.
		if ( ! empty($packages = $this->list_packages()))
		{
			foreach ($packages as $folder => $path)
			{
				// Returns TRUE if the selected plugin is enabled.
				if ( ! $this->is_enabled($folder))
				{
					continue;
				}

				if ( ! is_file($path.'config/routes.php'))
				{
					continue;
				}

				include_once($path.'config/routes.php');

				$details = $this->package_details($folder, $path);

				if (isset($details['routes']) && ! empty($details['routes']))
				{
					$this->_set_request_package($details['routes']);
					continue;
				}
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
		if (empty($this->default_controller))
		{
			show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
		}

		// Is the method being specified?
		if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2)
		{
			$method = 'index';
		}

		// Hold the controller location status.
		$controller_exists = FALSE;
		$package_controller = FALSE;

		if ($located = $this->_locate_package(array($class, $class, $method)))
		{
			// If the controller was not found, try with package.
			$controller_exists = $located;
			$package_controller = $located;
		}

		if ( ! $controller_exists && file_exists(APPPATH.'controllers/'.$this->directory.ucfirst($class).'.php'))
		{
			// Found in application? Set it to found.
			$controller_exists = TRUE;
		}

        if ( ! $controller_exists)
        {
			// This will trigger 404 error.
			return;
        }

		$this->set_class($class);
		$this->set_method($method);

		// Assign routed segments, index starting from 1
		$this->uri->rsegments = array(
			1 => $class,
			2 => $method
		);

        log_message('debug', ($package_controller ? 'No URI present. Default package controller set.' : 'No URI present. Default controller set.'));
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

        // Did the user specify a 404 override?
        if ( ! empty($this->routes['404_override']))
        {
            $segments = explode('/', $this->routes['404_override']);

            // Again, look for the controller with HMVC support.
            if ($located = $this->_locate_package($segments))
            {
                return $located;
            }
        }

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

	/**
	 * _prep_locations
	 *
	 * Method for formatting paths to packages directories.
	 */
	protected function _prep_locations()
	{
		if (isset($this->_locations))
		{
			return;
		}

		$this->_locations = $this->config->item('package_locations');

		if (null === $this->_locations)
		{
			$this->_locations = array(APPPATH.config_item('package_folder').'/');
		}
		elseif ( ! in_array(APPPATH.config_item('package_folder').'/', $this->_locations))
		{
			$this->_locations[] = APPPATH.config_item('package_folder').'/';
		}

		foreach ($this->_locations as $i => &$location)
		{
			if (false !== ($path = realpath($location)))
			{
				$location = rtrim(str_replace('\\', '/', $path), '/').'/';
				continue;
			}

			unset($this->_locations[$i]);
		}
	}

	// ------------------------------------------------------------------------

    /**
     * This method attempts to locate the controller of a package if
     * detected in the URI.
     * @access 	public
     * @param 	array 	$segments
     * @return 	array 	$segments.
     */
	protected function _locate_package($segments)
	{
    	// Let's detect package's parts first.
        list($package, $directory, $controller) = array_pad($segments, 3, NULL);

		if ($this->translate_uri_dashes === TRUE)
		{
			$package = str_replace('-', '_', $package);
			$directory = str_replace('-', '_', $directory);
			$controller = str_replace('-', '_', $controller);
		}

        // Flag to see if we are in a package.
        $is_package = false;
		$is_folder = 'admin';

        if (isset($this->_packages[$package]))
        {
			$is_package = true;
			$location   = $this->_packages[$package];
        }
        // Because of revered routes ;)
        elseif (isset($this->_packages[$directory]))
        {
			$is_package = true;
			$location   = $this->_packages[$directory];
			$_package   = $package;
			$package    = $directory;
			$directory  = $_package;
        }

        if (false === $is_package)
        {
			$package = $this->is_admin() ? $is_folder : $package;

			$path = APPPATH.'controllers/';
			$this->directory = $package.'/';

			if (is_file($path.$package.'/'.ucfirst($directory).'.php'))
			{
				return array_slice($segments, 1);
			}

			// Root folder controller?
			if (is_file($path.ucfirst($package).'.php'))
			{
				$this->directory = '';
				return $segments;
			}

			// Different controller's name?
			if ($controller && is_file($path.$package.'/'.ucfirst($controller).'.php'))
			{
				return array_slice($segments, 2);
			}

			// package sub-directory with default controller?
			if (is_file($path.$package.'/'.ucfirst($this->default_controller).'.php'))
			{
				$segments[1] = $this->default_controller;
				return array_slice($segments, 1);
			}

			return false;
		}

		if (true !== is_dir($source = $location.'controllers/'))
		{
			return false;
		}

		$relative    = rtrim(str_replace($package.'/', '', $location), '/');
		$start       = rtrim(realpath(APPPATH), '/');
		$parts       = explode('/', str_replace('\\', '/', $start));
		$parts_count = count($parts);

		for ($i = 1; $i <= $parts_count; $i++)
		{
			$relative = str_replace(
				implode('/', $parts).'/',
				str_repeat('../', $i),
				$relative,
				$count
			);

			array_pop($parts);

			if ($count)
			{
				break;
			}
		}

		$this->set_package($package);
		$this->directory = "{$relative}/{$package}/controllers/";

		// Found the controller?
		$directory = in_array($directory, [config_item('site_admin')]) ? $is_folder : $directory;
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

			if (is_file($source.ucfirst($directory).'.php'))
			{
				return array_slice($segments, 1);
			}

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

		if ($directory && is_file($source.ucfirst($directory).'.php'))
		{
			$this->set_class($directory);
			$segments[0] = $package;
			$segments[1] = $directory;
			return array_slice($segments, 1);
		}

		// package controller?
		if (is_file($source.ucfirst($package).'.php'))
		{
			return $segments;
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
	public function list_packages($details = false)
	{
		// Not cached? Cache them first.
		if ( ! isset($this->_packages))
		{
			$this->_packages = array();

			// Moved out of the foreach loop for better performance.
			$_to_eliminate = array(
				'.',
				'..',
				'.gitkeep',
				'index.html',
				'.htaccess',
				'__MACOSX',
			);

			// Let's go through folders and check if there are any.
			foreach ($this->package_locations() as $location)
			{
				if ($handle = opendir($location))
				{
					while (false !== ($file = readdir($handle)))
					{
						// Must be a directory and has "manifest.json".
						if ( ! in_array($file, $_to_eliminate)
							&& is_dir($location.$file)
							&& (is_file($location.$file."/manifest.json") OR is_file($location.$file."/manifest.json.dist")))
						{
							$this->_packages[$file] = rtrim(str_replace('\\', '/', $location.$file), '/').'/';
						}
					}
				}
			}

			// Alphabetically order packages.
			ksort($this->_packages);
		}

		$return = $this->_packages;

		if (true === $details)
		{
			$_packages_details = array();

			foreach ($this->_packages as $folder => $path)
			{
				if (isset($this->_packages_details[$folder]))
				{
					$_packages_details[$folder] = $this->_packages_details[$folder];
				}
				elseif (false !== ($details = $this->package_details($folder, $path)))
				{
					$_packages_details[$folder] = $details;
				}
			}

			empty($_packages_details) OR $return = $_packages_details;
		}

		return $return;
	}

	// ------------------------------------------------------------------------

	/**
	 * Get the list of active packages.
	 */
	public function active_packages($details = false)
	{
		// Not cached? Cache them first.
		if ( ! isset($this->_active_packages))
		{
			$packages = array();

			/**
			 * Because we are automatically assigning options from database
			 * to config array, we see if we have the item
			 */
			if (! empty(config_item('active_packages')))
			{
				$packages = config_item('active_packages');
			}

			// We make sure it's an array before finally caching it.
			is_array($packages) OR $packages = array();
			$this->_active_packages = $packages;
		}

		$list_packages = $this->list_packages();
		array_filter(array_flip($list_packages), function ($item) {
			// header
			$headers = $this->package_header($item);

			// we make sure the package is enabled!
			if ( $headers['enabled'] && ! in_array($item, $this->_active_packages)) {
				$this->_active_packages[] = $item;
			}
		});

		$return = $this->_active_packages;

		if (true === $details)
		{
			$_packages_details = array();

			foreach ($this->_active_packages as $key => $folder)
			{
				if (isset($this->_packages_details[$folder]))
				{
					$_packages_details[$folder] = $this->_packages_details[$folder];
				}
				elseif (false !== ($details = $this->package_details($folder)))
				{
					$_packages_details[$folder] = $details;
				}
			}

			empty($_packages_details) OR $return = $_packages_details;
		}

		return $return;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns TRUE if the selected package is valid.
	 * @access 	private
	 * @param 	string 	$name
	 * @return 	boolean
	 */
	public function valid_package($name = null)
	{
    	// First, we remove the extension and trim slashes.
        $class = str_replace('.php', '', trim($name, '/'));

        // Catch the position of the first slash.
        $first_slash = strpos($class, '/');

        // If there is a slash, proceed.
        if (FALSE === $first_slash)
        {
			// Nothing found?
			return FALSE;
        }

		// Get the package and class from $class.
		$package = substr($class, 0, $first_slash);
		$class  = substr($class, $first_slash + 1);

		// Make sure the package exits before returning the result.
		$packages = $this->list_packages();
		if (isset($packages[$name]))
		{
			return array($package, $class);
		}
	}

	// ----------------------------------------------------------------------------

	/**
	 * Returns and array of package locations.
	 * @access 	public
	 * @return 	array.
	 */
	public function package_locations()
	{
		isset($this->_locations) OR $this->_prep_locations();
		return $this->_locations;
	}

	// ------------------------------------------------------------------------

	/**
	 * Reads details about the plugin from the manifest.json file.
	 */
	public function package_details($folder = null, $path = null)
	{
		if (empty($folder))
		{
			$folder = $this->package;

			if (empty($folder))
			{
				return false;
			}
		}

		if (isset($this->_packages_details[$folder]))
		{
			return $this->_packages_details[$folder];
		}

		// header
		$headers = $this->package_header($folder, $path);

		// Added things:
		empty($headers['admin_menu']) && $headers['admin_menu'] = $folder;

		// Is package enabled?
		$headers['enabled'] = $this->is_enabled($folder);

		// Add all internal details.
		$headers['contexts'] = $this->package_contexts($folder, $headers['full_path']);
		if ( ! empty($headers['contexts']))
		{
			foreach ($headers['contexts'] as $key => $val)
			{
				$headers['has_'.$key] = (false !== $val);
			}
		}

		$headers['folder'] = $folder;

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
		if (false !== stripos($headers['license'], 'mit') && empty($headers['license_uri']))
		{
			$headers['license_uri'] = 'http://opensource.org/licenses/MIT';
		}

		// Cache everything before returning.
		$this->_packages_details[$folder] = $headers;
		return $headers;
	}

	// ----------------------------------------------------------------------------

	public function package_header($folder, $path = null)
	{
		$package_path = $path ? $path : $this->package_path($folder);
		$manifest_file = $package_path.'manifest.json';
		$manifest_dist = $manifest_file.'.dist';

		if ( ! $package_path
			OR ( ! is_file($manifest_file) && ! is_file($manifest_dist)))
		{
			return false;
		}

		/**
		 * In case the manifest.json is not found but we have a backup
		 * file, we make sure to create the file first.
		 */
		if (( ! is_file($manifest_file) && is_file($manifest_dist))
			&& false === copy($manifest_dist, $manifest_file))
		{
			return false;
		}

		$headers = function_exists('json_read_file')
			? json_read_file($manifest_file)
			: json_decode(file_get_contents($manifest_file), true, JSON_PRETTY_PRINT);

		if ( ! $headers OR ! is_array($headers))
		{
			return false;
		}

		/**
		 * Create a back-up for the manifest.json file if it does not exist.
		 */
		if (true !== is_file($manifest_dist)
			&& true !== copy($manifest_file, $manifest_dist))
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

		$headers['full_path'] = $package_path;
		return $headers;
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
		(null === $path) && $path = $this->package_path($package);
		if (false === $path)
		{
			return $package_contexts;
		}

		// Let's first see if the package has an admin controller.
		$package_contexts['admin'] = is_file($path.'/controllers/admin/Welcome.php');

		// Loop through contexts and see if we find a controller.
		global $back_contexts;
		foreach ($back_contexts as $context)
		{
			$package_contexts[$context] = is_file($path.'/controllers/'.$context.'/Welcome.php');
		}

		// Return the final result.
		return $package_contexts;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the real path to the selected package.
	 *
	 * @access 	public
	 * @param 	string 	$name 	package name.
	 * @return 	the full path if found, else FALSE.
	 */
	public function package_path($name = null)
	{
		if (empty($name))
		{
			$name = $this->package;

			if (empty($name))
			{
				return false;
			}
		}

		if ( ! isset($this->_packages[$name]))
		{
			$path = false;

			foreach ($this->package_locations() as $location)
			{
				if (is_dir($location.$name))
				{
					$path = $location.$name;
					break;
				}
			}

			if (false === $path)
			{
				return false;
			}

			$this->_packages[$name] = normalize_path($path.'/');
		}

		return $this->_packages[$name];
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns TRUE if the selected plugin is enabled.
	 */
	public function is_enabled($name)
	{
		$active = $this->active_packages();
		return in_array($name, $active);
	}

	// --------------------------------------------------------------------

	/**
	 * Set package name
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

	// ------------------------------------------------------------------------

	/**
	 * is_admin
	 *
	 * Method for checking if we are on the dashboard section.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	bool
	 */
	public function is_admin()
	{
		$is_admin = false;

		if (config_item('site_admin') === $this->uri->segment(1))
		{
			$is_admin = true;
		}

		// Last check for front-end Users controller.
		if (config_item('site_admin') !== $this->uri->segment(1) && 'users' === $this->class)
		{
			$is_admin = false;
		}

		return $is_admin;
	}
}