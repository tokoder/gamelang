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

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Assets
 *
 * @category 	Asset Management
 * @author		Tokoder Team, who hacked at it a bit.
 * @author		Jack Boberg
 * @link        https://github.com/jackboberg/CodeIgniter-Assets
 */
class Assets extends CI_Driver_Library {

    /**
     * valid drivers
     *
     * @var array
     */
    public $valid_drivers = array('css', 'js');

    protected $cache_dir    = 'cache/';

    protected $script_dirs  = 'assets/js/';

    protected $style_dirs   = 'assets/css/';

    protected $assets_dirs  = array();

    protected $static_cache = FALSE;

    protected $auto_update  = TRUE;

    protected $cache        = NULL;

    private $ci;

    private $current_group  = NULL;

    private $groups         = array();

    private $store          = array();

    // --------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access  public
     * @param   array   $config     variables to override in library
     *
     * @return  void
     */
    public function __construct($config = array())
    {
        $this->ci = get_instance();

        if (count($config) > 0)
        {
            $this->initialize($config);
        }

		// Always delete cache.
		$this->_delete_cache();

        // File helper
		function_exists('write_file') OR $this->ci->load->helper('file');

        log_message('debug', 'Assets Library initialized.');
    }

	// ------------------------------------------------------------------------

	/**
	 * This method handles old cached assets deletion.
	 * @access 	protected
	 * @return 	void
	 */
	protected function _delete_cache()
	{
		// Prepare the path to to assets folder.
		$path = realpath(FCPATH . $this->cache_dir);

		// Let's open the folder to read.
		if ($handle = opendir($path))
		{
			// Files to ignore.
			$ignore = array('.', '..', '.htaccess', '.gitkeep', 'index.html');

			// Loop through all files.
			while(false !== ($file = readdir($handle)))
			{
				// We ignore unneeded or invalid files.
				if (in_array($file, $ignore) OR '.' === $file[0])
				{
					continue;
				}

				// If the file is older than 24 hours, we delete file.
				if (filemtime($path.DIRECTORY_SEPARATOR.$file) < (time() - 86400))
				{
					@unlink($path.$file);
				}
			}

			closedir($handle);
		}
	}

    // --------------------------------------------------------------------

