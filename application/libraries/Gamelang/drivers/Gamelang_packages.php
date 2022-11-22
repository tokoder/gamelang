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
 * Gamelang_package Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class Gamelang_packages extends CI_Driver
{
	/**
	 * Get all autoloaded options from database and assign
	 * them to CodeIgniter config array.
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function initialize()
	{
		log_message('info', 'Gamelang_packages Class Initialize');

		// Register the action after controller constructor.
		add_action('post_controller_constructor', array($this, '_load_packages'));
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns details about the selected plugin.
	 * @access 	public
	 * @param 	string 	$name 	The plugin's name.
	 * @return 	array if found, else false.
	 */
	public function get_package($name)
	{
		$packages = $this->ci->router->list_packages();
		return (isset($packages[$name])) ? $packages[$name] : false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Activate the selected plugin.
	 * @access 	public
	 * @param 	string 	$name 	The plugin's folder name.
	 * @return 	boolean
	 */
	public function activate($name)
	{
		$packages       = $this->ci->router->list_packages();
		$active_packages = $this->ci->router->get_package_active();

		if (is_array($name))
		{
			foreach ($name as $i => $p)
			{
				if ( ! isset($packages[$p]) OR in_array($p, $active_packages))
				{
					continue;
				}

				// Include their main file if found.
				$file = $packages[$p]['full_path']."main.php";
				require_once($file);

				// Do the action related to each plugin.
				do_action('package_activate_'.$p);
				$active_packages[] = $p;
			}

			return $this->_set_packages($active_packages);
		}

		if (isset($packages[$name]) && ! in_array($name, $active_packages))
		{
			// Include their main file if found.
			$file = $packages[$name]['full_path']."main.php";
			require_once($file);

			// Do the action related to each plugin.
			do_action('package_activate_'.$name);

			$active_packages[] = $name;
			return $this->_set_packages($active_packages);
		}

		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Deactivate the selected plugin.
	 * @access 	public
	 * @param 	string 	$name 	The plugin's folder name.
	 * @return 	boolean
	 */
	public function deactivate($name)
	{
		$packages        = $this->ci->router->list_packages();
		$active_packages = $this->ci->router->get_package_active();

		if (is_array($name))
		{
			foreach ($name as $i => $p)
			{
				if ( ! isset($packages[$p])
					OR ! in_array($p, $active_packages)
					OR false === ($key = array_search($p, $active_packages)))
				{
					continue;
				}

				// Include their main file if found.
				$file = $packages[$p]['full_path']."main.php";
				require_once($file);

				do_action('package_deactivate_'.$p);
				unset($active_packages[$key]);
			}

			return $this->_set_packages($active_packages);
		}

		if (isset($packages[$name])
			&& in_array($name, $active_packages)
			&& false !== ($key = array_search($name, $active_packages)))
		{
			// Include their main file if found.
			$file = $packages[$name]['full_path']."main.php";
			require_once($file);

			do_action('package_deactivate_'.$name);
			unset($active_packages[$key]);
			return $this->_set_packages($active_packages);
		}


		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete the selected plugin.
	 *
	 * @access 	public
	 * @param 	string 	$name 	The plugin's folder name.
	 * @return 	boolean
	 */
	public function delete($name)
	{
		// Make sure the packages exists.
		$packages = $this->ci->router->list_packages(false);
		$active_packages = $this->ci->router->get_package_active();

		if (is_array($name))
		{
			foreach ($name as $i => $p)
			{
				if (true !== $this->delete($p))
				{
					return false;
				}
			}

			return true;
		}

		if ( ! isset($packages[$name]) OR in_array($name, $active_packages))
		{
			return false;
		}

		// Proceed to plugin deletion after deactivation.
		$this->deactivate($name);

		if (false !== is_dir($packages[$name]))
		{
			function_exists('directory_delete') OR $this->ci->load->helper('directory');
			directory_delete($packages[$name]);
		}

		return true;
	}

	// -----------------------------------------------------------------------------

	/**
	 * Returns the full path to the currently active package,
	 */
	public function package_path($uri = '', $package = null)
	{
		static $path, $cached_paths = array();

		$package OR $package = $this->ci->router->fetch_package();

		if (is_null($path))
		{
			$this->ci->load->helper('path');
			$path = APPPATH.'packages';
			$path = path_join($path, $package);
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

	// -----------------------------------------------------------------------------

	/**
	 * Load all packages in order to do all their actions.
	 * @access 	public
	 * @return 	void
	 */
	public function _load_packages()
	{
		// Get the list of active packages.
		$packages = $this->ci->router->get_package_active(true);

		// Lopp through all packages.
		if ( ! empty($packages))
		{
			foreach ($packages as $pack)
			{
				// package enabled but folder missing? Nothing to do.
				if ( ! is_dir($pack['full_path']))
				{
					continue;
				}

				// "main.php" not found? Nothing to do.
				if ( ! is_file($pack['full_path'].'main.php'))
				{
					continue;
				}

				// Include their main file if found.
				$this->ci->load->add_package_path($pack['full_path']);

				// Include their main file if found.
				require_once($pack['full_path']."main.php");

				// Do the action related to each plugin.
				do_action('package_install_'.$pack['folder']);
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Updates or create active packages options item.
	 * @access 	private
	 * @param 	array 	$packages
	 * @return 	bool
	 */
	private function _set_packages($packages = array())
	{
		if ( ! empty($packages))
		{
			asort($packages);
			$packages = array_values($packages);
		}

		// Check if the option exists first.
		$found = $this->_parent->options->get('active_packages');

		// If found and updated, return TRUE.
		if ($found && $this->_parent->options->set_item('active_packages', $packages))
		{
			return true;
		}

		// Create it because it was not found.
		$this->_parent->options->create(
			'active_packages', $packages, 'packages'
		);
		return true;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns TRUE if the selected plugin is valid.
	 *
	 * @access 	private
	 * @param 	string 	$name
	 * @return 	boolean
	 */
	public function _is_valid($name)
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
		if ($this->ci->router->package_realpath($package) !== FALSE)
		{
			return array($package, $class);
		}
	}
}

// ------------------------------------------------------------------------
// Helpers.
// ------------------------------------------------------------------------

if ( ! function_exists('get_package_path'))
{
	/**
	 * Returns the full path to the given package.
	 *
	 * @param 	string 	$name 	The package's name.
	 * @return 	the package's path if found, else false.
	 */
	function get_package_path($name = null)
	{
		return get_instance()->packages->package_path($name);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('package_details'))
{
	/**
	 * Returns details about the given package.
	 *
	 * @param 	string 	$name 	The package's name.
	 * @param 	string 	$path 	The package path.
	 * @return 	array of package details if found, else false.
	 */
	function package_details($name = null, $path = null)
	{
		return get_instance()->router->package_details($name, $path);
	}
}

if ( ! function_exists('package_is_active'))
{
	/**
	 * Checks whether the given package is enabled.
	 *
	 * @param 	string 	$name 	The package's name.
	 * @return 	bool 	true if the package is enabled, else false.
	 */
	function package_is_active($name = null)
	{
		return get_instance()->router->is_enabled($name);
	}
}