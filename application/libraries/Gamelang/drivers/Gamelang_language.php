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
 * Gamelang_language Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class Gamelang_language extends CI_Driver
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
		$this->ci->load->helper('language');

		// Dapatkan daftar semua detail bahasa terlebih dahulu.
		$languages = $this->ci->lang->lang_lists($this->ci->config->item('languages'));

		// Pastikan bahasa saat ini tersedia untuk dilihat.
		$langs = $languages[$this->ci->session->language];
		$this->ci->themes->set( 'current_language', $langs, true );

		// Bahasa situs disimpan dalam konfigurasi.
		$config_languages = $this->ci->config->item('languages');

		// Tambahkan bahasa tersedia ke tampilan.
		$langs = array();
		if (count($config_languages) > 0)
		{
			foreach ($languages as $folder => $details)
			{
				if (in_array($folder, $config_languages)
					&& $folder !== $this->ci->session->language)
				{
					$langs[$folder] = $details;
				}
			}
		}

		// set bahasa ke dalam variabel
		$this->ci->themes->set( 'site_languages', $langs, true );

		// load main bahasa
		$this->ci->load->language('game');

		log_message('info', 'Gamelang_language Class Initialized');
	}
}