    /**
     * Initialize the configuration options
     *
     * @access  public
     * @param   array   $config     variables to override in library
     *
     * @return  void
     */
    public function initialize($config = array())
    {
        foreach ($config as $key => $val)
        {
            if (method_exists($this, 'set_'.$key))
            {
                $this->{'set_'.$key}($val);
            }
            else if (isset($this->$key))
            {
                $this->$key = $val;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Add an asset to the store
     *
     * @access  public
     * @param   mixed   $assets (string)    path to asset
     *                          (array)     multiple assets
     *                                      single asset with minified version
     * @param   string  $group              name of asset group
     *
     * @return void
     **/
    public function add($assets, $group = NULL)
    {
        // convert strings to array for simplicty
        if ( ! is_array($assets))
        {
            $assets = array($assets);
        }
        // ensure the group is a string
        if ( ! is_string($group))
        {
            $group = 'main';
        }
        // create the group if needed
        if ( ! isset($this->store[$group]))
        {
            $this->store[$group] = array(
                'css'   => array(),
                'js'    => array()
            );
        }
        $group =& $this->store[$group];

        // let's get to adding!
        foreach ($assets as $key => $value)
        {
            $asset = array();
            // did the user provide a minified version?
            if (is_int($key))
            {
                $asset['path'] = $value;
            }
            else
            {
                $asset['path'] = $key;
                $asset['min'] = $value;
            }
            // what kind of asset is this?
            $type = (substr($asset['path'], -3) == 'css') ? 'css' : 'js';
            // ensure the file is not already present
            $hash = md5($asset['path']);
            if (in_array($hash, (array) $group[$type]))
            {
                continue;
            }
            // add it to the store!
            $group[$type][$hash] = $asset;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Combine existing groups into new group
     *
     * @access  public
     * @param   string  $group      name of new group
     * @param   array   $groups     names of groups to combine
     *
     * @return void
     **/
    public function group($group, $groups)
    {
        // create the group if needed
        if ( ! isset($this->groups[$group]))
        {
            $this->groups[$group] = array();
        }
        $group =& $this->groups[$group];
        // add a reference to the existing groups
        // we combine assets on output
        foreach ($groups as $g)
        {
            // don't duplicate
            if ( ! in_array($g, (array) $group))
            {
                $group[] = $g;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Get all assets stored for this group
     *
     * @access  public
     * @param   string  $group  name of group
     *
     * @return  array
     **/
    public function get_group_assets($group)
    {
        $css = array();
        $js = array();
        // first look in the store
        if (isset($this->store[$group]))
        {
            $css = $this->store[$group]['css'];
            $js = $this->store[$group]['js'];
        }
        // is there a meta-group by the same name?
        if (isset($this->groups[$group]))
        {
            // get the assets for each group
            foreach ($this->groups[$group] as $g)
            {
                $assets = $this->get_group_assets($g);
                foreach (array('css', 'js') as $type)
                {
                    foreach ($assets[$type] as $hash => $a)
                    {
                        // no duplicates
                        if ( ! isset(${$type}[$hash]))
                        {
                            ${$type}[$hash] = $a;
                        }
                    }
                }
            }
        }
        return array('css'=>$css,'js'=>$js);
    }

    // --------------------------------------------------------------------

    /**
     * get links to stored assets for this group, of specified type
     *
     * @access  public
     * @param   string  $group      name of the group
     * @param   array   $config     optional settings
     * @param   string  $type       asset type
     *
     * @return  string
     **/
    public function get_assets($group = NULL, $config = array(), $type = NULL)
    {
        if (is_null($group))
        {
            $group = 'main';
        }
        if (empty($this->current_group))
        {
            $this->current_group = $group;
        }
        $output = '';
        if (is_null($type))
        {
            $output .= $this->get_assets($group, $config, 'css');
            $output .= $this->get_assets($group, $config, 'js');
            return $output;
        }
        // do we have assets of this type?
        $assets = $this->get_group_assets($group);
        if (empty($assets[$type]))
        {
            return $output;
        }

        // get the output
        switch ($type)
        {
            case 'css':
                // is there a specified media type?
                $media = isset($config['media'])
                    ? $config['media']
                    : 'all'
                    ;
                $output .= $this->get_links('css', $assets['css'], $media);
                break;
            case 'js':
                $output .= $this->get_links('js', $assets['js']);
                break;
        }
        $this->current_group = NULL;
        return $output;
    }

    // --------------------------------------------------------------------

    /**
     * get HTML for assets
     *
     * @access  private
     * @param   string  $type       asset type
     * @param   array   $assets     assets to process
     * @param   bool    $combine    toggle combining assets
     * @param   string  $media      CSS media attribute
     *
     * @return  string
     **/
    private function get_links($type, $assets, $media = NULL)
    {
        return (ENVIRONMENT !== 'development')
            ? $this->get_combined_minified_link($type, $assets, $media)
            : $this->get_combined_link($type, $assets, $media);
    }

    // --------------------------------------------------------------------

    /**
     * get combined link
     *
     * @access  private
     * @param    string    $type       asset type
     * @param    array    $assets     array of assets
     * @param    string    $media      CSS media attribute
     *
     * @return    string
     **/
    private function get_combined_link($type, $assets, $media)
    {
        // check for cached file
        $filename = $this->get_cache_filename($type, $assets);
        $modified = $this->get_last_modified($type, $assets);
        $filepath = FCPATH . $this->cache_dir . $filename;
        if ( ! is_file($filepath)
            || ($this->static_cache && $modified) > filemtime($filepath)
        ){
            // build filedata
            $filedata = '';
            foreach ($assets as $a)
            {
                $filedata .= $this->get_file($a['path'], $type);
            }

            // write to cache
            if ( ! write_file($filepath, $filedata))
            {
                return FALSE;
            }

            $this->update_cache($assets, $filename);
        }

        return $this->tag($type, $filename, TRUE, $media);
    }

    // --------------------------------------------------------------------

    /**
     * get minified and combined link
     *
     * @access  private
     * @param    string    $type       asset type
     * @param    array    $assets     array of assets
     * @param    string    $media      CSS media attribute
     *
     * @return    string
     **/
    private function get_combined_minified_link($type, $assets, $media)
    {
        // build array of minified file paths
        $min_assets = array();
        foreach ($assets as $hash => $a)
        {
            // is these a pre-minified version available
            if (isset($a['min']))
            {
                $min_assets[$hash]['path'] = $a['min'];
            }
            else
            {
                $min_assets[$hash]['path'] = $this->get_minified_path($type, $a['path']);
            }
        }
        // check for cached file
        $filename = $this->get_cache_filename($type, $min_assets);
        $filepath = FCPATH . $this->cache_dir . $filename;
        if ( ! is_file($filepath)
            || ($this->static_cache && $this->get_last_modified($type,$assets) > filemtime($filepath))
        ) {
            // call method to generate files
            $this->get_minified_links($type, $assets, $media);

            // combine new assets array
            return $this->get_combined_link($type, $min_assets, $media);
        }

        return $this->tag($type, $filename, TRUE, $media);
    }

    // --------------------------------------------------------------------

    /**
     * get minified links
     *
     * @access  private
     * @param    string    $type       asset type
     * @param    array    $assets     array of assets
     * @param    string    $media      CSS media attribute
     *
     * @return    string
     **/
    private function get_minified_links($type, $assets, $media)
    {
        $output = '';
        foreach ($assets as $a)
        {
            // is these a pre-minified version available
            if ( ! isset($a['min']))
            {
                // have we minified this file in the past
                $dir = $this->get_path($a['path'],$type);
                $min_path = $this->get_minified_path($type, $a['path']);
                $min_path = str_replace($dir, '', $min_path);
                if ( ! is_file($dir . $min_path))
                {
                    // minify the file and write to path
                    $this->minify($type, $a['path'], $dir . $min_path);
                }
                else
                {
                    $orig_path = str_replace($dir, '', $a['path']);

                    // is the original file newer
                    $min_info  = get_file_info($dir . $min_path);
                    $orig_info = get_file_info($dir . $orig_path);
                    if ($orig_info['date'] > $min_info['date'])
                    {
                        // re-minify the file and write to path
                        $this->minify($type, $a['path'], $dir . $min_path);
                    }
                }
                $a['min'] = $min_path;
            }
            $output .= $this->tag($type, $a['min'], FALSE, $media);
        }
        return $output;
    }

    // --------------------------------------------------------------------

    /**
     * get path of locally stored minified version
     *
     * @access  private
     * @param    string    $type       asset type
     * @param   string  $path       path to original
     *
     * @return  string
     **/
    private function get_minified_path($type, $path)
    {
        if (filter_var($path, FILTER_VALIDATE_URL))
        {
            // remote path, just get the filename
            $filename = substr(strrchr($path, '/'), 1);
            return substr($filename, 0, strrpos($filename, '.')) . '.min.' . $type;
        }
        else
        {
            // local path, include original file location
            $filename = $path;
            return strrpos($filename, '.min')
                ? $filename
                : substr($filename, 0, strrpos($filename, '.')) . '.min.' . $type;
        }
    }

    // --------------------------------------------------------------------

    /**
     * minify asset
     *
     * @access    private
     * @param    string  $type       asset type
     * @param    string    $path       path to original
     * @param   string  $min_path   path to save minifed version
     *
     * @return  bool
     **/
    public function minify($type, $path, $min_path)
    {
        $contents = $this->get_file($path, $type);

        // ensure we have some content
        if ( ! $contents)
        {
            return FALSE;
        }
        // minimize the contents
        $output = '';
        switch($type)
        {
            case 'js':
                $output .= $this->js->min($contents);
                break;
            case 'css':
                $output .= $this->css->min($contents);
                break;
        }
        // write the minimized content to file
        return write_file($min_path, $output);
    }

    // --------------------------------------------------------------------

    /**
     * get the hashed filename for these assets
     *
     * @access  private
     * @param    string    $type       asset type
     * @param    array    $assets     array of assets
     *
     * @return  string
     **/
    private function get_cache_filename($type, $assets)
    {
        if ( ! $this->auto_update)
        {
            // have we loaded the store
            if (is_null($this->cache))
            {
                $this->cache = array();
                if ($filedata = $this->read_file(FCPATH . $this->cache_dir . 'store.json'))
                {
                    $this->cache = json_decode($filedata, TRUE);
                }
            }
        }
        // look up filename in cache
        if ($this->static_cache)
        {
            $hash = $this->current_group;
            if ($this->{$type})
            {
                $hash .= '.min';
            }
        }
        else
        {
            $hash = md5(json_encode($assets));
            if (isset($this->cache[$hash]))
            {
                return $this->cache[$hash];
            }
            $modified = $this->get_last_modified($type, $assets);
            $hash = md5(json_encode($assets) . $modified);
        }
        // generate hashed filename based on modification date
        return  $hash . '.' . $type;
    }

    // --------------------------------------------------------------------

    /**
     * record this filename in cache for fast lookups
     *
     * @access  private
     * @param   array   $assets     assets being cached
     * @param   string  $filename   name of cache file
     *
     * @return  void
     **/
    private function update_cache($assets, $filename)
    {
        // should we be ignoring the cache
        if ($this->auto_update )
        {
            return;
        }

        // build the store from file
        $store = array();
        $filedata = $this->read_file(FCPATH . $this->cache_dir . 'store.json');
        if ($filedata)
        {
            $store = json_decode($filedata, TRUE);
        }

        // create/update the record for these assets
        $hash = md5(json_encode($assets));
        $store[$hash] = $filename;

        // write it back to file
        $filedata = json_encode($store);
        write_file(FCPATH . $this->cache_dir . 'store.json', $filedata);
    }

    // --------------------------------------------------------------------

    /**
     * get timestamp of most recently modified file
     *
     * @access  private
     * @param   array   $assets     files to examine
     *
     * @return  string
     **/
    private function get_last_modified($type, $assets)
    {
        $timestamp = 0;
        foreach ($assets as $a)
        {
            // only check local files
            if ( ! filter_var($a['path'], FILTER_VALIDATE_URL))
            {
                $path = normalize_path($this->get_path($a['path'], $type));
                if (is_file($path))
                {
                    $timestamp = max($timestamp, filemtime($path));
                }
            }
        }
        return $timestamp;
    }

    // --------------------------------------------------------------------

    /**
     * get HTML tag for asset
     *
     * @access    private
     * @param    string    $type   asset type
     * @param    string    $path   path to asset
     * @param    bool    $cache  toggle for using cache directory
     * @param    string    $media  CSS media attribute
     *
     * @return    string
     **/
    private function tag($type, $path, $cache = FALSE, $media = NULL)
    {
        $output = '';

        // is this a local path?
        $url = $path;
        if ( ! filter_var($path, FILTER_VALIDATE_URL))
        {
            if ($cache)
            {
                $dir = $this->cache_dir;
            }
            else
            {
                $dir = $this->get_path($path,$type);
            }

            $url = base_url($dir . $path);
            if ($this->static_cache)
            {
                $url .= '?cache=' . filemtime($dir . $path);
            }
        }

        switch($type)
        {
            case 'css':
                $output .= '<link type="text/css" rel="stylesheet" href="'
                    . $url
                    . '" media="' . $media
                    . '" />';
                break;
            case 'js':
                $output .= '<script type="text/javascript" src="'
                    . $url
                    . '"></script>';
                break;
        }

        return $output;
    }

    // --------------------------------------------------------------------

    /**
     * Opens the file specfied in the path and returns it as a string
     *
     * this is a duplicate of the file_helper method, without a check
     * for file_exists()
     *
     * @access    private
     * @param    string  $file   path to file
     *
     * @return    string
     **/
    public function read_file($file)
    {
        if (function_exists('file_get_contents'))
        {
            return file_get_contents($file);
        }

        if ( ! $fp = @fopen($file, FOPEN_READ))
        {
            return FALSE;
        }

        flock($fp, LOCK_SH);

        $data = '';
        if (filesize($file) > 0)
        {
            $data =& fread($fp, filesize($file));
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        return $data;
    }

    // --------------------------------------------------------------------

    /**
     * find and return file from correct directory
     *
     * @param   string  $file  filename
     * @param   string  $ext  file extension
     * @access  public
     *
     * @return  string
     **/
    public function get_file($file, $ext)
    {
        // read file into variable
        if (filter_var($file, FILTER_VALIDATE_URL))
        {
            // use modified read_file for remote files
            $result = $this->read_file($file);
        }
        else
        {
            if( ! $path = $this->get_path($file, $ext))
            {
                return FALSE;
            }

            $path = is_file($file) ? $file : $path . $file;

            $read_f = $this->read_file($path);
            $path_f = rtrim(str_replace(normalize_path(APPPATH), 'gamelang/', $path), '/');

            $result = str_replace('url("./' ,   'url("/'.$path_f.'/../',        $read_f);
            $result = str_replace('url("../',   'url("/'.$path_f.'/../../',     $result);
            $result = str_replace('url(\'../',  'url(\'/'.$path_f.'/../../',    $result);
            $result = str_replace('url(../' ,   'url(/'.$path_f.'/../../',      $result);
            $result = str_replace('url(font',   'url(/'.$path_f.'/../font',     $result);
        }

        return $result;
    }

    // --------------------------------------------------------------------

    /**
     * get file path of asset
     *
     * @param   string  file to search for
     * @access  public
     *
     * @return string  path to file
     **/
    public function get_path($file, $ext)
    {
        // for local files use the system read_file
        switch ($ext)
        {
            case 'css':
                $dir = $this->style_dirs;
                break;
            default:
                $dir = $this->script_dirs;
                break;
        }

        $theme_path = get_theme_path('/');
        if (False !== strpos($file, (string)$theme_path))
        {
            return $theme_path;
        }

        $package_paths = $this->ci->load->get_package_paths();
        foreach ($package_paths as $package_path)
        {
            if (file_exists($package_path . $file))
            {
                return $package_path;
            }

            $package_path .= '/'.$dir;
            if (file_exists($package_path . $file))
            {
                return $package_path;
            }
        }

        $view_path = normalize_path(VIEWPATH);
        if (file_exists($view_path . $file))
        {
            return $view_path;
        }

        $view_path .= $dir;
        if (file_exists($view_path . $file))
        {
            return $view_path;
        }

        return FALSE;
    }
}

// --------------------------------------------------------------------

if ( ! function_exists('add_script'))
{
	/**
	 * Adds JavaScript files to view.
	 */
	function add_script($file, $group = 'script')
	{
		return get_instance()->assets->add($file, $group);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('add_style'))
{
	/**
	 * Adds StyleSheets to view.
	 * @param   string  $handle     used as identifier.
	 * @param   string  $file       the css file.
	 * @param   int     $ver        the file's version.
	 * @param   bool    $prepend    whether to put the file at the beggining or not
	 * @param   array   $attrs      array of attributes to add.
	 * @return  object
	 */
	function add_styles($file, $group = 'style')
	{
		return get_instance()->assets->add( $file, $group);
	}
}