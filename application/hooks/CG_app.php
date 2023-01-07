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
 * Domain Main
 *
 * @var	string
 */
const ROUTE_DOMAIN_NAME = 'master-ci3.test';

/**
 * CodeIgniter Gamelang Version
 *
 * @var	string
 */
const CG_VERSION = '1.0.0-beta.4';

/**
 * Bootstrap File.
 *
 * File ini mendaftarkan class agar dapat dengan mudah dimuat/diperpanjang.
 *
 * @subpackage 	Hooks
 * @author		Tokoder Team
 */
class CG_app
{
	/**
	 * Class constructor
	 * @return	void
	 */
	public function __construct()
	{
		// mari kita cek file htaccess
		if ( ! file_exists(FCPATH . ".htaccess"))
		{
			// tampilkan informasi tentang htaccess
			echo ".htaccess file does not exist on main directory of your site. You can find this file in the main directory of script files. You need to upload this file to your site.<br>";
			echo "Depending on the operating system you are using, such setting files may be hidden in your computer. In this case, you may not see this file.<br><br>";
			echo "If you can't see this file, you can create a new file named \".htaccess\" in the main directory of your site and you can paste the following codes to your .htaccess file.<br><br>";
			echo "<strong>RewriteEngine On<br>";
			echo "RewriteCond %{REQUEST_FILENAME} !-f<br>";
			echo "RewriteCond %{REQUEST_FILENAME} !-d<br>";
			echo "RewriteRule ^(.*)$ index.php?/$1 [L]</strong>";
			exit();
		}
	}

	// -----------------------------------------------------------------------------

	/**
	 * Denfinisikan semua constants
	 */
	function init()
	{
		$_config =& load_class('Config', 'core');
		$_load =& load_class('Loader', 'core');

		// Gabungkan nilai konfigurasi
		global $assign_to_config;

		// Do we have any manually set config items in the index.php file?
		if (isset($assign_to_config) && is_array($assign_to_config))
		{
			foreach ($assign_to_config as $key => $value)
			{
				$_config->set_item($key, $value);
			}
		}

		// Setup default constants.
		self::constants();

		// Load some base functions that we added to CodeIgniter.
		$_load->helper(['string', 'array', 'path']);

		/**
		 * Site contexts.
		 *
		 * Ada 2 kategori contexts: back-end and front-end.
		 * Front-end contexts (controllers) - extend CG_Controller class.
		 * Back-end context (controllers) - extend the CG_Controller_Admin class.
		 */
		global $back_contexts, $front_contexts;
		$back_contexts  = array('report', 'setting', 'user', 'content', 'help');
		$front_contexts = array('ajax', 'api');

		// Muat file config
		$_config->load('cg_config', true);
        $options_to_config = $_config->config['cg_config'];

		// Muat table options
		$db_options = self::DB()->get('options')->result();
		foreach ($db_options as $option) {
			// Kami menetapkan opsi basis data ke dalam konfigurasi
			$options_to_config[$option->name] = from_bool_or_serialize($option->value);
		}

		// Do we have any manually set config items in the database
		if (isset($options_to_config) && is_array($options_to_config))
		{
			foreach ($options_to_config as $key => $value)
			{
				$_config->set_item($key, $value);
			}
		}
	}

	// -----------------------------------------------------------------------------

	/**
	 * Method for defining all initial constants.
	 */
	public static function constants()
	{
		// Constants useful for expressing human-readable data sizes
		defined('KB_IN_BYTES')   		OR define('KB_IN_BYTES', 1024);
		defined('MB_IN_BYTES')    		OR define('MB_IN_BYTES', 1024 * KB_IN_BYTES);
		defined('GB_IN_BYTES')    		OR define('GB_IN_BYTES', 1024 * MB_IN_BYTES);
		defined('TB_IN_BYTES')    		OR define('TB_IN_BYTES', 1024 * GB_IN_BYTES);

		// Terapkan intervals waktu ke dalam constants.
		defined('MINUTE_IN_SECONDS')    OR define('MINUTE_IN_SECONDS', 60);
		defined('HOUR_IN_SECONDS')      OR define('HOUR_IN_SECONDS',   60 * MINUTE_IN_SECONDS);
		defined('DAY_IN_SECONDS')       OR define('DAY_IN_SECONDS',    24 * HOUR_IN_SECONDS);
		defined('WEEK_IN_SECONDS')      OR define('WEEK_IN_SECONDS',    7 * DAY_IN_SECONDS);
		defined('MONTH_IN_SECONDS')     OR define('MONTH_IN_SECONDS',  30 * DAY_IN_SECONDS);
		defined('YEAR_IN_SECONDS')      OR define('YEAR_IN_SECONDS',  365 * DAY_IN_SECONDS);
	}

	// -----------------------------------------------------------------------------

	/**
	 * Guna mengambil beberapa data yang di butuhkan diawal eksekusi.
	 */
	public static function DB()
	{
		// buat result menjadi object
		static $DB;

		// Is the config file in the environment folder
		if ( file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
			OR file_exists($file_path = APPPATH.'config/database.php'))
		{
			include($file_path);

			$hostname = $db['default']['hostname'];
			$database = $db['default']['database'];
			$username = $db['default']['username'];

			if (empty($hostname)
				|| empty($database)
				|| empty($username)
			) {
				$_error =& load_class('Exceptions', 'core');
				echo $_error->show_error('Codeigniter Gamelang', '', 'cg_install');
				exit();
			}
		}

		// jika variable belum ditetapkan
		if (empty($DB))
		{
			// Jika file belum di muat.
			if ( ! function_exists('DB'))
			{
				require_once(BASEPATH.'database/DB.php');
			}

			$DB =& DB();
		}

		return $DB;
	}
}