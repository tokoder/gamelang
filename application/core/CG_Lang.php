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
 * CG_Lang Class
 *
 * This class extends CI_Lang class in order en add, override or
 * enhance some of the parent's methods.
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class CG_Lang extends CI_Lang
{
    /**
     * default lang file that will be created if it doesn't exist
     * and will be used to automatically add any missing strings
     */
    public $default_lang_file = 'game_lang.php';

	/**
	 * Holds an array of language details.
	 * @var 	array
	 */
	public $details = array(
		'name'      => 'Indonesia',
		'name_en'   => 'Indonesia',
		'folder'    => 'indonesia',
		'locale'    => 'id-ID',
		'gettext'   => 'id_ID',
		'direction' => 'ltr',
		'code'      => 'id',
		'flag'      => 'id',
	);

	/**
	 * Class constructor.
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();

		// Store language in session and change config item.
		$this->_set_language();

		log_message('info', 'CG_Language Class Initialized');
	}

	// -----------------------------------------------------------------------------

    /**
     * Load a language file
     *
     * @param	mixed	$langfile	Language file name
     * @param	string	$idiom		Language name (english, etc.)
     * @param	bool	$return		Whether to return the loaded array of translations
     * @param 	bool	$add_suffix	Whether to add suffix to $langfile
     * @param 	string	$alt_path	Alternative path to look for the language file
     *
     * @return	void|string[]	Array containing translations, if $return is set to TRUE
     */
    public function load($langfile, $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '')
	{
        if (is_array($langfile))
		{
            foreach ($langfile as $value)
			{
                $this->load($value, $idiom, $return, $add_suffix, $alt_path);
            }

            return;
        }

        $langfile = str_replace('.php', '', $langfile);

        if ($add_suffix === TRUE)
		{
            $langfile = preg_replace('/_lang$/', '', $langfile) . '_lang';
        }

        $langfile .= '.php';

        if (empty($idiom) OR ! preg_match('/^[a-z_-]+$/i', $idiom))
		{
            $config = & get_config();
            $idiom = empty($config['language']) ? 'english' : $config['language'];
        }

        if ($return === FALSE && isset($this->is_loaded[$langfile]) && $this->is_loaded[$langfile] === $idiom)
		{
            return;
        }

        // Load the base file, so any others found can override it
		$basepath = BASEPATH.'language/'.$idiom.'/'.$langfile;
        if (($found = file_exists($basepath)) === TRUE)
		{
            include($basepath);
        }

        // Do we have an alternative path to look in?
		if ($alt_path !== '')
		{
			$alt_path .= 'language/'.$idiom.'/'.$langfile;
			if (file_exists($alt_path))
			{
				include($alt_path);
				$found = TRUE;
			}
		}
		else
		{
			foreach (get_instance()->load->get_package_paths(TRUE) as $package_path)
			{
				$package_path .= 'language/'.$idiom.'/'.$langfile;
				if ($basepath !== $package_path && file_exists($package_path))
				{
					include($package_path);
					$found = TRUE;
					break;
				}
			}
		}

        if ($found !== TRUE)
		{
            log_message('error', 'Unable to load the requested language file: language/' . $idiom . '/' . $langfile);
        }

		if ( ! isset($lang) OR ! is_array($lang))
		{
			log_message('error', 'Language file contains no data: language/'.$idiom.'/'.$langfile);

			if ($return === TRUE)
			{
				return array();
			}
			return;
		}

		if ($return === TRUE)
		{
			return $lang;
		}

		$this->is_loaded[$langfile] = $idiom;
		$this->language = array_merge($this->language, $lang);

		log_message('info', 'Language file loaded: language/'.$idiom.'/'.$langfile);
		return TRUE;
    }

	/**
	 * Language line
	 *
	 * Fetches a single line of text from the language array
	 *
	 * @param	string	$line		Language line key
	 * @param	bool	$log_errors	Whether to log an error message if the line is not found
	 * @return	string	Translation
	 */
	public function line($line, $index = '', $log_errors = TRUE)
	{
		$line = strtolower(str_replace(' ', '_', $line));

		if ($index == '')
		{
			$value = (isset($this->language[$line]))
				? $this->language[$line]
				: false;
		}
		else
		{
			$value = (isset($this->language[$index][$line]))
				? $this->language[$index][$line]
				: false;
		}

        if ($value === FALSE)
		{
            $default_path = APPPATH . 'language/' . $this->lang() . '/' . $this->default_lang_file;

			$this->create_lang_file($default_path);

            $this->add_to_lang_file($default_path, $line);

			include($default_path);

			$this->language = array_merge($this->language, $lang);

            return $this->language[$line];
        }

		// Because killer robots like unicorns!
		if ($value === FALSE && $log_errors === TRUE)
		{
			log_message('error', 'Could not find the language line "'.$line.'"');
		}

        return $value;
	}

    /**
     * create a language file
     *
     * @param   string  $file   path of language file
     */
    function create_lang_file($file)
	{
		if (file_exists($file) !== FALSE) return;

		$content = <<<EOT
		<?php
		defined('BASEPATH') OR exit('No direct script access allowed');
		EOT;

        try {
            file_put_contents($file, $content . PHP_EOL);
        } catch (Exception $exc) {
            log_message('error', 'Could not create lang file: "' . $file . '"');
        }
    }

    /**
     * add line to language file array
     *
     * @param   string  $file   path of language file
     * @param   string  $line   line (key) to add to file
     */
    function add_to_lang_file($file, $line)
	{
		$lang = str_replace('lang_', '', $line);
		$lang = ucfirst(str_replace('_', ' ', $lang));
		$line = strtolower(str_replace(' ', '_', $line));

        try {
            $file_contents = file_get_contents($file);
            $pattern = '~\$lang\[(\'|")' . preg_quote($line) . '(\'|")\]~';
            if (!preg_match($pattern, $file_contents))
			{
                $data = '$lang[\'' . addcslashes($line, '\'') . '\'] = "' . addcslashes($lang, '"') . '";';
                file_put_contents($file, PHP_EOL . $data, FILE_APPEND);
            }

            $file_contents = trim(file_get_contents($file));
            $pattern = '/<\?php/';
            if (!preg_match($pattern, $file_contents))
			{
                $content = '<?php ' . $file_contents;
                file_put_contents($file, $content);
            }
        } catch (Exception $exc) {
            log_message('error', 'Could not edit lang file: "' . $file . '"');
        }
    }

    /**
     * Returns the current language
     */
    function lang()
	{
        global $CFG;
        $language = $CFG->item('language');

		return $language;
    }

	// ------------------------------------------------------------------------

	/**
	 * Returns the name or details about the language currently in use.
	 * @access 	public
	 * @param 	mixed 	what to retrieve.
	 * @return 	mixed
	 */
	public function lang_detail()
	{
		// Make the method remember language details.
		static $details;

		is_null($details) && $details = $this->details;

		$return = $this->details;

		// The language was not found?
		if ( ! $return)
		{
			return false;
		}

		// Not arguments passed? Return the language folder.
		if (empty($args = func_get_args()))
		{
			return $return['folder'];
		}

		// Get rid of nasty array.
		(is_array($args[0])) && $args = $args[0];
		$args_count = count($args);

		// In case of a boolean, we return all language details.
		if ($args_count == 1 && $args[0] === true)
		{
			return $return;
		}

		// In case of a single item and found, return it.
		if ($args_count === 1 && isset($return[$args[0]]))
		{
			return $return[$args[0]];
		}

		// Multiple arguments?
		if ($args_count >= 2)
		{
			$_return = array();

			foreach ($args as $arg)
			{
				isset($return[$arg]) && $_return[$arg] = $return[$arg];
			}

			empty($_return) OR $return = $_return;
		}

		return $return;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns an array of languages details.
	 *
	 * @param 	mixed 	$langs 	String, comma-separated strings or array.
	 * @return 	array
	 */
	public function lang_lists($langs = null)
	{
        global $CFG;
		static $languages;

		// Not cached? Cache them if found.
		if (empty($languages))
		{
			$languages = $CFG->load('cg_translate', true, true)
				? $CFG->config['cg_translate']
				: [];
		}

		$return = $languages;

		// No argument? return all languages.
		if (null === $langs)
		{
			return $return;
		}

		// Format our requested languages.
		( ! is_array($langs)) && $langs = array_map('trim', explode(',', $langs));

		if ( ! empty($langs))
		{
			// Build requested languages array.
			$_languages = array();
			foreach ($langs as $lang)
			{
				if (isset($languages[$lang]))
				{
					$_languages[$lang] = $languages[$lang];
				}
			}

			empty($_languages) OR $return = $_languages;
		}

		return $return;
	}

	// ------------------------------------------------------------------------

	/**
	 * Make sure to store language in session.
	 *
	 * @param 	none
	 * @return 	void
	 */
	protected function _set_language()
	{
        global $CFG;

		// We make sure to load session first.
		static $session;

		if (is_null($session) OR ! isset($_SESSION))
		{
			$session =& load_class('Session', 'libraries/Session');
		}

		// Site available language and all languages details.
		$site_languages = $CFG->item('languages');
		$languages = $this->lang_lists();

		// Current and default language.
		$default = $CFG->item('language');
		$current = isset($_SESSION['language']) ? $_SESSION['language'] : $default;

        /**
         * In case the language is not stored in session or is not available;
         * we attempt to detect clients language. If available, we use it
         * instead of the default language.
         */
		if ( ! isset($_SESSION['language'])
			OR ! in_array($_SESSION['language'], $site_languages))
		{
			$code = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
				? substr(html_escape($_SERVER['HTTP_ACCEPT_LANGUAGE']), 0, 2)
				: 'id';

			foreach ($languages as $folder => $lang)
			{
                /**
                 * In order for the language to be used, the code must exists and
                 * the language must be available.
                 */
				if (isset($lang['code'])
					&& $code === $lang['code']
					&& in_array($folder, $site_languages))
				{
					$current = $folder;
					break;
				}
			}

			$_SESSION['language'] = $current;
		}

		$this->details = $languages[$current];
		$CFG->set_item('current_language', $this->details);
		$CFG->set_item('language', $current);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('__'))
{
    /**
     * Alias of CG_Lang::line with optional arguments.
     *
     * @param 	string 	$line 		the line the retrieve.
     * @param 	string 	$index 		whether to look under an index.
     * @param 	string 	$before 	Whether to put something before the line.
     * @param 	string 	$after 		Whether to put something after the line.
     * @return 	string
     */
    function __($line, $index = '', $before = '', $after = '')
    {
        // Shall we translate the before?
        if ('' !== $before && 1 === sscanf($before, 'lang:%s', $b_line))
        {
            $before = __($b_line, $index);
        }

        // Shall we translate the after?
        if ('' !== $after && 1 === sscanf($after, 'lang:%s', $a_line))
        {
            $after = __($a_line, $index);
        }

        return $before.get_instance()->lang->line($line, $index).$after;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('_e'))
{
	/**
	 * Alias of CG_Lang::line with optional arguments.
	 *
	 * @param 	string 	$line 		the line the retrieve.
	 * @param 	string 	$index 		whether to look under an index.
	 * @param 	string 	$before 	Whether to put something before the line.
	 * @param 	string 	$after 		Whether to put something after the line.
	 * @return 	string
	 */
	function _e($line, $index = '', $before = '', $after = '')
	{
		echo __($line, $index, $before, $after);
	}
}