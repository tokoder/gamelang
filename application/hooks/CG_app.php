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
	 * CI_Config
	 *
	 * @var	CI_Config
	 */
	public $config;

	/**
	 * CI_Loader
	 *
	 * @var	CI_Loader
	 */
	public $load;

	/**
	 * Class constructor
	 * @return	void
	 */
	public function __construct()
	{
		$this->config =& load_class('Config', 'core');
		$this->load =& load_class('Loader', 'core');

		// mari kita cek file htaccess
		if ( ! file_exists(FCPATH . ".htaccess"))
		{
			// tampilkan informasi tentang htaccess
			self::htaccess();
		}

		// mari kita cek file config
		if ( ! $this->config->load('cg_config', true, true))
		{
			// jalankan installasi
			self::install();
		}
	}

	// -----------------------------------------------------------------------------

	/**
	 * Denfinisikan semua constants
	 */
	function init()
	{
		// Setup default constants.
		self::constants();

		// Load some base functions that we added to CodeIgniter.
		$this->load->helper(['string', 'array', 'path']);

		/**
		 * Site contexts.
		 *
		 * Ada 2 kategori contexts: back-end and front-end.
		 * Front-end contexts (controllers) - extend CG_Controller class.
		 * Back-end context (controllers) - extend the CG_Controller_Admin class.
		 */
		global $back_contexts, $front_contexts;
		$front_contexts = array('ajax', 'process', 'api');
		$back_contexts  = array('settings', 'user', 'content', 'reports', 'help');

		// Muat file config
        $config_file = $this->config->config['cg_config'];

		// Muat table options
		$db_options = self::DB()->get('options')->result();
		foreach ($db_options as $option)
		{
			// Kami menetapkan opsi basis data ke dalam konfigurasi
			$config_file[$option->name] = from_bool_or_serialize($option->value);
		}

		// Gabungkan nilai konfigurasi
		global $assign_to_config;
		is_array($assign_to_config) OR $assign_to_config = array();
		$assign_to_config = array_merge($assign_to_config, $config_file);
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
	public static function DB() {

		// buat result menjadi object
		static $DB;

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

	// -----------------------------------------------------------------------------

	/**
	 * Format informasi tentang htaccess
	 */
	public static function htaccess()
	{
		echo ".htaccess file does not exist on main directory of your site. You can find this file in the main directory of script files. You need to upload this file to your site.<br>";
		echo "Depending on the operating system you are using, such setting files may be hidden in your computer. In this case, you may not see this file.<br><br>";
		echo "If you can't see this file, you can create a new file named \".htaccess\" in the main directory of your site and you can paste the following codes to your .htaccess file.<br><br>";
		echo "<strong>RewriteEngine On<br>";
		echo "RewriteCond %{REQUEST_FILENAME} !-f<br>";
		echo "RewriteCond %{REQUEST_FILENAME} !-d<br>";
		echo "RewriteRule ^(.*)$ index.php?/$1 [L]</strong>";
		exit();
	}

	// -----------------------------------------------------------------------------

	/**
	 * Laman instalasi
	 */
	public static function install()
	{
		$install_url = config_item('base_url');
		$install_url .= 'install';

		echo '<h1>Apps not installed</h1>';
		echo '<p>1. To you use the automatic Apps installation tool click <a href="' . $install_url . '">here (' . $install_url . ')</a> (maintenance)</p>';
		echo '<p>2. If you are installing manually rename the config file located in application/config/cg_config.php.dist to cg_config.php and populate the defined fields.</p>';
		die();
	}
}