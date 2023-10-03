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
		// Register the action after controller constructor.
		add_action('post_controller_constructor', array($this, '_load_packages'));

		log_message('info', 'Gamelang_packages Class Initialize');
	}

	// ------------------------------------------------------------------------

	/**
	 * Activate the selected package.
	 * @access 	public
	 * @param 	string 	$name 	The package's folder name.
	 * @return 	boolean
	 */
	public function activate($name)
	{
		$packages = $this->ci->router->list_packages();
		$active = $this->ci->router->active_packages();

		if (is_array($name))
		{
			foreach ($name as $i => $p)
			{
				// package don't exists or already enabled? Nothing to do...
				if ( ! isset($packages[$p]) OR in_array($p, $active))
				{
					continue;
				}

				// Include their main file if found.
				$file = $packages[$p]."main.php";
				require_once($file);

				// Fire the "package_activate_{folder}" action.
				do_action('package_activate_'.$p);

				// Add it to active packages and update database.
				$active[] = $p;
			}

			return $this->_set_packages($active);
		}

		// package don't exists or already enabled? Nothing to do...
		if ( ! isset($packages[$name]) OR in_array($name, $active))
		{
			return false;
		}

		// Include their main file if found.
		$this->ci->load->add_package_path($packages[$name]);

		// Include their main file if found.
		$file = $packages[$name]."main.php";
		require_once($file);

		// Fire the "package_activate_{folder}" action.
		do_action('package_activate_'.$name);

		// Add it to active packages and update database.
		$active[] = $name;

		return $this->_set_packages($active);
	}

	// ------------------------------------------------------------------------

	/**
	 * Deactivate the selected package.
	 * @access 	public
	 * @param 	string 	$name 	The package's folder name.
	 * @return 	boolean
	 */
	public function deactivate($name)
	{
		$packages = $this->ci->router->list_packages();
		$active = $this->ci->router->active_packages();

		if (is_array($name))
		{
			foreach ($name as $i => $p)
			{
				// The package must exists and must be enabled to proceed.
				if ( ! isset($packages[$p])
					OR ! in_array($p, $active)
					OR false === ($key = array_search($p, $active)))
				{
					continue;
				}

				// Include their main file if found.
				$file = $packages[$p]."main.php";
				require_once($file);

				// Fire the "package_deactivate_{folder}" action.
				do_action('package_deactivate_'.$p);

				// we use the $i (index) to remove the package.
				unset($active[$key]);
			}

			return $this->_set_packages($active);
		}

		// The package must exists and must be enabled to proceed.
		if ( ! isset($packages[$name])
			OR ! in_array($name, $active)
			OR false === ($key = array_search($name, $active)))
		{
			return false;
		}

		// Include their main file if found.
		$file = $packages[$name]."main.php";
		require_once($file);

		// Fire the "package_deactivate_{folder}" action.
		do_action('package_deactivate_'.$name);

		// we use the $i (index) to remove the package.
		unset($active[$key]);

		return $this->_set_packages($active);
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete the selected package.
	 *
	 * @access 	public
	 * @param 	string 	$name 	The package's folder name.
	 * @return 	boolean
	 */
	public function delete($name)
	{
		// Make sure the packages exists.
		$packages = $this->ci->router->list_packages();
		$active = $this->ci->router->active_packages();

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

		if ( ! isset($packages[$name]) OR in_array($name, $active))
		{
			return false;
		}

		// Proceed to package deletion after deactivation.
		$this->deactivate($name);

		if (false !== is_dir($packages[$name]))
		{
			function_exists('directory_delete') OR $this->ci->load->helper('directory');
			directory_delete($packages[$name]);
		}

		return true;
	}

	// ----------------------------------------------------------------------------

	/**
	 * Returns the URL to the currently active package, whether it's the front-end
	 * package or the dashboard package.
	 * @access 	public
	 * @param 	string 	$uri
	 * @param 	string 	$protocol
	 * @return 	string
	 */
	public function package_url($uri = '', $protocol = null, $package = null)
	{
		static $base_url, $_protocol, $cached_uris;

		$package OR $package = $this->ci->router->fetch_package();

		if (empty($base_url) OR $_protocol !== $protocol)
		{
			$_protocol = $protocol;
			$base_url = path_join(base_url('gamelang/packages', $_protocol), $package);
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
	 * Load all packages in order to do all their actions.
	 * @access 	public
	 * @return 	void
	 */
	public function _load_packages()
	{
		// Get the list of active packages.
		$active = $this->ci->router->active_packages(true);
		if (empty($active))
		{
			return;
		}

		// Lopp through all packages.
		foreach ($active as $folder => $m)
		{
			// package enabled but folder missing? Nothing to do.
			if ( ! is_dir($m['full_path']))
			{
				continue;
			}

			// "main.php" not found? Nothing to do.
			if ( ! is_file($m['full_path'].'main.php'))
			{
				continue;
			}

			// Include their main file if found.
			$this->ci->load->add_package_path($m['full_path']);

			// Include their main file if found.
			require_once($m['full_path']."main.php");

			// We always fire this action.
			do_action('package_loaded_'.$folder);
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

		// Update an option item if it exists or create it if it does not.
		if ($this->_parent->options->set_item('active_packages', $packages))
		{
			return true;
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
	 */
	function get_package_path($name = null)
	{
		return get_instance()->router->package_path($name);
	}
}

// -----------------------------------------------------------------------------

if ( ! function_exists('get_package_url'))
{
	/**
	 * Returns the URL to the package folder.
	 * @param   string  $uri    string to be appended.
	 * @param   string  $protocol
	 * @return  string.
	 */
	function get_package_url($uri = '', $protocol = null, $package = null)
	{
		return get_instance()->packages->package_url($uri, $protocol, $package);
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

// -----------------------------------------------------------------------------

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

// -----------------------------------------------------------------------------

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